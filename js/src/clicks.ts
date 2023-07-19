class Clicks {

    public static init(){
        const proxyLinkElements = document.querySelectorAll(".proxy-link");
        for (let i = 0; i < proxyLinkElements.length; i++) {
            const proxyLinkElement = proxyLinkElements[i] as HTMLLinkElement;
            const data_proxy_id = proxyLinkElement.getAttribute("data-proxy-id");
            const proxy_id = parseInt(data_proxy_id);
            const link = proxyLinkElement.getAttribute("href");
            proxyLinkElement.addEventListener("click", (e) => {
                e.preventDefault();
                this.click(proxy_id, link, true);
            });
            proxyLinkElement.addEventListener("auxclick", (e) => {
                this.click(proxy_id, link);
            });
        }
    }

    private static click(id : number, link : string, redirect = false){
        Api.json("/php/api/click.php", {id: id}, {method: "head"}).then(data => {
            if (redirect) window.location.href = link;
        });
    }

}