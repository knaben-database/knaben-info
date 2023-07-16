<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Knaben's Proxy List</title>
    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/main.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'].'/js/main.js') ?>"></script>
</head>
<body>
<div class="m-auto mt-5" style="height: 350px;width: 350px;">
    <?php include "$_SERVER[DOCUMENT_ROOT]/include/logo.php" ?>
</div>
<h1 class="m-auto text-center text-darkblue fs-0">Knaben's Proxy List</h1>
<hr class="mx-5"/>
<div class="m-4">
    <?php include_once "$_SERVER[DOCUMENT_ROOT]/include/lists.php" ?>
</div>
</body>
</html>