// Snippet para manejar flag 'home' en el headerContainer

(function () {
    document.addEventListener("DOMContentLoaded", () => {

        const textarea = document.querySelector('[name="descripcion"]');
        const contador = document.getElementById('description_input_counter');
        textarea.addEventListener('input', () => {
            contador.textContent = `${textarea.value.length}/255`;
        });
    });
})();