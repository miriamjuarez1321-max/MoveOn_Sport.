document.addEventListener("DOMContentLoaded", function () {
    console.log("JS cargado correctamente");

    const toggle = document.getElementById("menuToggle");
    const nav = document.getElementById("navMenu");

    console.log("toggle:", toggle);
    console.log("nav:", nav);

    if(toggle && nav){
        toggle.addEventListener("click", function () {
            console.log("CLICK EN HAMBURGUESA");
            nav.classList.toggle("active");
        });
    }
});
