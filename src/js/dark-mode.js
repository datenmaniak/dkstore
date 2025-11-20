(() => {
    document.addEventListener('DOMContentLoaded', function () {

        darkMode()


    });


    function darkMode() {

        const preferredDarkMode = window.matchMedia('(prefers-color-scheme: dark)');

        console.log('Current theme: ', preferredDarkMode.matches ? 'dark' : 'light');

        if (preferredDarkMode.matches) {
            document.body.classList.add('dark-mode');
        } else {

            document.body.classList.remove('dark-mode');

        }

        preferredDarkMode.addEventListener('change', (event) => {
            if (event.matches) {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }

        });

        const darkModeBtn = document.querySelector('.dark-mode-button');

        // Escuchamos el click
        darkModeBtn.addEventListener('click', () => {
            darkModeBtn.classList.toggle('active');
            document.body.classList.toggle('dark-mode');
            console.log('Dark mode active');
        });

    }
})();
