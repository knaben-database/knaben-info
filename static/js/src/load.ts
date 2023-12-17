/**
 * Typescript part to init things
 */
// This will trigger first
document.addEventListener('DOMContentLoaded', function () {
    console.log("meow meow");
    // Init click events
    Clicks.init();

    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        // @ts-ignore
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

// Wait for everything to load, then set event for
window.addEventListener('load', function () {
    // Init bloat
    BloatButCool.init();
});