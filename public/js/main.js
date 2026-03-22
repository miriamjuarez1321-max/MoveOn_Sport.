document.addEventListener("DOMContentLoaded", function () {
    // Hamburger menu toggle logic
    const menuToggle = document.getElementById('menuToggle');
    const nav = document.getElementById("navMenu");
    const overlay = document.getElementById("navOverlay");

    const openNav = () => {
        nav.classList.add("active");
        nav.style.maxHeight = ""; // side drawer usa transform; limpiamos restricciones
        toggle.setAttribute("aria-expanded", "true");
        toggle.classList.add("active");
        if (overlay) overlay.classList.add("visible");
    };

    const closeNav = () => {
        nav.style.maxHeight = "0px";
        nav.classList.remove("active", "show");
        toggle.setAttribute("aria-expanded", "false");
        toggle.classList.remove("active");
        if (overlay) overlay.classList.remove("visible");
    };

    const toggleNav = () => {
        if (!nav.classList.contains("active")) {
            openNav();
        } else {
            closeNav();
        }
    };

    if (toggle && nav) {
        // ARIA for accessibility/compat with Bootstrap semantics
        toggle.setAttribute("role", "button");
        toggle.setAttribute("aria-controls", "navMenu");
        toggle.setAttribute("aria-expanded", "false");

        toggle.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleNav();
        });

        // Close when clicking outside on mobile
        document.addEventListener("click", function (e) {
            if (nav.classList.contains("active") && !nav.contains(e.target) && e.target !== toggle) {
                closeNav();
            }
        });
        // Evitar cierre al hacer click dentro del menú
        nav.addEventListener("click", function (e) {
            e.stopPropagation();
        });
        if (overlay) {
            overlay.addEventListener("click", function () {
                closeNav();
            });
        }

        // Cerrar al hacer click en cualquier enlace del menú (sin bloquear navegación)
        const navLinks = nav.querySelectorAll("a[href]");
        navLinks.forEach((a) => {
            a.addEventListener("click", () => closeNav());
        });

        // Reset on resize: ensure proper state when moving to desktop
        window.addEventListener("resize", function () {
            const isDesktop = window.matchMedia("(min-width: 901px)").matches;
            if (isDesktop) {
                nav.style.maxHeight = "";
                nav.classList.remove("active");
                toggle.setAttribute("aria-expanded", "false");
                toggle.classList.remove("active");
                if (overlay) overlay.classList.remove("visible");
            }
        });
    }
});
