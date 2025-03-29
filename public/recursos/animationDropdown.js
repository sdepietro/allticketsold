document.addEventListener('DOMContentLoaded', () => {
    const btnMenos = document.querySelectorAll(".btnMenos");

    btnMenos.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const target = e.target;
            const isActive = target.src.includes("menosIcon.png");

            // Restablecer la imagen de todos los botones
            btnMenos.forEach((btn) => {
                if (btn !== target) {
                    btn.src = "./public/masIcon.png";
                    btn.parentElement.nextElementSibling.classList.remove('active');
                }
            });

            // Cambiar la imagen del botón clickeado
            if (isActive) {
                target.src = "./public/masIcon.png";
                target.parentElement.nextElementSibling.classList.remove('active');
            } else {
                target.src = "./public/menosIcon.png";
                target.parentElement.nextElementSibling.classList.add('active');
            }
        });
    });
});
