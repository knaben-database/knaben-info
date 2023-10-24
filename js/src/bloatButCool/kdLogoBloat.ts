let KdLogoBloat = (function () {

    function initialize(){
        let elms : NodeListOf<HTMLElement> = document.querySelectorAll(".cssLogo.animated");
        for (let i = 0; i < elms.length; i++) {
            elms[i].style.transitionDuration = "0s";
            spawnPos(elms[i]);
            // Don't want to auto search right away
            setTimeout(function() {
                elms[i].style.transitionDuration = "";
                animate(elms[i]);
            }, 50);
        }
    }

    function spawnPos(elm){
        let layers = elm.getElementsByClassName("foredeck")[0].children;
        for (let i = 0; i < layers.length; i++) {
            let containers = layers[i].children;
            for (let j = 0; j < containers.length; j++) {
                containers[j].style.transform = "translate(0, -600px)";
                containers[j].style.transitionDuration = "0s";
            }
        }
        elm.style.opacity = "1";
    }

    function animate(elm){
        elm.style.opacity = "1";
        elm.style.transform = "translate(0px)";
        setTimeout(function() {
            let layers = elm.getElementsByClassName("foredeck")[0].children;
            for (let i = 0; i < layers.length; i++) {
                let containers = layers[i].children;
                for (let j = 0; j < containers.length; j++) {
                    containers[j].style.transition = "all cubic-bezier(0.5, 0.25, 0.27, 0.1) 1s";
                    setTimeout(function() {
                        containers[j].style.transform = "translate(0)";
                    }, 50*(j-containers.length)*(i-layers.length));
                }
            }
        }, 2000);
    }



    // Here you can set what to be public
    return {
        // Name : Function
        init    : initialize
    };
})();