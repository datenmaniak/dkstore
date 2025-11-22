// Snippet para manejar flag 'home' en el headerContainer

(function () {
    document.addEventListener("DOMContentLoaded", () => {
        const headerContainer = document.querySelector(".header__container");
        if (!headerContainer) return; // seguridad: si no existe, no hace nada

        // Detecta la página actual
        const currentPage = window.location.pathname.split("/").pop();

        // Aplica la clase 'home' solo en index.php o raíz
        if (currentPage === "index.php" || currentPage === "") {
            headerContainer.classList.remove("bg-active");
        } else {
            headerContainer.classList.add("bg-active");
        }
    });
})();