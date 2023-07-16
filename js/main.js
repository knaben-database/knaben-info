/**
 * Typescript part to init things
 */
// This will trigger first
document.addEventListener('DOMContentLoaded', function () {
});
// Wait for everything to load, then set event for
window.addEventListener('load', function () {
    // Init bloat
    BloatButCool.init();
});
var BloatButCool = /** @class */ (function () {
    function BloatButCool() {
    }
    BloatButCool.init = function () {
        // Init logo bloat
        var logos = document.querySelectorAll(".css-logo");
        for (var i = 0; i < logos.length; i++) {
            var logo = logos[i];
            new LogoBloat(logo);
        }
    };
    return BloatButCool;
}());
var LogoBloat = /** @class */ (function () {
    function LogoBloat(elm) {
        var _this = this;
        /**
         * Times the planets blink per second
         */
        this.blinkInterval = {
            idle: 0.25,
            active: 3
        };
        /**
         * If we are hovering it or not
         */
        this.hovering = false;
        this.elm = elm;
        this.blinkLoop();
        this.elm.addEventListener("mouseenter", function () { return _this.hovering = true; });
        this.elm.addEventListener("mouseleave", function () { return _this.hovering = false; });
    }
    LogoBloat.prototype.blinkLoop = function () {
        var _this = this;
        var planetCores = this.elm.querySelectorAll(".planet-core");
        var highlightColor = Style().getPropertyValue("--bs-highlight");
        var blinkingPlanetCores = new Set();
        var counter = 0;
        setInterval(function () {
            if (!_this.hovering) {
                counter += _this.blinkInterval.idle;
                if (_this.blinkInterval.active > counter)
                    return;
                else
                    counter = 0;
            }
            // Check if all the planet cores are already blinking
            if (blinkingPlanetCores.size === planetCores.length) {
                return;
            }
            var randomPlanetCore;
            do {
                var randomIndex = Math.floor(Math.random() * planetCores.length);
                randomPlanetCore = planetCores[randomIndex];
            } while (blinkingPlanetCores.has(randomPlanetCore));
            // Add the selected planet core to the set of blinking planet cores
            blinkingPlanetCores.add(randomPlanetCore);
            // Change the background color of the selected planet core
            randomPlanetCore.style.backgroundColor = highlightColor; // Change to the desired color
            randomPlanetCore.style.transform = "scale(1.1)";
            randomPlanetCore.style.transitionDuration = "";
            // Set a timeout to change the background color back after some time
            setTimeout(function () {
                blinkingPlanetCores.delete(randomPlanetCore);
                randomPlanetCore.style.transitionDuration = "".concat(_this.blinkInterval.active, "s");
                randomPlanetCore.style.backgroundColor = ""; // Change back to the original color
                randomPlanetCore.style.transform = "scale(1)";
            }, _this.blinkInterval.idle * 1000); // Change the duration of the blink here
        }, 1 / this.blinkInterval.active * 1000);
    };
    return LogoBloat;
}());
/**
 * Function to manage stylesheets
 * @param title{string} Title of the stylesheet
 * @constructor wth is this?
 */
var Style = function (title) {
    /**
     * This is used to
     */
    var style;
    /** Finding stylesheet by title name
     * @returns {number} Returns -1 if not found, or else it returns the key of the stylesheet
     */
    function findStyle() {
        if (!title)
            throw new Error("Title needs to be set when calling Style(title).findStyle()");
        for (var i = 0; i < document.styleSheets.length; i++) {
            // user-defined | style
            // Needs to be set like so: <link href="/css/style.css" title="style" rel="stylesheet">
            if (document.styleSheets[i].title === title) {
                return i;
            }
        }
        return -1;
    }
    /** Finding selector by selectorText
     * @param rules{CSSRuleList[]} pass in the rules
     * @param selector{string} the name of the selector
     * @returns {number} Returns -1 if not found, or else it returns the key of the rule
     */
    function findSelector(rules, selector) {
        for (var i = 0; i < rules.length; i++)
            if (rules[i]["selectorText"] === selector)
                return i;
        return -1;
    }
    /**
     * Function to remove a style value from a stylesheet
     * @param selector{string} Name of the style
     * @param rule{string} value within the style
     */
    function remove(selector, rule) {
        selector = selector.replace(/ +(?= )/g, '').trim();
        var key = findStyle();
        if (key === -1)
            return; // Could not find style
        var stylesheet = document.styleSheets[key];
        var key2 = findSelector(stylesheet.cssRules, selector);
        if (key2 === -1)
            return; // Could not find selector
        stylesheet.cssRules[key2]["style"][rule] = "";
    }
    /**
     * Function for getting a value of a spesific selector within the CSS Stylesheet
     * @param selector{string} The selector. Some examples might be: #mainDiv, .text-muted
     * @param rule{string} The rule to select, for example "color", "background-color" etc...
     */
    function get(selector, rule) {
        selector = selector.replace(/ +(?= )/g, '').trim();
        var key = findStyle();
        if (key === -1)
            return; // Could not find style
        var stylesheet = document.styleSheets[key];
        if (!("cssRules" in stylesheet))
            return;
        var key2 = findSelector(stylesheet["cssRules"], selector);
        if (key2 === -1)
            return; // Could not find selector
        if (stylesheet.cssRules[key2]["style"][rule])
            return stylesheet.cssRules[key2]["style"][rule];
    }
    /**
     * Simplified version of getPropertyValue to prevent computing style every time
     * @param property
     */
    function getPropertyValue(property) {
        if (!style)
            style = getComputedStyle(document.body);
        return style.getPropertyValue(property);
    }
    /**
     * Function to set a style value into an existing, or new stylesheet
     * @param selector{string} Name of the style
     * @param rule{Array<CSSStyleDeclaration>} values for the style
     * IMPORTANT! RULE MUST BE A CROSS CSS AND JS VALUE
     */
    function set(selector, rule) {
        if (!title)
            throw new Error("Title needs to be set when calling Style(title).set(..., [{...}, {...}])");
        selector = selector.replace(/ +(?= )/g, '').trim();
        var key = findStyle();
        if (key === -1) { // Create the stylesheet from scratch
            var stylesheet_1 = new CSSStyleSheet();
            // @ts-ignore
            stylesheet_1["title"] = title;
            for (var i = 0; i < rule.length; i++) {
                if (Object.keys(rule[i]).length > 1)
                    throw new Error("Can only have 1 key for each array");
                var name_1 = Object.keys(rule[i])[0];
                var value = rule[i][name_1];
                if (stylesheet_1.cssRules.length === 0)
                    stylesheet_1.insertRule("".concat(selector, " { ").concat(name_1, ": ").concat(value, " }"), 0);
                else
                    stylesheet_1.cssRules[0]["style"][name_1] = value;
            }
            document.adoptedStyleSheets = [stylesheet_1];
            return;
        }
        var stylesheet = document.styleSheets[key];
        var key2 = findSelector(stylesheet.cssRules, selector);
        if (key2 === -1) { // Create a new rule
            for (var i = 0; i < rule.length; i++) {
                if (Object.keys(rule[i]).length > 1)
                    throw new Error("Can only have 1 key for each array");
                var name_2 = Object.keys(rule[i])[0];
                var value = rule[i][name_2];
                if (stylesheet.cssRules.length === 0)
                    stylesheet.insertRule("".concat(selector, " { ").concat(name_2, ": ").concat(value, " }"), 0);
                else
                    stylesheet.cssRules[0]["style"][name_2] = value;
            }
        }
        else { // Modify the existing rule
            for (var i = 0; i < rule.length; i++) {
                if (Object.keys(rule[i]).length > 1)
                    throw new Error("Can only have 1 key for each array");
                var name_3 = Object.keys(rule[i])[0];
                var value = rule[i][name_3];
                stylesheet.cssRules[key2]["style"][name_3] = value;
            }
        }
    }
    return {
        set: set,
        remove: remove,
        get: get,
        getPropertyValue: getPropertyValue
    };
};
