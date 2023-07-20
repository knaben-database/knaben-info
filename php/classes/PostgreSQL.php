<?php

require_once("$_SERVER[DOCUMENT_ROOT]/test/debug.php");
require_once("$_SERVER[DOCUMENT_ROOT]/php/functionality/salt.php");


class PostgreSQL
{
    /**
     * The settings, tune if needed
     */
    private const SETTINGS = [
        "host" => "127.0.0.1",
        "port" => 5432,
        "user" => "postgres"
    ];

    /**
     * The function to get the password
     * @return string
     */
    private static function psswd() : string {
        $value1 = "aRUO4FqJ1KuMDsaLWBpQXA==";
        $value2 = "LWpDn4gMiPquvfqCcQYxng==";
        $keys = file_get_contents('/home/***REMOVED***/Documents/psswd_keys.txt');
        $key1 = trim(explode("\n", $keys)[0]);
        $key2 = trim(explode("\n", $keys)[1]);
        $psswd1 = openssl_decrypt($value1,"AES-256-ECB", $key1);
        $psswd1 = strtolower(strrev($psswd1));
        $psswd2 = openssl_decrypt($value2,"AES-128-ECB", $key2);
        $psswd2 = strtolower(strrev($psswd2));
        return salt($psswd1, $psswd2);
    }

    private static $instance;
    public static function init(string $db = "knabeninfo")
    {
        // Check is $_instance has been set
        if(!isset(self::$instance[$db]))
        {
            // Creates sets object to instance
            self::$instance[$db] = new PostgreSQL($db);
        }

        // Returns the instance
        return self::$instance[$db];
    }

    public function __construct(string $db = "knabeninfo")
    {
        // Settings
        $host = self::SETTINGS["host"];
        $user = self::SETTINGS["user"];
        $port = self::SETTINGS["port"];
        $psswd = self::psswd();

        $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
        try {
            $this->dbh = new PDO($dsn, $user, $psswd);
        }catch (PDOException $exc) {
            echo $exc->getMessage();
        }

        // Connect to the memcache server
        $this->memCache = new Memcached();
        $this->memCache->addServer("127.0.0.1", 11211);
    }

    /** Function to execute an SQL query
     * @param string $sql The SQL query or filename found in /sql to execute containing question mark, or string matches
     * @param array{
     *   data: array,
     *   cache: array{
     *     name: string,
     *     expire: int,
     *     dynamic: array{
     *       query: string
     *     }
     *   },
     *   single: bool
     * }|null $options Options for what to do with the query
     * @return array|false
     */
    public function query(string $sql, array $options = null){
        //if (ctype_print($sql) && (file_exists("$_SERVER[DOCUMENT_ROOT]/sql/$sql.sql") || file_exists($sql))) {
        //    $queryInfo = $this->SQLFile($sql);
        //    if (isset($queryInfo["options"]) && $queryInfo["options"] != null) {
        //        if ($options == null)
        //            $options = $queryInfo["options"];
        //        else
        //            $options = array_merge_recursive($queryInfo["options"], $options);
        //    }if (isset($queryInfo["sql"]) && $queryInfo["sql"] != null)
        //        $sql = $queryInfo["sql"];
        //}

        // First and foremost, check cache
        $cache_name = $options["cache"]["name"] ?? md5($sql);
        $cache_timeout = 45;
        $pre_cache = null;
        if (isset($options["cache"])) {
            if (isset($options["cache"]["expire"]))
                $cache_timeout = ($options["cache"]["expire"] == "never") ? 60 * 60 * 24 * 14 : $options["cache"]["expire"]; // 2 weeks

            // Decreases time spent to ~0.4578 ms
            $aData = $this->memCache->get($cache_name);
            if ($aData) {
                // Dynamic query checks if retrieved single column appears in the full query, if not then discard cache
                if (isset($options["cache"]["dynamic"], $options["cache"]["dynamic"]["query"])){
                    $sth = $this->dbh->prepare($options["cache"]["dynamic"]["query"]);
                    $sth->execute();
                    $result = $sth->fetch();
                    $pre_cache = md5(json_encode($result[0] ?? ""));
                    if (isset($aData["cache"]) && $pre_cache == $aData["cache"]) {
                        return $aData["result"];
                    }else {
                        $this->memCache->delete($cache_name);
                    }
                }else {
                    return $aData["result"];
                }
            }
        }

        $result = $this->execute($sql, $options);

        // Correctly format the data to the correct data type
        if (isset($options["columns"])){
            $this->dataTypeCorrection($result, $options["columns"]);
        }

        // Handling point_columns in $options
        if (isset($options["point_columns"])){
            foreach ($options["point_columns"] as $column){
                foreach ($result as $key => $row){
                    if (!isset($row[$column]))
                        continue;
                    $newArray = [];
                    foreach (explode(",", mb_substr($row[$column], 1, -1)) as $value)
                        $newArray[] = (float)$value;
                    $result[$key][$column] = $newArray;
                }
            }
        }

        if (isset($options["single"]) && $options["single"] && $result){
            $result = $result[0];
        }

        if (isset($options["cache"])) {
            if ($pre_cache != null)
                $this->memCache->set($cache_name, ["result" => $result, "cache" => $pre_cache], (int)$cache_timeout);
            else
                $this->memCache->set($cache_name, ["result" => $result], (int)$cache_timeout);
        }
        return $result;
    }

    /** Function to execute an SQL query with update in mind
     * @param string $sql The SQL query or filename found in /sql to execute containing question mark, or string matches
     * @param array{
     *   data: array
     * }|null $options Options for what to do with the query
     * @param bool $force This is used if you want to force a query with no WHERE statement
     * @return int|array The amount of rows affected
     * @throws Exception Might fail if no WHERE clause is set
     */
    public function update(string $sql, array $options = null, bool $force = false){
        if (ctype_print($sql) && (file_exists("$_SERVER[DOCUMENT_ROOT]/sql/$sql.sql") || file_exists($sql))) {
            $queryInfo = $this->SQLFile($sql);
            if (isset($queryInfo["options"]) && $queryInfo["options"] != null) {
                if ($options == null)
                    $options = $queryInfo["options"];
                else
                    $options = array_merge_recursive($queryInfo["options"], $options);
            }if (isset($queryInfo["sql"]) && $queryInfo["sql"] != null)
                $sql = $queryInfo["sql"];
        }

        if (strpos($sql, ";\n") !== FALSE){
            $results = [];
            foreach (explode(";\n", $sql) as $item) {
                $data = [];
                if (trim($item) == "")
                    continue;

                if (isset($options, $options["data"])){
                    foreach ($options["data"] as $key => $value){
                        if (strpos($item, ":$key") !== FALSE){
                            $data[$key] = $value;
                        }
                    }
                }
                $options2 = $options;
                $options2["data"] = $data;
                // Quickly check that there is a WHERE statement
                if (!$force && strpos(strtolower($item), "where") === FALSE){
                    throw new Exception("Trying to do an UPDATE statement, but without a WHERE clause!");
                }
                $results[] = $this->execute($item, $options2, "rowcount");
            }
            return $results;
        }

        // Quickly check that there is a WHERE statement
        if (!$force && strpos(strtolower($sql), "where") === FALSE){
            throw new Exception("Trying to do an UPDATE statement, but without a WHERE clause!");
        }

        return $this->execute($sql, $options, "rowcount");
    }

    /** Function to execute an SQL query with deletion in mind
     * @param string $sql The SQL query or filename found in /sql to execute containing question mark, or string matches
     * @param array{
     *   data: array
     * }|null $options Options for what to do with the query
     * @param bool $force This is used if you want to force a query with no WHERE statement
     * @return int The amount of rows affected
     * @throws Exception Might fail if no WHERE clause is set
     */
    public function delete(string $sql, array $options = null, bool $force = false){
        if (ctype_print($sql) && (file_exists("$_SERVER[DOCUMENT_ROOT]/sql/$sql.sql") || file_exists($sql))) {
            $queryInfo = $this->SQLFile($sql);
            if (isset($queryInfo["options"]) && $queryInfo["options"] != null) {
                if ($options == null)
                    $options = $queryInfo["options"];
                else
                    $options = array_merge_recursive($queryInfo["options"], $options);
            }if (isset($queryInfo["sql"]) && $queryInfo["sql"] != null)
                $sql = $queryInfo["sql"];
        }

        // Quickly check that there is a WHERE statement
        if (!$force && strpos(strtolower($sql), "where") === FALSE){
            throw new Exception("Trying to do a DELETE statement, but without a WHERE clause!");
        }

        return $this->execute($sql, $options, "rowcount");
    }

    /** Function to execute an SQL query with insert in mind
     * @param string $sql The SQL query or filename found in /sql to execute containing question mark, or string matches
     * @param array{
     *   data: array,
     *   ignore_conflict: bool,
     * }|null $options Options for what to do with the query
     * @return int The amount of rows affected
     */
    public function insert(string $sql, array $options = null): int{
        if (ctype_print($sql) && (file_exists("$_SERVER[DOCUMENT_ROOT]/sql/$sql.sql") || file_exists($sql))) {
            $queryInfo = $this->SQLFile($sql);
            if (isset($queryInfo["options"]) && $queryInfo["options"] != null) {
                if ($options == null)
                    $options = $queryInfo["options"];
                else
                    $options = array_merge_recursive($queryInfo["options"], $options);
            }if (isset($queryInfo["sql"]) && $queryInfo["sql"] != null)
                $sql = $queryInfo["sql"];
        }

        // Ignore conflict if desired
        if (isset($options["ignore_conflict"]) && $options["ignore_conflict"])
            $sql = rtrim(trim($sql), ";") . " ON CONFLICT DO NOTHING";


        return $this->execute($sql, $options, "rowcount");
    }

    /** Function to check if value exists within table
     * @param string $table The table name
     * @param array{
     *   key: string
     * }[]|null $values This contains all the keys matching values. Can be null, one or several.
     * It can look something like this:
     * [
     *    post_id => 2344,
     *    user_id => 11
     * ]
     * @return bool Returns true if found, false if not
     */
    public function exists(string $table, ?array $values = null) : bool{
        $safeTable = preg_replace('/[^a-zA-Z0-9_ ]/', '', $table);

        $sql = "SELECT COUNT(*) AS count FROM $safeTable";
        $data = null;

        if (!is_null($values)){
            $firstLoop = true;
            foreach ($values as $key => $value){
                $safeKey = preg_replace('/[^a-zA-Z0-9_ ]/', '', $key);

                if ($firstLoop){
                    $data = array();
                    $sql .= " WHERE $safeKey = :$safeKey";
                    $firstLoop = false;
                }else{
                    $sql .= " AND $safeKey = :$safeKey";
                }
                $data[$safeKey] = $value;
            }
        }

        $results = $this->execute($sql, ["data" => $data]);
        if ($results[0]["count"] > 0)
            return true;

        return false;
    }

    /**
     * @param string $sql the sql query to run
     * @param array{
     *     data: array|null,
     *     single: bool|null
     * }|null $options Options to pass to the function
     * @param string|null $type rowcount|normal
     * @return mixed returns the desired result based on the $type provided
     */
    private function execute(string $sql, ?array $options = null, string $type = null){
        $sth = $this->dbh->prepare($sql);

        if (isset($options, $options["data"]) && !empty($options["data"])) {
            // Loop over the keys, yes bing AI suggested this?
            foreach ($options["data"] as $key => $value){
                if (is_array($value)){
                    $values = implode(", ", $value);
                    $sth->bindValue(":$key", '{'.$values.'}');
                }else{
                    $sth->bindValue(":$key", $value);
                }
            }
        }
        $sth->execute();

        switch ($type){
            case "rowcount":
                return $sth->rowCount();
            default:
                return (isset($options, $options["single"]) && $options["single"]) ? [$sth->fetch(PDO::FETCH_NAMED)] : $sth->fetchAll(PDO::FETCH_NAMED);
        }
    }


    private function dataTypeCorrection(array &$info, array $columns){
        foreach ($info as $key => &$doc){
            if (!$doc) // This might happen if there is no results returned from a query, and this function gets called
                continue;
            foreach ($columns as $column){
                if (strpos($column, '::') === FALSE)
                    continue;

                $splits = explode("::", $column);

                $column = $splits[0];
                $type = [end($splits), false];
                if (substr($type[0], -2) == "[]")
                    $type = [substr($type[0], 0, -2), true];

                if (substr_count($column, ".") > 0) {
                    $this->handleJSON($doc, $column, $type);
                    continue;
                }

                if ($type[1]){ // In this case, it's an array
                    $arr = [];
                    foreach (explode(",", mb_substr($info[$key][$column], 1, -1)) as $value) {
                        if ($value == "")
                            continue;
                        $arr[] = $this->convert($value, $type[0]);
                    }
                    $info[$key][$column] = $arr;
                }else{
                    $info[$key][$column] = $this->convert($info[$key][$column], $type[0]);
                }

            }
        }
    }

    private function convert($item, $type){
        if (is_null($item)) return null;

        if ($type == "point"){
            $newArray = [];
            foreach (explode(",", mb_substr($item, 1, -1)) as $value)
                $newArray[] = (float)$value;
            return $newArray;
        }
        switch ($type){
            case "bit":
                if (strlen(trim($item)) % 4 !== 0) throw new Exception("Can't divide bit value by 4");
                $bits = str_split($item, 4);
                $item = "";
                foreach ($bits as $bit){
                    $item .= dechex(bindec($bit));
                }
                break;
            case "int":
                $item = (int)($item);
                break;
            case "bool":
            case "boolean":
                if      ($item === "true"  || $item === 1 || $item === true)  $item = true;
                else if ($item === "false" || $item === 0 || $item === false) $item = false;
                else                                                          $item = (bool)($item);
                break;
            case "float":
                $item = (float)($item);
                break;
            case "datetime":
                // Check if timezone
                $pattern = "/(?<!\d\d\d\d-\d\d)([+-]\d\d$)/";
                if(preg_match($pattern, $item, $matches) !== FALSE && isset($matches[0])) {
                    $tz = $matches[0];
                    $item = explode($tz, $item)[0];
                    $item = new DateTimeImmutable($item, new DateTimeZone($tz));
                }else
                    $item = new DateTimeImmutable($item);
                break;
            case "json":
                $item = json_decode($item, true);
                break;
            case "string":
                $item = (substr(trim($item), 0, 1) == '"' && substr(trim($item), -1) == '"') ? trim(trim($item), '"') : trim($item);
                break;
        }
        return $item;
    }

    private function handleJSON(&$doc, $column, $type){
        $depth = 0;
        $maxDepth = substr_count($column, ".");
        while ($maxDepth >= $depth && $maxDepth > 0){ // If it needs depth into the object
            $focusColumn = explode(".", $column)[$depth];
            if ($focusColumn == ""){ // It's an array
                foreach($doc as &$item) {
                    $newSplits = explode(".", $column);
                    $newSplits = array_splice($newSplits, $depth+1); // Get the new column: links..time → time
                    $newColumn = implode(".", $newSplits);
                    $this->handleJSON($item, $newColumn, $type);
                }
            }else if (isset($doc[$focusColumn])) {
                if ($depth == $maxDepth) {
                    $doc[$focusColumn] = $this->convert($doc[$focusColumn], $type[0]);
                    return true;
                }
                $doc = &$doc[$focusColumn];
            }else{
                return false;
            }
            $depth++;
        }
        if (is_null($doc))
            return false;

        $doc[$column] = $this->convert($doc[$column], $type[0]);
        return true;
    }

    ///
    //  private variables
    ///

    private PDO $dbh;

    private Memcached $memCache;
}