const sections = document.querySelectorAll("section");

sections.forEach((section) => {
    window.addEventListener("load", eventListner); 
    window.addEventListener("scroll", eventListner);

    function eventListner() {
        let windowHeight = window.innerHeight; 
        let sectionRectTop = section.getBoundingClientRect().top; 

        console.log("Window height:" + windowHeight); 
        console.log("SectionRectTop:" + sectionRectTop);

        if(sectionRectTop < windowHeight) {
            section.classList.add("active"); 
        }
    }

    window.addEventListener("scroll", () => {
        let reveals = section.querySelectorAll(".reveal"); 

        reveals.forEach((reveal, index) => {
            let windowHeight = window.innerHeight; 
            let revealRectTop = reveal.getBoundingClientRect().top; 
            let revealPoint = -250;

            if(revealRectTop < windowHeight - revealPoint) {
                const delay = 600;

                setTimeout(() => {
                    reveal.classList.add("active"); 
                }, index * delay)
            }
        });
    });
    

    window.addEventListener("load", () => {
        let reveals = section.querySelectorAll(".reveal"); 

        reveals.forEach((reveal, index) => {
            let windowHeight = window.innerHeight; 
            let revealRectTop = reveal.getBoundingClientRect().top; 

            if(revealRectTop < windowHeight) {
                const delay = 600;

                setTimeout(() => {
                    reveal.classList.add("active"); 
                }, index * delay)
            }
        });
    });
});