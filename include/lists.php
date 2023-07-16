<?php

require_once ($_SERVER['DOCUMENT_ROOT'].'/test/debug.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/php/classes/PostgreSQL.php');
$psql = PostgreSQL::init();

/**
 * @var array{array{
 *   id: int,
 *   name: string,
 *   description: string|null,
 *   ping: float,
 *   return_code: int,
 *   up: bool,
 *   test_reason: string|null,
 *   added_time: DateTimeImmutable,
 *   last_active: DateTimeImmutable,
 *   clicks: int,
 *   clicks_last_24h: int,
 *   proxies: array{array{
 *      id: int
 *   }}
 * }} $websites
 */
$websites = [];



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
    COUNT(c.proxy_id) as clicks,
    COUNT(CASE WHEN c.time >= NOW() - INTERVAL '24 HOURS' THEN c.proxy_id END) as clicks_last_24h
FROM
    website w
LEFT JOIN proxy_list pl ON pl.web_id = w.id
LEFT JOIN clicks c ON c.proxy_id = pl.id
GROUP BY
    w.id
ORDER BY
    clicks DESC;
SQL;

$options = [
    "columns" => [
        "ping::float",
        "up::bool",
        "added_time::datetime",
        "last_active::datetime",
        "last_checked::datetime"
    ],
    "cache" => [
        "name" => "websites_cache",
        "expire" => 21600, // 6 hours
        "dynamic" => [
            "query" => "SELECT md5(string_agg(last_checked::text, '')) as hash FROM website"
        ]
    ]
];
$results = $psql->query($sql, $options);

/**
 * Now from the query above, use the output and query the relevant proxies
 */



echo "<pre>";
echo json_encode($results, JSON_PRETTY_PRINT);
echo "</pre>";