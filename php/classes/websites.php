<?php

/**
 * So, this is pretty stupid, but it should cover the tip of the complexity so it doesn't matter.
 * I hate that PHP types aren't strict at all.
 */

declare(strict_types=1);
require_once ($_SERVER['DOCUMENT_ROOT'].'/php/classes/PostgreSQL.php');

class Websites
{
    // Settings
    public const MAX_TIMEOUT = 6.0; //s
    public const MIN_TIMEOUT = 0.3; //s

    private static $instance;
    public static function init()
    {
        // Check is $_instance has been set
        if(!isset(self::$instance))
        {
            // Creates sets object to instance
            self::$instance = new Websites();
        }

        // Returns the instance
        return self::$instance;
    }

    private function __construct(){
        $this->psql = PostgreSQL::init();
    }

    private array $websites = [];

    public function getWebsites(array|string|int $id = null) : array{
        if (is_array($id)) $id = implode(",", $id);
        $WHERE = "";
        if (isset($id)) $WHERE = "WHERE w.id IN ($id)";

        /**
         * Setting up the SQL query
         * TODO: wants to order by clicks_last_24h * 1000 + clicks
         */
        $sql = <<<SQL
        SELECT
            w.id,
            w.name,
            w.description,
            w.url,
            w.ping,
            w.return_code,
            w.up,
            w.test_reason,
            w.added_time,
            w.last_active,
            w.last_checked,
            COALESCE(cats.names, '[]') as categories,
            COUNT(c.proxy_id) as clicks,
            COUNT(CASE WHEN c.time >= NOW() - INTERVAL '24 HOURS' THEN c.proxy_id END) as clicks_last_24h
        FROM
            website w
        LEFT JOIN (
            SELECT
                jsonb_agg(cat.name) as names,
                wc.web_id
            FROM
                categories cat
            INNER JOIN website_categories wc on cat.id = wc.cat_id
            GROUP BY wc.web_id
        ) cats ON cats.web_id = w.id
        LEFT JOIN proxy_list pl ON pl.web_id = w.id
        LEFT JOIN clicks c ON c.proxy_id = pl.id
        $WHERE
        GROUP BY
            w.id, cats.names
        ORDER BY
            clicks DESC;
        SQL;

        $options = [
            "columns" => [
                "ping::float",
                "up::bool",
                "added_time::datetime",
                "last_active::datetime",
                "last_checked::datetime",
                "categories::string[]"
            ],
            "cache" => [
                "name" => "websites_cache_" . md5(json_encode($id)),
                "expire" => 900, // 6 hours
                "dynamic" => [
                    "query" => "SELECT md5(string_agg(last_checked::text, '')) as hash FROM website"
                ]
            ]
        ];
        $this->websites = $this->psql->query($sql, $options);
        return $this->websites;
    }

    public function getProxies(array|string $id = null, $limit){
        if (is_array($id)) $id = implode(",", $id);
        $WHERE = "";
        if (isset($id)) $WHERE = "WHERE pl.web_id IN ($id)";

        if ($limit > 0) {
            $sql = <<<SQL
            WITH cte AS (
                SELECT
                    id,
                    web_id,
                    url,
                    official,
                    ping,
                    return_code,
                    up,
                    test_reason,
                    added_time,
                    last_active,
                    last_checked,
                    COUNT(c.proxy_id) clicks,
                    COUNT(CASE WHEN c.time >= NOW() - INTERVAL '24 HOURS' THEN c.proxy_id END) as clicks_last_24h,
                    ROW_NUMBER() OVER (PARTITION BY web_id ORDER BY COUNT(CASE WHEN c.time >= NOW() - INTERVAL '24 HOURS' THEN c.proxy_id END) DESC) as rn
                FROM
                    proxy_list pl
                LEFT JOIN clicks c on pl.id = c.proxy_id
                $WHERE
                GROUP BY id
            )
            SELECT *
            FROM cte
            WHERE rn <= $limit
            ORDER BY web_id, clicks_last_24h DESC;
            SQL;
        }else{
            $sql = <<<SQL
            WITH cte AS (
                SELECT
                    id,
                    web_id,
                    url,
                    official,
                    ping,
                    return_code,
                    up,
                    test_reason,
                    added_time,
                    last_active,
                    last_checked,
                    COUNT(c.proxy_id) clicks,
                    COUNT(CASE WHEN c.time >= NOW() - INTERVAL '24 HOURS' THEN c.proxy_id END) as clicks_last_24h
                FROM
                    proxy_list pl
                LEFT JOIN clicks c on pl.id = c.proxy_id
                $WHERE
                GROUP BY id
            )
            SELECT *
            FROM cte
            ORDER BY web_id, clicks_last_24h DESC;
            SQL;
        }

        $options = [
            "columns" => [
                "official::bool",
                "ping::float",
                "up::bool",
                "added_time::datetime",
                "last_active::datetime",
                "last_checked::datetime"
            ],
            "cache" => [
                "name" => "proxy_list_cache_" . md5(json_encode($id)),
                "expire" => 900, // 6 hours
                "dynamic" => [
                    "query" => "SELECT md5(string_agg(last_checked::text, '')) as hash FROM website"
                ]
            ]
        ];
        return $this->psql->query($sql, $options);
    }

    /**
     * @param $webIdOrName string|int|null Provide the id of the website, or give it a search query. The search query only allows [a-zA-Z0-9-_.]
     * @param int $proxyLimit
     * @return null|array{array{
     *   id: int,
     *   name: string,
     *   description: string|null,
     *   ping: float,
     *   return_code: int,
     *   up: bool,
     *   test_reason: string|null,
     *   added_time: DateTimeImmutable,
     *   last_active: DateTimeImmutable,
     *   last_checked: DateTimeImmutable,
     *   categories: string[],
     *   clicks: int,
     *   clicks_last_24h: int,
     *   proxies: array{array{
     *      id: int,
     *      web_id: int,
     *      url: string,
     *      official: bool,
     *      ping: float,
     *      return_code: int,
     *      up: bool,
     *      test_reason: string|null,
     *      added_time: DateTimeImmutable,
     *      last_active: DateTimeImmutable,
     *      last_checked: DateTimeImmutable,
     *      clicks: int,
     *      clicks_last_24h: int,
     *      rn: int
     *   }}
     * }}
     */
    public function getEverything($webIdOrName = null, $proxyLimit = 5) : null|array{
        $id = null;
        if (is_numeric($webIdOrName)) $id = $webIdOrName;
        else if (!is_null($webIdOrName)){
            $id = $this->getWebIdByName($webIdOrName);
            if (!$id) return null;
        }

        $websites = $this->getWebsites($id);
        $ids = array_column($websites, "id");
        $proxies = $this->getProxies($ids, $proxyLimit);

        // Now connect them together
        foreach ($websites as &$web){
            if (!isset($web["proxies"])) $web["proxies"] = [];
            foreach ($proxies as $proxy){
                if ($proxy["web_id"] == $web["id"]){
                    $web["proxies"][] = $proxy;
                }
            }
        }

        $this->websites = $websites;
        return $this->websites;
    }

    private PostgreSQL $psql;

    private function getWebIdByName(string $query) : int|null
    {
        $query = '%' . preg_replace("/[^a-z0-9]+/", "%", strtolower($query)) . '%';
        $sql = <<<SQL
        SELECT
            w.id
        FROM
            website w
        WHERE
            w.name ILIKE :query
        SQL;

        $options = [
            "data" => [
                "query" => $query
            ],
            "cache" => [
                "name" => "web_id_from_" . md5($query),
                "expire" => 900
            ],
            "single" => true
        ];
        $result = $this->psql->query($sql, $options);
        if (!$result || !isset($result["id"])) return null;
        return (int)$result["id"];
    }
}