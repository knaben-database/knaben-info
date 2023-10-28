<?php

$TIMEMEASURE = microtime(TRUE);

include_once($_SERVER["DOCUMENT_ROOT"] . '/php/classes/func/func.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/php/classes/websites.php');
$func = Func::init();

// Restrict to only me
if ($func::$ip != "152.89.162.5"){
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
<div style="z-index: 110;position: fixed;bottom: 0;left: 0;" class="bg-darkblue">
    <p style="color: red;margin: 0;padding: 12px;">The page broke!</p>
</div>

<!-- Here starts the testing -->

<pre>
<?php

// echo $psql::psswd();

echo "<br/>";
echo "<br/>";

$value1 = "EAA-2A";
$psswd1 = openssl_encrypt($value1,"AES-256-ECB", "OPIAUHSF?)=(QAHFWPO");
echo $psswd1;

echo "<br/>";

$value2 = "525BTSD";
$psswd2 = openssl_encrypt($value2,"AES-128-ECB", "AG)/(HEIUEHQ)P(H#QFF");
echo $psswd2;

// $web = Websites::init();
// $websites = $web->getEverything();
//
// echo json_encode($websites, JSON_PRETTY_PRINT);


?>
</pre>

<script>

</script>


<!-- Testing ends here -->

<div style="z-index: 111;position: fixed;bottom: 0;left: 0;" class="bg-darkblue">
    <p style="color: lime;margin: 0;padding: 12px;">The page loaded correctly nice!</p>
</div>
<?php include_once "$_SERVER[DOCUMENT_ROOT]/include/footer.php" ?>
</body>
</html>
