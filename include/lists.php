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
 *   last_checked: DateTimeImmutable,
 *   categories: string[],
 *   clicks: int,
 *   clicks_last_24h: int,
 *   proxies: array{array{
 *      id: int,
 *      web_id: int,
 *      url: string,
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
        "name" => "websites_cache",
        "expire" => 21600, // 6 hours
        "dynamic" => [
            "query" => "SELECT md5(string_agg(last_checked::text, '')) as hash FROM website"
        ]
    ]
];
$websites = $psql->query($sql, $options);
$ids = implode(",",array_column($websites, "id"));


/**
 * Now from the query above, use the output and query the relevant proxies
 */
$sql = <<<SQL
WITH cte AS (
    SELECT
        id,
        web_id,
        url,
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
    WHERE
        pl.web_id IN ($ids)
    GROUP BY id
)
SELECT *
FROM cte
WHERE rn <= 10
ORDER BY web_id, clicks_last_24h DESC;
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
        "name" => "proxy_list_cache",
        "expire" => 21600, // 6 hours
        "dynamic" => [
            "query" => "SELECT md5(string_agg(last_checked::text, '')) as hash FROM proxy_list"
        ]
    ]
];
$proxies = $psql->query($sql, $options);
foreach ($websites as &$web){
    if (!isset($web["proxies"])) $web["proxies"] = [];
    foreach ($proxies as $proxy){
        if ($proxy["web_id"] == $web["id"]){
            $web["proxies"][] = $proxy;
        }
    }
}

foreach ($websites as $website): ?>
<div class="bg-darkblue bg-opacity-75 p-3 mx-2 my-4" style="width: 23%">
    <div>
        <div>
            <div class="d-grid w-100">
                <div class="text-white text-opacity-50 d-inline-flex ms-auto">Ping:&nbsp;<?= $website["ping"]*1000 ?>ms</div>
            </div>
            <h1 class="text-white mb-0"><?= $website["name"] ?></h1>
        </div>
        <div class="d-flex flex-wrap">
            <?php foreach ($website["categories"] as $category): ?>
                <div class="fs-7 px-2 me-2 mb-2 bg-white text-pink bg-opacity-25"><?= $category ?></div>
            <?php endforeach; ?>
        </div>
        <div class="w-100 d-inline-flex">
            <div class="fancy-link <?= !$website["up"] ? 'text-red' : '' ?>">
                <a title="The official link" href="<?= $website["url"] ?>"><?= $website["url"] ?></a>
            </div>
            <div class="text-opacity-50 d-inline-flex me-auto">&nbsp;<?= !$website["up"] ? '<div class="text-white">(Down)</div>' : '' ?></div>
        </div>
    </div>
    <table class="mt-2 w-100 text-nowrap">
        <thead>
            <tr class="text-pink bg-white bg-opacity-25">
                <th class="px-2">Link</th>
                <th class="px-2 text-center">Clicks 24h</th>
                <th class="px-2 text-center">Clicks</th>
                <th class="px-2 text-center">Ping</th>
            </tr>
        </thead>
        <tbody>
        <?php $showLine = false; foreach ($website["proxies"] as $proxy): ?>
            <tr class="<?= $showLine ? "border-top border-white border-opacity-25" : ""; ?>">
                <!--class="fs-7 px-2 me-2 mb-2 bg-white text-pink bg-opacity-25"-->
                <th><div class="fancy-link"><a href="<?= $proxy["url"] ?>"><?= $proxy["url"] ?></a></div></th>
                <th class="text-center text-white text-opacity-50"><?= $proxy["clicks_last_24h"] ?></th>
                <th class="text-center text-white text-opacity-50"><?= $proxy["clicks"] ?></th>
                <th class="text-center text-white text-opacity-50"><?= $proxy["ping"]*1000 ?>ms</th>
            </tr>
        <?php $showLine = true; endforeach; ?>
        </tbody>
    </table>
</div>
<?php endforeach; ?>