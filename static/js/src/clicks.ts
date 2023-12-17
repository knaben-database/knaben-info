class Clicks {

    private static CTRL = false;
    private static SHIFT = false;

    public static init(){
        const proxyLinkElements = document.querySelectorAll(".proxy-link");
        for (let i = 0; i < proxyLinkElements.length; i++) {
            const proxyLinkElement = proxyLinkElements[i] as HTMLLinkElement;
            const data_proxy_id = proxyLinkElement.getAttribute("data-proxy-id");
            if (data_proxy_id === null) continue;

            const proxy_id = parseInt(data_proxy_id);
            const link = proxyLinkElement.getAttribute("href");
            if (link === null) continue;

            proxyLinkElement.addEventListener("click", (e) => {
                let redirect = !this.SHIFT && !this.CTRL;
                if (redirect) e.preventDefault();
                this.click(proxy_id, link, redirect);
            });
            proxyLinkElement.addEventListener("auxclick", (e) => {
                this.click(proxy_id, link);
            });
        }

        // Keep track of CTRL state
        document.addEventListener("keydown", (e) => {
            if (e.shiftKey) this.SHIFT = true;
            if (e.ctrlKey) this.CTRL = true;
        })
        document.addEventListener("keyup", (e) => {
            if (e.shiftKey) this.SHIFT = false;
            if (e.ctrlKey) this.CTRL = false;
        })
    }

    private static click(id : number, link : string, redirect = false){
        Api.json(`/api/clicks/${id}`, null, {method: "head"}).then(data => {
            if (redirect) window.location.href = link;
        }).catch(e => {
            console.warn(e);
            if (redirect) window.location.href = link;
        })
    }

}