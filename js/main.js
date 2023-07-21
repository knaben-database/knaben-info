var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var Api = (function () {
    /**
     * Thanks Bing AI
     * @param headers
     */
    function convertHeaders(headers) {
        var arr = headers.trim().split(/[\r\n]+/);
        var headerMap = new Headers();
        arr.forEach(function (line) {
            var parts = line.split(': ');
            var header = parts.shift();
            var value = parts.join(': ');
            headerMap.append(header, value);
        });
        return headerMap;
    }
    /**
     *
     * @param url The url to send the data to. Starting with slash (/) means Protocol://Host/${Your shit}
     * @param data The data to send, can be anything. But preferably json data. If data is not provided, it will default to get request instead
     * @param options Options to merge with fetch RequestInit
     */
    function sendJSON(url, data, options) {
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        var defaultOptions = {
            method: "post",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        };
        if (data && Object.keys(data).length === 0 && Object.getPrototypeOf(data) === Object.prototype)
            defaultOptions = {
                method: "get"
            };
        var apiOptions = __assign(__assign({}, defaultOptions), options);
        // Handling get request
        if (apiOptions["method"] == "get" || apiOptions["method"] == "head") {
            if ("headers" in apiOptions && "Content-Type" in apiOptions["headers"])
                delete apiOptions["headers"]["Content-Type"];
            var requestQuery = [];
            for (var i = 0; i < Object.keys(data).length; i++) {
                var key = Object.keys(data)[i];
                requestQuery.push(key + "=" + data[key]);
            }
            if (requestQuery.length > 0) {
                if (url.indexOf("?") === -1)
                    url += "?" + requestQuery.join("&");
                else
                    url += "&" + requestQuery.join("&");
            }
        }
        else {
            apiOptions["body"] = JSON.stringify(data);
        }
        var response = {
            headers: null,
            ok: null,
            status: null,
            statusText: null,
            error: null,
            error_msg: null,
            error_desc: null,
            type: null,
            server_time: null,
            source: null
        };
        return fetch(url, apiOptions).then(function (x) {
            response.headers = x.headers;
            response.ok = x.ok;
            response.status = x.status;
            response.statusText = x.statusText;
            if (response.status === 204 || apiOptions["method"] == "head")
                return; // Response 204 is an exception, with no body at all
            return x.json();
        }).then(function (data) {
            var _a;
            if (response.status === 204 || apiOptions["method"] == "head")
                return Promise.resolve(response);
            /**/ /**
             * The raw response might look something like this:
             * ```json
             *  {
             *   "meta": {
             *     "error": false,
             *     "error_msg": {
             *       "title": null,
             *       "desc": null
             *     },
             *     "status_code": 200,
             *     "type": "single",
             *     "server_time": 1679841247
             *   },
             *   "source": {
             *     "my_data": "something"
             *   }
             * }
             * ```
             */ /**/
            // We want the server time in epoch ms
            var server_time = data["meta"]["server_time"];
            if (1000000000000 > server_time)
                server_time *= 1000;
            response.error = data["meta"]["error"];
            response.error_msg = data["meta"]["error_msg"]["title"];
            response.error_desc = data["meta"]["error_msg"]["desc"];
            response.type = data["meta"]["type"];
            response.server_time = new Date(server_time);
            response.source = (_a = data["source"]) !== null && _a !== void 0 ? _a : null;
            if (response.error)
                return Promise.reject(response);
            return Promise.resolve(response);
        });
    }
    /**
     * For files and such, we didn't bother to create a fancy function for it.
     * Pass the XMLHttpRequest response to this function
     * @param xhr
     * @return Promise<Response> The formatted Response we
     */
    function formatXMLHttpRequest(xhr) {
        var _a;
        var r = JSON.parse(xhr.responseText);
        var meta = r["meta"];
        var server_time = meta["server_time"];
        if (1000000000000 > server_time)
            server_time *= 1000;
        var response = {
            headers: convertHeaders(xhr.getAllResponseHeaders()),
            ok: !meta["error"] || 300 > xhr.status,
            status: xhr.status || meta["status_code"],
            statusText: xhr.statusText,
            error: xhr.status >= 400,
            error_msg: meta["error_msg"]["title"],
            error_desc: meta["error_msg"]["desc"],
            type: meta["type"],
            server_time: server_time,
            source: (_a = r["source"]) !== null && _a !== void 0 ? _a : null
        };
        if (response.error)
            return Promise.reject(response);
        return Promise.resolve(response);
    }
    // Here you can set what to be public
    return {
        // Name   : Function
        json: sendJSON,
        formatXMLHttpRequest: formatXMLHttpRequest
    };
})();
var Clicks = /** @class */ (function () {
    function Clicks() {
    }
    Clicks.init = function () {
        var _this = this;
        var proxyLinkElements = document.querySelectorAll(".proxy-link");
        var _loop_1 = function (i) {
            var proxyLinkElement = proxyLinkElements[i];
            var data_proxy_id = proxyLinkElement.getAttribute("data-proxy-id");
            var proxy_id = parseInt(data_proxy_id);
            var link = proxyLinkElement.getAttribute("href");
            proxyLinkElement.addEventListener("click", function (e) {
                var redirect = !_this.SHIFT && !_this.CTRL;
                if (redirect)
                    e.preventDefault();
                _this.click(proxy_id, link, redirect);
            });
            proxyLinkElement.addEventListener("auxclick", function (e) {
                _this.click(proxy_id, link);
            });
        };
        for (var i = 0; i < proxyLinkElements.length; i++) {
            _loop_1(i);
        }
        // Keep track of CTRL state
        document.addEventListener("keydown", function (e) {
            if (e.shiftKey)
                _this.SHIFT = true;
            if (e.ctrlKey)
                _this.CTRL = true;
        });
        document.addEventListener("keyup", function (e) {
            if (e.shiftKey)
                _this.SHIFT = false;
            if (e.ctrlKey)
                _this.CTRL = false;
        });
    };
    Clicks.click = function (id, link, redirect) {
        if (redirect === void 0) { redirect = false; }
        Api.json("/php/api/click.php", { id: id }, { method: "head" }).then(function (data) {
            if (redirect)
                window.location.href = link;
        });
    };
    Clicks.CTRL = false;
    Clicks.SHIFT = false;
    return Clicks;
}());
/**
 * Typescript part to init things
 */
// This will trigger first
document.addEventListener('DOMContentLoaded', function () {
    // Init click events
    Clicks.init();
    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        // @ts-ignore
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
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
    LogoBloat.prototype.getColor = function () {
        var color = "";
        if (this.elm.hasAttribute("data-logo-color-theme"))
            color = this.elm.getAttribute("data-logo-color-theme");
        else
            color = Style().getPropertyValue("--bs-highlight");
        return color;
    };
    LogoBloat.prototype.blinkLoop = function () {
        var _this = this;
        var planetCores = this.elm.querySelectorAll(".planet-core");
        var highlightColor = this.getColor();
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
