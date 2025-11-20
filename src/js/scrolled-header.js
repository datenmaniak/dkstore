/* 
// Snippet mínimo para alternar la clase
//  .scrolled 
// */
(function () {
    const header = document.querySelector('[data-header]');
    if (!header) return;

    const SCROLL_THRESHOLD = 10; // píxeles desde el top para activar

    const onScroll = () => {
        if (window.scrollY > SCROLL_THRESHOLD) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };

    // Inicial y listeners
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
})();
