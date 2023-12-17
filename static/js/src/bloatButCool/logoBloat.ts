class LogoBloat {

    private elm : HTMLDivElement;

    /**
     * Times the planets blink per second
     */
    private blinkInterval = {
        idle: 0.25,
        active: 3
    };

    /**
     * If we are hovering it or not
     */
    private hovering = false;

    constructor(elm : HTMLDivElement) {
        this.elm = elm;
        this.blinkLoop();
        this.elm.addEventListener("mouseenter", () => this.hovering = true);
        this.elm.addEventListener("mouseleave", () => this.hovering = false);
    }

    private getColor(): string|null {
        let color: string|null;
        if (this.elm.hasAttribute("data-logo-color-theme") && this.elm.getAttribute("data-logo-color-theme")?.trim() !== "")
            color = this.elm.getAttribute("data-logo-color-theme");
        else
            color = Style().getPropertyValue("--bs-highlight");
        return color;
    }

    private blinkLoop(){
        const planetCores: NodeListOf<HTMLDivElement> = this.elm.querySelectorAll(".planet-core");
        const highlightColor = this.getColor();
        const blinkingPlanetCores = new Set();
        let counter = 0;
        setInterval(() => {
            if (!this.hovering) {
                counter += this.blinkInterval.idle;
                if (this.blinkInterval.active > counter) return;
                else counter = 0;
            }

            // Check if all the planet cores are already blinking
            if (blinkingPlanetCores.size === planetCores.length) {
                return;
            }

            let randomPlanetCore : HTMLDivElement;
            do {
                const randomIndex = Math.floor(Math.random() * planetCores.length);
                randomPlanetCore = planetCores[randomIndex];
            } while (blinkingPlanetCores.has(randomPlanetCore));

            // Add the selected planet core to the set of blinking planet cores
            blinkingPlanetCores.add(randomPlanetCore);

            // Change the background color of the selected planet core
            if (highlightColor)
                randomPlanetCore.style.backgroundColor = highlightColor; // Change to the desired color
            randomPlanetCore.style.transform = "scale(1.1)";
            randomPlanetCore.style.transitionDuration = ""

            // Set a timeout to change the background color back after some time
            setTimeout(() => {
                blinkingPlanetCores.delete(randomPlanetCore);
                randomPlanetCore.style.transitionDuration = `${this.blinkInterval.active}s`;
                randomPlanetCore.style.backgroundColor = ""; // Change back to the original color
                randomPlanetCore.style.transform = "scale(1)";
            }, this.blinkInterval.idle*1000); // Change the duration of the blink here
        }, 1/this.blinkInterval.active*1000)
    }
}