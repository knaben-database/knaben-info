<?php

/**
 * This file serves as a template for the logo
 * You can define $ANIMATED_LOGO = true; for an animated logo
 */

$animate = false;
if (isset($ANIMATED_LOGO)) {
    $ANIMATED_LOGO = false;
    $animate = true;
}

?>
<!-- My beautiful CSS drawing -->
<div class="cssLogo <?= $animate ? "animated" : "" ?>">
    <div class="housing">
        <div class="grey1" style="width:100%;height: 27%"></div>
        <div class="grey1" style="width:14.6%;top:27%;height: 31.2%"></div>
        <div class="grey1" style="left: 33%; width:9%;top:27%;height: 31.2%"></div>
        <div class="grey1" style="left: 59%; width:9%;top:27%;height: 31.2%"></div>
        <div class="grey1" style="left: 86%; width:14%;top:27%;height: 31.2%"></div>
        <div class="grey1" style="width:100%;height: 42%;top:58.1%"></div>
    </div>
    <div class="deck grey2"></div>
    <div class="hull">
        <div class="hull1">
            <div class="tail grey1"></div>
            <div class="grey1" style="left:6%;height: 100%;width: 86%"></div>
            <div class="bow grey1"></div>
        </div>
        <div class="hull2">
            <div class="tail grey2"></div>
            <div class="grey2" style="left: 10%;height: 100%;width: 73%;"></div>
            <div class="bow grey2"></div>
        </div>
    </div>
    <div class="foredeck">
        <div class="layer1">
            <div class="grey2" style="left: 0%;width: 32%;height: 100%;"></div>
            <div class="grey1" style="left: 34%; width: 32%;height: 100%;"></div>
            <div class="grey2" style="left: 68%; width: 32%;height: 100%;"></div>
        </div>
        <div class="layer2">
            <div class="grey1" style="width: 26%;height: 100%;"></div>
            <div class="grey2" style="left: 27.5%;width: 45.5%;height: 100%;"></div>
            <div class="grey1" style="left: 74.4%;width: 25.5%;height: 100%;"></div>
        </div>
        <div class="layer3">
            <div class="grey2" style="width: 26%;height: 100%;"></div>
            <div class="grey1" style="left: 27.5%;width: 45.5%;height: 100%;"></div>
            <div class="grey2" style="left: 74.4%;width: 25.5%;height: 100%;"></div>
        </div>
    </div>
</div>
