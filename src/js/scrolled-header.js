/* 
// Snippet mínimo para alternar la clase
//  .scrolled 
// */
(function () {
    const header = document.querySelector('[data-header]');
    // const headerContainer = document.querySelector('.header__container');

    if (!header) return;

    const SCROLL_THRESHOLD = 10; // píxeles desde el top para activar

    const onScroll = () => {
        if (window.scrollY > SCROLL_THRESHOLD) {
            header.classList.add('scrolled');
            // headerContainer.classList.add('home');
        } else {
            header.classList.remove('scrolled');
        }
    };

    // Inicial y listeners
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
})();
