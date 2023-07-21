<?php
$TIMEMEASURE = microtime(TRUE);
$LOGO_CORE_MESSAGE = "404";
$LOGO_SHOW_CORE = true;
$LOGO_COLOR_THEME = "var(--bs-red)";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Knaben's Proxy List</title>
    <link rel="icon" href="/favicon.ico">
    <meta name="description" content="Proxy for a various of websites, including our own. Only working proxies are shown">
    <meta name="keywords" content="proxy, list, workaround, bypass, working, free, knaben"/>
    <link rel="stylesheet" href="/css/style.css?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'].'/css/style.css') ?>">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/main.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'].'/js/main.js') ?>"></script>
</head>
<body>
<div class="m-auto mt-5" style="aspect-ratio: 1/1;width: 25%;">
    <?php include "$_SERVER[DOCUMENT_ROOT]/include/logo.php" ?>
</div>
<div class="m-auto text-center text-darkblue fs-0">ERROR <div class="text-red d-inline">404</div></div>
<hr class="mx-5"/>
<div class="text-center text-white d-flex justify-content-center">Let's go&nbsp;<div class="fancy-link"><a href="/">home</a></div>&nbsp;instead, away from this mess&nbsp;<i>oof!</i></div>
<?php include_once "$_SERVER[DOCUMENT_ROOT]/include/footer.php" ?>
</body>
</html>