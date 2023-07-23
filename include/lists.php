<?php

include_once($_SERVER["DOCUMENT_ROOT"] . '/php/classes/func/func.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/php/classes/websites.php');


function colorsBetweenOffset($color1, $color2, $minTime, $maxTime, $time){
    $func = func::init();
    $percentage = max($time*1000 - $minTime*1000, 0) / ($maxTime*1000 - $minTime*1000);
    return $func::$colors::colorBetween($color1, $color2, $func::$math::easeOut($percentage, 0.8));
}

$web = Websites::init();
$websites = $web->getEverything();

/**
 * Since the last_checked is set on all rows, and all rows are updated at the same time we do this
 * @var $lastChecked DateTimeImmutable
 */
$lastChecked = $websites[0]["last_checked"];
$updatedMins = round(((new DateTimeImmutable())->getTimestamp() - $lastChecked->getTimestamp())/60);
?>
    <div class="position-absolute top-0 end-0 pe-4 text-darkblue text-opacity-75" data-bs-toggle="tooltip" title="<?= $lastChecked->format("Y-m-d H:i:sT") ?>" style="transform: translateY(-100%);">
        <?= 0 >= $updatedMins ? "Updated now" : "Updated {$updatedMins}m ago" ?>
    </div>
<?php

foreach ($websites as $website):
    $moreInfoUrl = preg_replace("/[^a-z0-9]+/", "-",strtolower($website["name"]));
    $textColor = "";
    if (in_array($website["return_code"], [403, 401])) $textColor = "text-white";
    else if (!$website["up"]) $textColor = "text-red";
    ?>
<div class="bg-darkblue bg-opacity-75 p-3 mx-2 my-4 position-relative proxyBox">
    <div>
        <div class="d-flex w-100">
            <div class="w-100 d-grid">
                <div class="fancy-link z-2 <?= $textColor ?>">
                    <a title="The official link" href="<?= $website["url"] ?>"><?= $website["url"] ?></a>
                </div>
                <div>
                    <?php if (!$website["up"] && !in_array($website["return_code"], [403, 401])): ?>
                        <i class="text-white text-opacity-50"><?= $website["test_reason"] ?? 'Down' ?></i>
                    <?php endif; ?>
                </div>
            </div>
            <div style="color: <?= colorsBetweenOffset("#FFFFFF", "#FF0000", $web::MIN_TIMEOUT, $web::MAX_TIMEOUT, $website["ping"]) ?>" class="d-inline-flex ms-auto">Ping:&nbsp;<?= round($website["ping"]*1000) ?>ms</div>
        </div>
        <h1 class="text-white mb-0 fancy-link">
            <a title="More info" href="/website/<?= $moreInfoUrl ?>"><?= $website["name"] ?></a>
        </h1>
    </div>
    <div class="d-flex flex-wrap">
        <?php foreach ($website["categories"] as $category): ?>
            <div class="fs-7 px-2 me-2 mb-2 bg-white text-pink bg-opacity-25"><?= $category ?></div>
        <?php endforeach; ?>
    </div>
    <table class="mt-2 w-100 text-nowrap" style="table-layout: fixed">
        <colgroup>
            <col style="width: auto">
            <col style="width: 76px;">
            <col style="width: 55px;">
            <col style="width: 47px;">
        </colgroup>
        <thead>
            <tr class="text-pink bg-white bg-opacity-25">
                <th class="px-2">Link</th>
                <th class="px-2 text-center w-min">Clicks 24h</th>
                <th class="px-2 text-center w-min">Clicks</th>
                <th class="px-2 text-center w-min">Ping</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $showLine = false;
        foreach ($website["proxies"] as $proxy):
            $textColor = "";
            if (in_array($proxy["return_code"], [403, 401])) $textColor = "text-white";
            else if (!$proxy["up"]) continue;
            ?>
            <tr class="<?= $showLine ? "border-top border-white border-opacity-25" : ""; ?>">
                <!--class="fs-7 px-2 me-2 mb-2 bg-white text-pink bg-opacity-25"-->
                <th>
                    <div class="position-relative">
                        <div class="fancy-link mw-100 text-overflow-ellipsis <?= $textColor ?>" title="<?= $proxy["url"] ?>">
                            <a href="<?= $proxy["url"] ?>" class="proxy-link" data-proxy-id="<?= $proxy["id"] ?>"><?= $proxy["url"] ?></a>
                        </div>
                        <?php if ($proxy["official"]): ?>
                            <div class="position-absolute end-100 top-0 text-pink"  data-bs-toggle="tooltip" title='This is a confirmed official proxy'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                </svg>
                            </div>
                        <?php elseif (str_contains($proxy["url"], "knaben")): ?>
                            <div class="position-absolute end-100 top-0 text-pink"  data-bs-toggle="tooltip" title="Knaben's proxy">
                                <!-- If you are wondering, I did indeed edit this SVG manually -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="300 210 200 430">
                                    <g id="#00002aff">
                                        <path fill="#00002a" opacity="1.00" d=" M 282.86 294.84 C 331.85 248.96 409.22 237.80 469.25 267.80 C 526.20 294.69 564.83 355.91 563.90 418.98 C 527.25 418.97 490.73 431.31 461.71 453.72 C 420.72 484.78 395.73 535.48 395.97 586.90 C 328.47 588.02 263.46 543.46 239.62 480.39 C 213.91 417.40 231.95 339.96 282.86 294.84 Z"></path>
                                    </g>
                                    <g id="#00ff72ff">
                                        <path fill="#00ff72" opacity="1.00" d=" M 461.71 453.72 C 490.73 431.31 527.25 418.97 563.90 418.98 C 564.73 462.13 546.83 505.00 516.90 535.92 C 485.71 568.25 441.02 587.76 395.97 586.90 C 395.73 535.48 420.72 484.78 461.71 453.72 Z"></path>
                                    </g>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                </th>
                <th class="text-center text-white text-opacity-50 w-min"><?= $proxy["clicks_last_24h"] ?></th>
                <th class="text-center text-white text-opacity-50 w-min"><?= $proxy["clicks"] ?></th>
                <th style="color: <?= colorsBetweenOffset("#FFFFFF", "#FF0000", $web::MIN_TIMEOUT, $web::MAX_TIMEOUT, $proxy["ping"]) ?>" class="text-center w-min"><?= round($proxy["ping"]*1000) ?>ms</th>
            </tr>
        <?php
            $showLine = true;
            endforeach;
            ?>
        </tbody>
    </table>
    <?php if (!$showLine):?>
        <i class="p-2 text-white d-block text-center text-opacity-50">No proxies are working?</i>
    <?php endif; ?>
</div>
<?php endforeach; ?>