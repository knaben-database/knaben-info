<?php

include_once($_SERVER["DOCUMENT_ROOT"] . '/php/classes/func/func.php');
$func = Func::init();

// Restrict to only me
if ($func::$ip->ip != "94.101.114.183"){
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

$dates = [
    "2023-07-16 23:32:37.817942+00",
    "2023-07-16 23:32:37.817942-01",
    "2023-07-16+02",
    "2023-07-16-03",
    "2023-07-16 23:32:37 +04",
    "2023-07-16 23:32:37-05",
    "2023-07-16",
    "07-16-2023",
    "07-16-2023+06",
    "07-12+07",
    "2023-07-16 23:32:37",
    "2023-07-16 23:32:37.817942"
];

// (\d+-\d+-\d+((\-|\+)\d+)$)|\.\d+((\-|\+)\d+$)|:\d+((\-|\+)\d+$)
$pattern = "/(?<!\d\d\d\d-\d\d)([+-]\d\d$)/";
foreach ($dates as $date){
    if(preg_match($pattern, $date, $matches) !== FALSE && isset($matches[0])) // Outputs 1
        echo $matches[0] . "\n";
}

?>
</pre>

<script>

</script>


<!-- Testing ends here -->

<div style="z-index: 111;position: fixed;bottom: 0;left: 0;" class="bg-darkblue">
    <p style="color: lime;margin: 0;padding: 12px;">The page loaded correctly nice!</p>
</div>
</body>
</html>
