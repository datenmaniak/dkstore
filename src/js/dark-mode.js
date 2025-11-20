(() => {
    const darkModeBtn = document.querySelector('.dark-mode-button');

    // Escuchamos el click
    darkModeBtn.addEventListener('click', () => {
        darkModeBtn.classList.toggle('active');
        document.body.classList.toggle('dark-mode');
    });
})();
