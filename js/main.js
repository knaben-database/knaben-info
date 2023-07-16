var BloatButCool = /** @class */ (function () {
    function BloatButCool() {
    }
    BloatButCool.init = function () {
        var logos = document.querySelectorAll(".css-logo");
        var _loop_1 = function (i) {
            var elm = logos[i];
            var logo = {
                planetPopInterval: 3,
                elm: elm
            };
            elm.addEventListener("mouseenter", function () { return logo.planetPopInterval = 0.25; });
            elm.addEventListener("mouseleave", function () { return logo.planetPopInterval = 3; });
            this_1.logoEffect(logo);
            this_1.logos.push(logo);
        };
        var this_1 = this;
        for (var i = 0; i < logos.length; i++) {
            _loop_1(i);
        }
    };
    BloatButCool.logoEffect = function (logo) {
        var _this = this;
        var randomTimer = logo.planetPopInterval;
        var counter = 10;
        var myFunction = function () {
            _this.blinkRandomPlanet(logo.elm);
            counter = logo.planetPopInterval * 1000;
            setTimeout(myFunction, counter);
        };
        setTimeout(myFunction, counter);
    };
    BloatButCool.blinkRandomPlanet = function (elm) {
        var duration = 2;
        var planetCores = elm.querySelectorAll(".planet-core");
        var choice = Math.floor(Math.random() * planetCores.length);
        var highlightColor = Style().getPropertyValue("--bs-highlight");
        var planetCore = planetCores[choice];
        planetCore.style.transitionDuration = "";
        planetCore.style.backgroundColor = highlightColor;
        setInterval(function () {
            planetCore.style.transitionDuration = "".concat(duration, "s");
            setInterval(function () {
                planetCore.style.backgroundColor = "";
            }, duration * 100);
        }, 100);
    };
    BloatButCool.logos = [];
    return BloatButCool;
}());
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
