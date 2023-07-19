<?php

require_once ($_SERVER['DOCUMENT_ROOT'].'/php/classes/PostgreSQL.php');
$psql = PostgreSQL::init();
require_once($_SERVER['DOCUMENT_ROOT'].'/php/classes/api.php');
$api = Api::init();
include_once($_SERVER["DOCUMENT_ROOT"] . '/php/classes/func/func.php');
$func = Func::init();


[$ok, $keys] = $api->keys_exist($_GET, ["id" => "int"]);
if (!$ok) $api->error("Missing or malformed field(s): " . implode(", ", array_keys($keys)), 400);

$proxy_id = (int)$_GET["id"];
$identifier = hash("sha256",$func::$ip->ip . $_SERVER["HTTP_USER_AGENT"]);
if (strlen($identifier) != 64) $api->error("Identifier not 64 char long", 500);


$sql = <<<SQL
INSERT INTO clicks (identifier, proxy_id) VALUES (:identifier, :proxy_id) ON CONFLICT (identifier, proxy_id) DO UPDATE SET time = now()
SQL;

$rowCount = $psql->insert($sql, ["data" => ["identifier" => $identifier, "proxy_id" => $proxy_id]]);

$api->print(["click_added" => $rowCount > 0], $rowCount > 0 ? 201 : 200);