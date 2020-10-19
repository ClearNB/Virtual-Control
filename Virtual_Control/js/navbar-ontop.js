(function () {
    var className = "navbar-ontop";
    document.write("<style id='temp-navbar-ontop'>.navbar {opacity:1; transition: none !important}</style>");

    function update() {
        var nav = document.querySelector(".navbar");
        if (window.scrollY > 15) {
            nav.classList.remove(className);
        } else {
            nav.classList.add(className);
        }
    }

    document.addEventListener("DOMContentLoaded", function (event) {
        $(window).on('show.bs.collapse', function (e) {
            $(e.target).closest("." + className).removeClass(className);
        });

        $(window).on('hidden.bs.collapse', function (e) {
            update();
        });
        update();
        setTimeout(function () {
            document.querySelector("#temp-navbar-ontop").remove();
        });
    });
    
    window.addEventListener("scroll", function () {
        update();
    });
})();