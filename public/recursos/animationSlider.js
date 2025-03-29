const slideObras = document.querySelectorAll(".animationShow");

document.addEventListener("DOMContentLoaded", () => {
    slideObras.forEach((slideObra) => {
        const descripcion = slideObra.querySelector(".containerDescription");
        slideObra.addEventListener("mouseenter", (e) => {
            descripcion.style.display = "flex";
        });
        slideObra.addEventListener("mouseleave", (e) => {
            descripcion.style.display = "none";
        });
    });
});
