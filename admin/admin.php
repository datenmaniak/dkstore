<?php
/* index.php   - Administrador  */

// before
// require '../includes/functions.php';
// simplificado
require_once __DIR__ . '/../../includes/app.php';

includeTemplate('header');

?>
<div class="admin-dashboard">
    <aside class="sidebar">
        <nav class="mt-10">
            <h3>Dashboard</h3>
            <ul>
                <li><button class="btn" data-section="proveedores">Proveedores</button></li>
                <li><button data-section="categorias">Categorías</button></li>
                <li><button data-section="productos">Productos</button>
                    <a href="/admin/productos/create.php"></a>
                </li>
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