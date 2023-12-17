class Api {

    /**
     * Thanks Bing AI
     * @param headers
     */
    private static convertHeaders(headers : string) {
        const arr = headers.trim().split(/[\r\n]+/);
        const headerMap = new Headers();
        arr.forEach(function (line) {
            const parts = line.split(': ');
            const header = parts.shift();
            const value = parts.join(': ');
            if (header)
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
    public static async json(url : string, data : any = {}, options : RequestInit = {}) : Promise<ApiResponse> {
        let defaultOptions : DefaultAPIOptions = {
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
        let apiOptions : RequestInit = {...defaultOptions, ...options};

        // Handling get request
        if (apiOptions["method"] == "get" || apiOptions["method"] == "head"){
            if (apiOptions.headers && "Content-Type" in apiOptions["headers"])
                delete apiOptions["headers"]["Content-Type"];

            let requestQuery = [];
            if (data !== null) {
                for (let i = 0; i < Object.keys(data).length; i++) {
                    let key = Object.keys(data)[i];
                    const value = data[key];
                    requestQuery.push(key + "=" + value);
                }
            }
            if (requestQuery.length > 0){
                if (url.indexOf("?") === -1)
                    url += "?" + requestQuery.join("&");
                else
                    url += "&" + requestQuery.join("&");
            }
        }else{
            apiOptions["body"] = JSON.stringify(data);
        }


        let response : ApiResponse = <ApiResponse>{};

        return fetch(url, apiOptions).then(x => {
            response.headers = x.headers;
            response.ok = x.ok;
            response.status = x.status;
            response.statusText = x.statusText;
            if (response.status === 204 || apiOptions["method"] == "head") return; // Response 204 is an exception, with no body at all
            return x.json();
        }).then((data) => {
            if (response.status === 204 || apiOptions["method"] == "head") return Promise.resolve(response);
            response = {...response, ...Api.createResponse(data)};
            if (response.error) return Promise.reject(response);

            return Promise.resolve(response);
        });
    }

    /**
     * For files and such, we didn't bother to create a fancy function for it.
     * Pass the XMLHttpRequest response to this function
     * @param xhr
     * @return Promise<Response> The formatted Response we
     */
    public static formatXMLHttpRequest(xhr : XMLHttpRequest) : Promise<ApiResponse>{
        if (!isJson(xhr.responseText)){
            const maxLength = 50;
            let error_msg = xhr.statusText.length > maxLength ?
                xhr.statusText.substring(0, maxLength - 3) + "..." :
                xhr.statusText;
            error_msg = `Failed to parse JSON: ${xhr.status} - ${error_msg}`;
            let response : ApiResponse = {
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
            return Promise.reject(response);
        }

        let r = JSON.parse(xhr.responseText);
        let meta = r["meta"] ?? null;

        let server_time = meta["server_time"] ?? 0;
        if (1000000000000 > server_time)
            server_time *= 1000;

        let response : ApiResponse = {
            headers: this.convertHeaders(xhr.getAllResponseHeaders()),
            ok: !meta["error"] || 300 > xhr.status,
            status: xhr.status || meta["status_code"],
            statusText: xhr.statusText,
            error: xhr.status >= 400,
            error_msg: meta["error_msg"]["title"],
            error_desc: meta["error_msg"]["desc"],
            type: meta["type"],
            server_time: new Date(server_time),
            source: r["source"] ?? null
        };

        if (response.error) return Promise.reject(response);

        return Promise.resolve(response);
    }

    private static createResponse(data: any) : ApiResponse {
        /**//**
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
         *//**/

        // We want the server time in epoch ms
        let server_time = data["meta"]["server_time"];
        if (1000000000000 > server_time)
            server_time *= 1000;

        let response : ApiResponse = <ApiResponse>{};
        response.error = data["meta"]["error"];
        response.error_msg = data["meta"]["error_msg"]["title"];
        response.error_desc = data["meta"]["error_msg"]["desc"];
        response.type = data["meta"]["type"];
        response.server_time = new Date(server_time);
        response.source = data["source"] ?? null;
        response.results = data["results"] ?? null;
        response.status = data["meta"]["status_code"];

        response.headers = null;
        response.ok = 300 > response.status && response.status >= 200;
        response.statusText = "";
        if (data["results"]){
            response.results = data["results"].map((r : any) => Api.createResponse(r));
        }

        return response;
    }

    public static async loadHTML(url : string, data = {}, options : RequestInit = {}) : Promise<HTMLElement|boolean> {
        let defaultOptions : RequestInit = {
            method: "get",
            headers: {
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9'
            }
        };
        let apiOptions : RequestInit = {...defaultOptions, ...options};

        const response = await fetch(url, apiOptions);
        if (response.status >= 400) return Promise.reject(`Status code ${response.status}, loadHTML failed`);
        if (response.status === 204 || apiOptions["method"] == "head") return Promise.resolve(true); // Response 204 is an exception, with no body at all
        const html = await response.text();
        const div = document.createElement("DIV");
        div.innerHTML = html;
        return Promise.resolve(div);
    }

    private static cachedComponents : Record<string, Element> = {};
    public static loadComponent(component : string, wrapper? : Element) : Element|null {
        if (this.cachedComponents[component]) return this.cachedComponents[component].cloneNode(true) as Element;
        let elm : Element|null;
        if (wrapper) elm = wrapper.querySelector(`.${component}.component`);
        else elm = document.querySelector(`.${component}.component`);

        elm?.classList.remove("component");

        if (!elm) return null;

        this.cachedComponents[component] = elm.cloneNode(true) as Element;
        return elm;
    }
}