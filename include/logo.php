<?php
/**
 * In this PHP file you can tune how the logo looks by setting the different variables before including the logo.php file.
 * @var $LOGO_CORE_MESSAGE string|null Show a text within the core of the logo
 * @var $LOGO_SHOW_CORE bool|null Whenever to show the core in the middle, without any hover effect
 * @var $LOGO_COLOR_THEME string|null Set a color like "red", "green" etc...
 */

?>
<div class="css-logo position-relative" data-logo-color-theme="<?= $LOGO_COLOR_THEME ?? "" ?>">
    <div class="outer-ring">
        <div class="planet">
            <div class="planet-core"></div>
        </div>
        <div class="ring"></div>
    </div>
    <div class="middle-ring">
        <div class="planet">
            <div class="planet-core"></div>
        </div>
        <div class="ring"></div>
    </div>
    <div class="inner-ring">
        <div class="planet">
            <div class="planet-core"></div>
        </div>
        <div class="ring"></div>
    </div>
    <div class="ball">
        <div class="core<?= isset($LOGO_SHOW_CORE) && $LOGO_SHOW_CORE ? " show-core" : "" ?>" style="<?= isset($LOGO_COLOR_THEME) ? "background-color:$LOGO_COLOR_THEME !important;" : "" ?>">
            <?php if (isset($LOGO_CORE_MESSAGE)): ?>
                <div class="text-darkblue fs-1 fw-bolder position-absolute c-m"><?= $LOGO_CORE_MESSAGE ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>