<div class="p-2 d-inline-flex w-100 text-pink position-sticky top-100">
    <div class="w-100">
        <a class="text-start matrix-logo text-pink w-fit text-decoration-none d-block" href="https://matrix.to/#/#general:knaben.org">matrix</a>
    </div>
    <div class="w-100">
        <div class="text-center">Knaben Team ψ <?= date("Y") ?></div>
    </div>
    <div class="w-100">
        <?php if (isset($TIMEMEASURE)): ?>
            <div class="text-end" title="Server side code execution time">Load time: <?= number_format((microtime(TRUE)-$TIMEMEASURE)*1000, 4) ?> ms</div>
        <?php endif; ?>
    </div>
</div>