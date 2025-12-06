/* .
  Cambia el texto: activo / inactivo 
. */
document.addEventListener('DOMContentLoaded', function () {
  const checkbox = document.getElementById('is_active');
  const estadoTexto = document.getElementById('estado-notificacion');

  function actualizarEstado() {
    estadoTexto.textContent = checkbox.checked ? ' Activo' : ' Inactivo';
  }

  checkbox.addEventListener('change', actualizarEstado);
});
