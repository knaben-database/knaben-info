let Api = (function () {

    /**
     * Thanks Bing AI
     * @param headers
     */
    function convertHeaders(headers : string) {
        const arr = headers.trim().split(/[\r\n]+/);
        const headerMap = new Headers();
        arr.forEach(function (line) {
            const parts = line.split(': ');
            const header = parts.shift();
            const value = parts.join(': ');
            headerMap.append(header, value);
        });
        return headerMap;
    }

    interface DefaultOptions {
        method: string,
        headers?: {
            'Accept': string,
            'Content-Type': string
        }
    }

    /**
     *
     * @param url The url to send the data to. Starting with slash (/) means Protocol://Host/${Your shit}
     * @param data The data to send, can be anything. But preferably json data. If data is not provided, it will default to get request instead
     * @param options Options to merge with fetch RequestInit
     */
    function sendJSON(url : string, data = {}, options : RequestInit = {}) : Promise<ApiResponse> {
        let defaultOptions : DefaultOptions = {
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
            if ("headers" in apiOptions && "Content-Type" in apiOptions["headers"])
                delete apiOptions["headers"]["Content-Type"];

            let requestQuery = [];
            for (let i = 0; i < Object.keys(data).length; i++) {
                let key = Object.keys(data)[i];
                requestQuery.push(key + "=" + data[key]);
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


        let response : ApiResponse = {
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

        return fetch(url, apiOptions).then(x => {
            response.headers = x.headers;
            response.ok = x.ok;
            response.status = x.status;
            response.statusText = x.statusText;
            if (response.status === 204 || apiOptions["method"] == "head") return; // Response 204 is an exception, with no body at all
            return x.json();
        }).then((data) => {
            if (response.status === 204 || apiOptions["method"] == "head") return Promise.resolve(response);
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

            response.error = data["meta"]["error"];
            response.error_msg = data["meta"]["error_msg"]["title"];
            response.error_desc = data["meta"]["error_msg"]["desc"];
            response.type = data["meta"]["type"];
            response.server_time = new Date(server_time);
            response.source = data["source"] ?? null;
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
    function formatXMLHttpRequest(xhr : XMLHttpRequest) : Promise<ApiResponse>{
        let r = JSON.parse(xhr.responseText);
        let meta = r["meta"];

        let server_time = meta["server_time"];
        if (1000000000000 > server_time)
            server_time *= 1000;

        let response : ApiResponse = {
            headers: convertHeaders(xhr.getAllResponseHeaders()),
            ok: !meta["error"] || 300 > xhr.status,
            status: xhr.status || meta["status_code"],
            statusText: xhr.statusText,
            error: xhr.status >= 400,
            error_msg: meta["error_msg"]["title"],
            error_desc: meta["error_msg"]["desc"],
            type: meta["type"],
            server_time: server_time,
            source: r["source"] ?? null
        };

        if (response.error) return Promise.reject(response);

        return Promise.resolve(response);
    }



    // Here you can set what to be public
    return {
        // Name   : Function
        json      : sendJSON,
        formatXMLHttpRequest
    };
})();