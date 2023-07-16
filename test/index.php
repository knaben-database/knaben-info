<?php

// Restrict to only me
if ($_SERVER["REMOTE_ADDR"] != "192.168.1.32"){
    echo "こんにちは！";
    die;
}

// Debugging
include_once($_SERVER["DOCUMENT_ROOT"].'/test/debug.php');

// Includes
include_once("$_SERVER[DOCUMENT_ROOT]/php/functionality/salt.php");
require_once ($_SERVER['DOCUMENT_ROOT'].'/php/classes/PostgreSQL.php');
$psql = PostgreSQL::init();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TEST - knaben.info</title>
    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body style="color: white; overflow: auto">
<div style="z-index: 110;position: fixed;bottom: 0;left: 0;" class="bg-dark2">
    <p style="color: red;margin: 0;padding: 12px;">The page broke!</p>
</div>

<!-- Here starts the testing -->


<?php

function psswd() : string {
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
const SETTINGS = [
    "host" => "127.0.0.1",
    "port" => 5432,
    "user" => "postgres"
];
$dsn = "pgsql:host=".SETTINGS["host"].";port=".SETTINGS["port"].";dbname=knabeninfo;";
$dbh = new PDO($dsn, SETTINGS["user"], psswd());
$sql = <<<SQL
SELECT
    w.id,
    w.name,
    w.description,
    w.ping,
    w.return_code,
    w.up,
    w.test_reason,
    w.added_time,
    w.last_active,
    COUNT(c.proxy_id) as clicks,
    COUNT(CASE WHEN c.time >= NOW() - INTERVAL '24 HOURS' THEN c.proxy_id END) as clicks_last_24h
FROM
    website w
LEFT JOIN proxy_list pl ON pl.web_id = w.id
LEFT JOIN clicks c ON c.proxy_id = pl.id
GROUP BY
    w.id;
SQL;
$sth = $dbh->prepare($sql);
$sth->execute();

echo json_encode($sth->fetchAll(PDO::FETCH_ASSOC));

?>

<script>

</script>


<!-- Testing ends here -->

<div style="z-index: 111;position: fixed;bottom: 0;left: 0;" class="bg-dark2">
    <p style="color: lime;margin: 0;padding: 12px;">The page loaded correctly nice!</p>
</div>
</body>
</html>
