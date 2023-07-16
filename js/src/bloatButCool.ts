class BloatButCool {

    private static logos = [];


    public static init() {
        const logos = document.querySelectorAll(".css-logo");
        for (let i = 0; i < logos.length; i++) {
            const elm = logos[i] as HTMLDivElement;
            const logo = {
                planetPopInterval: 3,
                elm: elm
            };
            elm.addEventListener("mouseenter", () => logo.planetPopInterval = 0.25);
            elm.addEventListener("mouseleave", () => logo.planetPopInterval = 3);
            this.logoEffect(logo);
            this.logos.push(logo);
        }
    }
    private static logoEffect(logo){
        let randomTimer = logo.planetPopInterval;
        var counter = 10;
        const myFunction = () => {
            this.blinkRandomPlanet(logo.elm);
            counter = logo.planetPopInterval * 1000;
            setTimeout(myFunction, counter);
        }
        setTimeout(myFunction, counter);
    }

    private static blinkRandomPlanet(elm){
        const duration = 2;
        const planetCores = elm.querySelectorAll(".planet-core");
        const choice = Math.floor(Math.random() * planetCores.length);

        let highlightColor = Style().getPropertyValue("--bs-highlight");

        const planetCore = planetCores[choice];
        planetCore.style.transitionDuration = ``;
        planetCore.style.backgroundColor = highlightColor;
        setInterval(() => {
            planetCore.style.transitionDuration = `${duration}s`;
            setInterval(() => {
                planetCore.style.backgroundColor = "";
            }, duration*100);
        }, 100);
    }
}