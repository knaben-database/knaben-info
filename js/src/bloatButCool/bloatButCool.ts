class BloatButCool {


    public static init() {
        
        // Init logo bloat
        const logos = document.querySelectorAll(".css-logo");
        for (let i = 0; i < logos.length; i++) {
            const logo = logos[i] as HTMLDivElement;
            new LogoBloat(logo);
        }

        // Init kd logo bloat
        KdLogoBloat.init();
    }
}