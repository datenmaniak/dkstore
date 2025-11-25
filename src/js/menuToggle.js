/* 

toggleMenu 

*/

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.menuToggle');
    const navbar = document.querySelector('.nav__options');
    const overlay = document.querySelector('.overlay');
    const mobileMenu = document.querySelector('.mobileMenu');
    /*  Login Button  */
    const navLogin = document.querySelector('.nav__login');


    if (!menuToggle || !navbar) return;


    const toggleMenu = () => {
      const isOpen = navbar.classList.toggle('active');
      menuToggle.classList.toggle('active');
      mobileMenu.classList.toggle('active');
      overlay?.classList.toggle('active');

      // Mostrar u ocultar login según estado
      navLogin.classList.toggle('active', isOpen);

      menuToggle.setAttribute('aria-expanded', isOpen);
      navbar.setAttribute('aria-hidden', !isOpen);
    };

    const closeMenu = () => {
      navbar.classList.remove('active');
      menuToggle.classList.remove('active');
      mobileMenu.classList.remove('active');
      overlay?.classList.remove('active');
      menuToggle.setAttribute('aria-expanded', false);
      navbar.setAttribute('aria-hidden', true);
      navLogin.classList.remove('active'); // ocultar login al cerrar
    };

    menuToggle.addEventListener('click', toggleMenu);
    mobileMenu.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', closeMenu);
    // mobileMenu.addEventListener('click', mobileMenu);

    // Opcional: cerrar con ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && navbar.classList.contains('active')) {
        closeMenu();
      }
    });

    // Cerrar al hacer click en cualquier enlace del menú
    navbar.addEventListener('click', (e) => {
      const link = e.target.closest('a');
      if (!link) return;

      // Cierra el menú inmediatamente
      if (navbar.classList.contains('active')) {
        closeMenu();
      }

    });

    // Resetea el menú si el viewport supera el breakpoint tablet
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) { // breakpoint tablet
        closeMenu();
        // navbar.classList.remove('active');
        // overlay?.classList.remove('active');
        // menuToggle.classList.remove('active');
        // menuToggle.setAttribute('aria-expanded', false);
        // En tablet/desktop el menú no debe estar "aria-hidden"
        navbar.setAttribute('aria-hidden', false);
      }
    });
  });
})();
