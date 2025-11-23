<?php
/* index.php   - Administrador  */

require '../includes/functions.php';
includeTemplate('header');

?>
<div class="admin-dashboard">
    <aside class="sidebar">
        <nav class="mt-10">
            <h3>Dashboard</h3>
            <ul>
                <li><button data-section="proveedores">Proveedores</button></li>
                <li><button data-section="categorias">Categorías</button></li>
                <li><button data-section="productos">Productos</button></li>
            </ul>
        </nav>
    </aside>

    <main class="main mt-5 mb-5">
        <section id="crud-area">
            <h2>Selecciona una opción del menú</h2>
            <!-- Aquí se cargará dinámicamente el CRUD -->
        </section>
    </main>
</div>

<?php
includeTemplate('footer');

?>