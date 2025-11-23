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

        // any other index without hero, no count!
        // if (currentPage === "/admin/index.php") {
        //     // if (currentPage === "/admin/index.php" || currentPage === "") {
        //     headerContainer.classList.add("bg-active");
        // } else {
        //     headerContainer.classList.remove("bg-active");
        // }

    });
})();