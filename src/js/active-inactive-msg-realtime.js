/* .
  Cambia el texto: activo / inactivo 
. */
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('activo');
    const estadoTexto = document.getElementById('estado-texto');

    function actualizarEstado() {
        estadoTexto.textContent = checkbox.checked ? ' Activo' : ' Inactivo';
    }

    checkbox.addEventListener('change', actualizarEstado);
});
