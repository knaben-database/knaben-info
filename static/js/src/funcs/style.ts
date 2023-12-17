/**
 * Function to manage stylesheets
 * @param title{string} Title of the stylesheet
 * @constructor wth is this?
 */
const Style = (title? : "user-defined" | "style") => {

    /**
     * This is used to
     */
    let style : CSSStyleDeclaration;

    /** Finding stylesheet by title name
     * @returns {number} Returns -1 if not found, or else it returns the key of the stylesheet
     */
    function findStyle() : number{
        if (!title) throw new Error("Title needs to be set when calling Style(title).findStyle()")
        for ( let i = 0; i < document.styleSheets.length; i++ ) {
            // user-defined | style
            // Needs to be set like so: <link href="/css/style.css" title="style" rel="stylesheet">
            if (document.styleSheets[i].title === title){
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
    function findSelector(rules : CSSRuleList, selector : string) : number{
        for ( let i = 0; i < rules.length; i++ ) {
            const rule = rules[i];
            // @ts-ignore I don't dare to touch this actually
            if (rule["selectorText"] === selector) return i;
        }
        return -1;
    }

    /**
     * Function to remove a style value from a stylesheet
     * @param selector{string} Name of the style
     * @param rule{string} value within the style
     */
    function remove(selector : string, rule : string){
        selector = selector.replace(/ +(?= )/g,'').trim();
        let key = findStyle();
        if (key === -1) return; // Could not find style

        let stylesheet = document.styleSheets[key];

        let key2 = findSelector(stylesheet.cssRules, selector);
        if (key2 === -1) return; // Could not find selector

        const cssRule = stylesheet.cssRules[key2];
        // @ts-ignore Don't dare to touch this either
        cssRule["style"][rule] = "";
    }


    /**
     * Function for getting a value of a specific selector within the CSS Stylesheet
     * @param selector{string} The selector. Some examples might be: #mainDiv, .text-muted
     * @param rule{string} The rule to select, for example "color", "background-color" etc...
     */
    function get(selector : string, rule : string) : string|undefined{
        selector = selector.replace(/ +(?= )/g,'').trim();
        let key = findStyle();
        if (key === -1) return ""; // Could not find style

        let stylesheet = document.styleSheets[key];

        if (!("cssRules" in stylesheet)) return "";
        let key2 = findSelector(stylesheet["cssRules"], selector);
        if (key2 === -1) return ""; // Could not find selector

        // @ts-ignore Just, trust me bro
        if (stylesheet.cssRules[key2]["style"][rule]) return stylesheet.cssRules[key2]["style"][rule];
    }

    /**
     * Simplified version of getPropertyValue to prevent computing style every time
     * @param property
     */
    function getPropertyValue(property : string){
        if (!style) style = getComputedStyle(document.body);
        return style.getPropertyValue(property);
    }

    /**
     * Function to set a style value into an existing, or new stylesheet
     * @param selector{string} Name of the style
     * @param rule{Array<CSSStyleDeclaration>} values for the style
     * IMPORTANT! RULE MUST BE A CROSS CSS AND JS VALUE
     */
    function set(selector : string, rule : Array<object>){
        if (!title) throw new Error("Title needs to be set when calling Style(title).set(..., [{...}, {...}])");
        selector = selector.replace(/ +(?= )/g,'').trim();
        let key = findStyle();
        if (key === -1){ // Create the stylesheet from scratch
            let stylesheet = new CSSStyleSheet();

            // @ts-ignore
            stylesheet["title"] = title;
            for ( let i = 0; i < rule.length; i++ ) {
                if (Object.keys(rule[i]).length > 1)
                    throw new Error("Can only have 1 key for each array");
                let name = Object.keys(rule[i])[0];
                // @ts-ignore shhhhhhhh....
                let value = rule[i][name];
                if (stylesheet.cssRules.length === 0)
                    stylesheet.insertRule(`${selector} { ${name}: ${value} }`, 0);
                else // @ts-ignore shhhhhhhh....
                    stylesheet.cssRules[0]["style"][name] = value;
            }

            document.adoptedStyleSheets = [stylesheet];
            return;
        }
        let stylesheet = document.styleSheets[key];
        let key2 = findSelector(stylesheet.cssRules, selector);
        if (key2 === -1){ // Create a new rule
            for ( let i = 0; i < rule.length; i++ ) {
                if (Object.keys(rule[i]).length > 1)
                    throw new Error("Can only have 1 key for each array");
                let name = Object.keys(rule[i])[0];
                // @ts-ignore Just shhhhhhhh....
                let value = rule[i][name];
                if (stylesheet.cssRules.length === 0)
                    stylesheet.insertRule(`${selector} { ${name}: ${value} }`, 0);
                else // @ts-ignore Just shhhhhhhh....
                    stylesheet.cssRules[0]["style"][name] = value;
            }
        }else{ // Modify the existing rule
            for ( let i = 0; i < rule.length; i++ ) {
                if (Object.keys(rule[i]).length > 1)
                    throw new Error("Can only have 1 key for each array");
                let name = Object.keys(rule[i])[0];
                // @ts-ignore Just shhhhhhhh....
                let value = rule[i][name];
                // @ts-ignore Just shhhhhhhh....
                stylesheet.cssRules[key2]["style"][name] = value;
            }
        }
    }

    return {
        set,
        remove,
        get,
        getPropertyValue
    }
};