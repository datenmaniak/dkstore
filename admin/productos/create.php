<?php

require '../../includes/config/database.php';
require  '../../includes/functions.php';

$db = conectDB();
// var_dump($db);

// Arreglo con mensajes de errores
$errores = [];

// Ejecutar despues que se envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";

    // leer variables
    $codigo = $_POST['codigo'];
    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $existencia = $_POST['existencia'];
    $stock_minimo = $_POST['stock_minimo'];
    $activo = $_POST['activo'];
    $proveedor_id = $_POST['proveedor_id'];
    $categoria_id = $_POST['categoria_id'];

    // validacion
    if (!$titulo) {
        $errores[] = 'Es necesario incluir un título';
    }
    if (!$precio) {
        $errores[] = 'Es necesario incluir un precio';
    }
    if (strlen($descripcion) < 50) {
        $errores[] = 'Es necesario incluir una descripcion, y debe tener al menis 50 caracteres';
    }
    if (!$existencia) {
        $errores[] = 'Es necesario incluir un existencia';
    }
    if (!$precio) {
        $errores[] = 'Es necesario incluir un precio';
    }
    if (!$stock_minimo) {
        $errores[] = 'Es necesario incluir un stock minimo';
    }
    if (!$proveedor_id) {
        $errores[] = 'Es necesario incluir el código del proveedor';
    }
    if (!$categoria_id) {
        $errores[] = 'Es necesario incluir el código de categoria';
    }


    echo "<pre>";
    var_dump($errores);
    echo "</pre>";

    exit;

    // insertar en la DB
    $query = "INSERT INTO productos (codigo, titulo, precio, descripcion, existencia, stock_minimo, 
    proveedor_id, categoria_id ) VALUES ('$codigo', '$titulo', '$precio', '$descripcion', '$existencia',
     $stock_minimo,  
    '$proveedor_id', '$categoria_id' )";

    // echo $query;
    $res = mysqli_query($db, $query);

    if ($res) {
        echo "Insertado correcto en la DB";
    }
}

includeTemplate('header');
?>

<main class="add-products-container mt-10">
    <h2>Administrador de la tienda</h2>

    <form method="POST" class="form-productos" enctype="multipart/form-data" action="/admin/productos/create.php">
        <h3>Registrar Producto</h3>
        <label>Código (SKU): <input type="text" name="codigo"></label>
        <label>Título: <input type="text" name="titulo"></label>
        <label>Precio: <input type="number" step="0.01" name="precio"></label>
        <label>Imagen: <input type="file" name="imagen" accept="image/*"></label>
        <label>Descripción: <textarea name="descripcion"></textarea></label>
        <label>Existencia: <input type="number" name="existencia"></label>
        <label>Stock mínimo: <input type="number" name="stock_minimo"></label>
        <label>Activo: <input type="checkbox" name="activo" checked></label>
        <label>ID Proveedor: <input type="number" name="proveedor_id"></label>
        <label>ID categoría: <input type="number" name="categoria_id"></label>
        <button type="submit">Guardar Producto</button>
    </form>

    <a href="/admin/adm.php"
        class="btn btn-secondary">Volver</a>
    <div class="return-home mb-10"></div>
</main>

<?php
includeTemplate('footer');

?>