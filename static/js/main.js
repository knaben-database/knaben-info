"use strict";
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
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (g && (g = 0, op[0] && (_ = 0)), _) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
var Api = /** @class */ (function () {
    function Api() {
    }
    /**
     * Thanks Bing AI
     * @param headers
     */
    Api.convertHeaders = function (headers) {
        var arr = headers.trim().split(/[\r\n]+/);
        var headerMap = new Headers();
        arr.forEach(function (line) {
            var parts = line.split(': ');
            var header = parts.shift();
            var value = parts.join(': ');
            if (header)
                headerMap.append(header, value);
        });
        return headerMap;
    };
    /**
     *
     * @param url The url to send the data to. Starting with slash (/) means Protocol://Host/${Your shit}
     * @param data The data to send, can be anything. But preferably json data. If data is not provided, it will default to get request instead
     * @param options Options to merge with fetch RequestInit
     */
    Api.json = function (url, data, options) {
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        return __awaiter(this, void 0, void 0, function () {
            var defaultOptions, apiOptions, requestQuery, i, key, value, response;
            return __generator(this, function (_a) {
                defaultOptions = {
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
                apiOptions = __assign(__assign({}, defaultOptions), options);
                // Handling get request
                if (apiOptions["method"] == "get" || apiOptions["method"] == "head") {
                    if (apiOptions.headers && "Content-Type" in apiOptions["headers"])
                        delete apiOptions["headers"]["Content-Type"];
                    requestQuery = [];
                    if (data !== null) {
                        for (i = 0; i < Object.keys(data).length; i++) {
                            key = Object.keys(data)[i];
                            value = data[key];
                            requestQuery.push(key + "=" + value);
                        }
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
                response = {};
                return [2 /*return*/, fetch(url, apiOptions).then(function (x) {
                        response.headers = x.headers;
                        response.ok = x.ok;
                        response.status = x.status;
                        response.statusText = x.statusText;
                        if (response.status === 204 || apiOptions["method"] == "head")
                            return; // Response 204 is an exception, with no body at all
                        return x.json();
                    }).then(function (data) {
                        if (response.status === 204 || apiOptions["method"] == "head")
                            return Promise.resolve(response);
                        response = __assign(__assign({}, response), Api.createResponse(data));
                        if (response.error)
                            return Promise.reject(response);
                        return Promise.resolve(response);
                    })];
            });
        });
    };
    /**
     * For files and such, we didn't bother to create a fancy function for it.
     * Pass the XMLHttpRequest response to this function
     * @param xhr
     * @return Promise<Response> The formatted Response we
     */
    Api.formatXMLHttpRequest = function (xhr) {
        var _a, _b, _c;
        if (!isJson(xhr.responseText)) {
            var maxLength = 50;
            var error_msg = xhr.statusText.length > maxLength ?
                xhr.statusText.substring(0, maxLength - 3) + "..." :
                xhr.statusText;
            error_msg = "Failed to parse JSON: ".concat(xhr.status, " - ").concat(error_msg);
            var response_1 = {
                headers: this.convertHeaders(xhr.getAllResponseHeaders()),
                ok: 300 > xhr.status,
                status: xhr.status,
                statusText: xhr.statusText,
                error: xhr.status >= 400,
                error_msg: error_msg,
                error_desc: null,
                type: "single",
                server_time: new Date(0),
                source: null
            };
            return Promise.reject(response_1);
        }
        var r = JSON.parse(xhr.responseText);
        var meta = (_a = r["meta"]) !== null && _a !== void 0 ? _a : null;
        var server_time = (_b = meta["server_time"]) !== null && _b !== void 0 ? _b : 0;
        if (1000000000000 > server_time)
            server_time *= 1000;
        var response = {
            headers: this.convertHeaders(xhr.getAllResponseHeaders()),
            ok: !meta["error"] || 300 > xhr.status,
            status: xhr.status || meta["status_code"],
            statusText: xhr.statusText,
            error: xhr.status >= 400,
            error_msg: meta["error_msg"]["title"],
            error_desc: meta["error_msg"]["desc"],
            type: meta["type"],
            server_time: new Date(server_time),
            source: (_c = r["source"]) !== null && _c !== void 0 ? _c : null
        };
        if (response.error)
            return Promise.reject(response);
        return Promise.resolve(response);
    };
    Api.createResponse = function (data) {
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
        var _a, _b;
        // We want the server time in epoch ms
        var server_time = data["meta"]["server_time"];
        if (1000000000000 > server_time)
            server_time *= 1000;
        var response = {};
        response.error = data["meta"]["error"];
        response.error_msg = data["meta"]["error_msg"]["title"];
        response.error_desc = data["meta"]["error_msg"]["desc"];
        response.type = data["meta"]["type"];
        response.server_time = new Date(server_time);
        response.source = (_a = data["source"]) !== null && _a !== void 0 ? _a : null;
        response.results = (_b = data["results"]) !== null && _b !== void 0 ? _b : null;
        response.status = data["meta"]["status_code"];
        response.headers = null;
        response.ok = 300 > response.status && response.status >= 200;
        response.statusText = "";
        if (data["results"]) {
            response.results = data["results"].map(function (r) { return Api.createResponse(r); });
        }
        return response;
    };
    Api.loadHTML = function (url, data, options) {
        if (data === void 0) { data = {}; }
        if (options === void 0) { options = {}; }
        return __awaiter(this, void 0, void 0, function () {
            var defaultOptions, apiOptions, response, html, div;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        defaultOptions = {
                            method: "get",
                            headers: {
                                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9'
                            }
                        };
                        apiOptions = __assign(__assign({}, defaultOptions), options);
                        return [4 /*yield*/, fetch(url, apiOptions)];
                    case 1:
                        response = _a.sent();
                        if (response.status >= 400)
                            return [2 /*return*/, Promise.reject("Status code ".concat(response.status, ", loadHTML failed"))];
                        if (response.status === 204 || apiOptions["method"] == "head")
                            return [2 /*return*/, Promise.resolve(true)]; // Response 204 is an exception, with no body at all
                        return [4 /*yield*/, response.text()];
                    case 2:
                        html = _a.sent();
                        div = document.createElement("DIV");
                        div.innerHTML = html;
                        return [2 /*return*/, Promise.resolve(div)];
                }
            });
        });
    };
    Api.loadComponent = function (component, wrapper) {
        if (this.cachedComponents[component])
            return this.cachedComponents[component].cloneNode(true);
        var elm;
        if (wrapper)
            elm = wrapper.querySelector(".".concat(component, ".component"));
        else
            elm = document.querySelector(".".concat(component, ".component"));
        elm === null || elm === void 0 ? void 0 : elm.classList.remove("component");
        if (!elm)
            return null;
        this.cachedComponents[component] = elm.cloneNode(true);
        return elm;
    };
    Api.cachedComponents = {};
    return Api;
}());
var Clicks = /** @class */ (function () {
    function Clicks() {
    }
    Clicks.init = function () {
        var _this = this;
        var proxyLinkElements = document.querySelectorAll(".proxy-link");
        var _loop_1 = function (i) {
            var proxyLinkElement = proxyLinkElements[i];
            var data_proxy_id = proxyLinkElement.getAttribute("data-proxy-id");
            if (data_proxy_id === null)
                return "continue";
            var proxy_id = parseInt(data_proxy_id);
            var link = proxyLinkElement.getAttribute("href");
            if (link === null)
                return "continue";
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
        Api.json("/api/clicks/".concat(id), null, { method: "head" }).then(function (data) {
            if (redirect)
                window.location.href = link;
        }).catch(function (e) {
            console.warn(e);
            if (redirect)
                window.location.href = link;
        });
    };
    Clicks.CTRL = false;
    Clicks.SHIFT = false;
    return Clicks;
}());
function isJson(str) {
    try {
        JSON.parse(str);
    }
    catch (e) {
        return false;
    }
    return true;
}
/**
 * Typescript part to init things
 */
// This will trigger first
document.addEventListener('DOMContentLoaded', function () {
    console.log("meow meow");
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
        // Init kd logo bloat
        KdLogoBloat.init();
    };
    return BloatButCool;
}());
var KdLogoBloat = (function () {
    function initialize() {
        var elms = document.querySelectorAll(".cssLogo.animated");
        var _loop_2 = function (i) {
            elms[i].style.transitionDuration = "0s";
            spawnPos(elms[i]);
            // Don't want to auto search right away
            setTimeout(function () {
                elms[i].style.transitionDuration = "";
                animate(elms[i]);
            }, 50);
        };
        for (var i = 0; i < elms.length; i++) {
            _loop_2(i);
        }
    }
    function spawnPos(elm) {
        var _a;
        var layers = (_a = elm.querySelector(".foredeck")) === null || _a === void 0 ? void 0 : _a.children;
        if (!layers)
            return;
        for (var i = 0; i < layers.length; i++) {
            var containers = layers[i].children;
            for (var j = 0; j < containers.length; j++) {
                var container = containers[j];
                container.style.transform = "translate(0, -600px)";
                container.style.transitionDuration = "0s";
            }
        }
        elm.style.opacity = "1";
    }
    function animate(elm) {
        elm.style.opacity = "1";
        elm.style.transform = "translate(0px)";
        setTimeout(function () {
            var _a;
            var layers = (_a = elm.querySelector(".foredeck")) === null || _a === void 0 ? void 0 : _a.children;
            if (!layers)
                return;
            for (var i = 0; i < layers.length; i++) {
                var containers = layers[i].children;
                var _loop_3 = function (j) {
                    var container = containers[j];
                    container.style.transition = "all cubic-bezier(0.5, 0.25, 0.27, 0.1) 1s";
                    setTimeout(function () {
                        container.style.transform = "translate(0)";
                    }, 50 * (j - containers.length) * (i - layers.length));
                };
                for (var j = 0; j < containers.length; j++) {
                    _loop_3(j);
                }
            }
        }, 2000);
    }
    // Here you can set what to be public
    return {
        // Name : Function
        init: initialize
    };
})();
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
        var _a;
        var color;
        if (this.elm.hasAttribute("data-logo-color-theme") && ((_a = this.elm.getAttribute("data-logo-color-theme")) === null || _a === void 0 ? void 0 : _a.trim()) !== "")
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
            if (highlightColor)
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
        for (var i = 0; i < rules.length; i++) {
            var rule = rules[i];
            // @ts-ignore I don't dare to touch this actually
            if (rule["selectorText"] === selector)
                return i;
        }
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
        var cssRule = stylesheet.cssRules[key2];
        // @ts-ignore Don't dare to touch this either
        cssRule["style"][rule] = "";
    }
    /**
     * Function for getting a value of a specific selector within the CSS Stylesheet
     * @param selector{string} The selector. Some examples might be: #mainDiv, .text-muted
     * @param rule{string} The rule to select, for example "color", "background-color" etc...
     */
    function get(selector, rule) {
        selector = selector.replace(/ +(?= )/g, '').trim();
        var key = findStyle();
        if (key === -1)
            return ""; // Could not find style
        var stylesheet = document.styleSheets[key];
        if (!("cssRules" in stylesheet))
            return "";
        var key2 = findSelector(stylesheet["cssRules"], selector);
        if (key2 === -1)
            return ""; // Could not find selector
        // @ts-ignore Just, trust me bro
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
                // @ts-ignore shhhhhhhh....
                var value = rule[i][name_1];
                if (stylesheet_1.cssRules.length === 0)
                    stylesheet_1.insertRule("".concat(selector, " { ").concat(name_1, ": ").concat(value, " }"), 0);
                else // @ts-ignore shhhhhhhh....
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
                // @ts-ignore Just shhhhhhhh....
                var value = rule[i][name_2];
                if (stylesheet.cssRules.length === 0)
                    stylesheet.insertRule("".concat(selector, " { ").concat(name_2, ": ").concat(value, " }"), 0);
                else // @ts-ignore Just shhhhhhhh....
                    stylesheet.cssRules[0]["style"][name_2] = value;
            }
        }
        else { // Modify the existing rule
            for (var i = 0; i < rule.length; i++) {
                if (Object.keys(rule[i]).length > 1)
                    throw new Error("Can only have 1 key for each array");
                var name_3 = Object.keys(rule[i])[0];
                // @ts-ignore Just shhhhhhhh....
                var value = rule[i][name_3];
                // @ts-ignore Just shhhhhhhh....
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
//# sourceMappingURL=main.js.map