
/* admin dashboard */

(function () {
    document.querySelectorAll('.sidebar button').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.dataset.section;

            fetch(`/admin/${section}/${section}.php`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('crud-area').innerHTML = html;
                })
                .catch(err => {
                    document.getElementById('crud-area').innerHTML = "<p>Error al cargar la sección.</p>";
                    console.error(err);
                });
        });
    });
})();
