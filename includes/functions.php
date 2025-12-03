<!-- functions.php -->

<?php

/* Functions file */


require __DIR__ . '/app.php';

function includeTemplate(string $page)
{
    // templates whitelist permit
    $allowed = [
        'header',
        'footer',
        'about-us',
        'ads',
        'catalog-featured',
        'catalog-minimal',
        'catalog-std',
        'catalog-split',
        'color-palette',
        'catalog-std-db',
        'hero',
        'home',
        'end-page'
    ];

    if (in_array($page, $allowed)) {
        require PATH_TEMPLATES . "/$page.php";
    } else {
        echo "$page: Plantilla no permitida.";
    }
}
function validarImagen(array $archivo): array
{

    $maxSizeBytes = 100 * 1024; // 100 KB
    $extPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    // Verificar carga exitosa
    if ($archivo['error'] !== UPLOAD_ERR_OK || empty($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
        return ['valida' => false, 'error' => 'Error en carga del archivo'];
    }

    $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $extPermitidas)) {
        return ['valida' => false, 'error' => 'Extensión no permitida'];
    }

    // MÉTODO MODERNO: Validar por firma mágica de imagen
    $imageType = exif_imagetype($archivo['tmp_name']);
    if ($imageType === false) {
        return ['valida' => false, 'error' => 'No es una imagen válida'];
    }

    $mimeReal = image_type_to_mime_type($imageType);
    $mimesValidos = [
        IMAGETYPE_JPEG => 'image/jpeg',
        IMAGETYPE_PNG => 'image/png',
        IMAGETYPE_GIF => 'image/gif',
        IMAGETYPE_WEBP => 'image/webp',

    ];

    if (!isset($mimesValidos[$imageType]) || $mimeReal !== $mimesValidos[$imageType]) {
        return ['valida' => false, 'error' => 'Tipo de imagen no permitido'];
    }

    // // Validar MIME type real del archivo
    // $finfo = finfo_open(FILEINFO_MIME_TYPE);
    // $mime = finfo_file($finfo, $archivo['tmp_name']);
    // // finfo_close($finfo);

    // $mimePermitidos = [
    //     'jpg',
    //     'jpeg' => 'image/jpeg',
    //     'png' => 'image/png',
    //     'gif' => 'image/gif'
    // ];

    // if (!isset($mimePermitidos[$ext]) || $mime !== $mimePermitidos[$ext]) {
    //     return ['valida' => false, 'error' => 'Tipo MIME inválido'];
    // }

    // Validar tamaño
    if ($archivo['size'] > $maxSizeBytes || $archivo['size'] === 0) {
        return ['valida' => false, 'error' => "Tamaño excede $maxSizeBytes bytes"];
    }

    return ['valida' => true, 'archivo' => $archivo];
}

// Función para generar nombre único
function generarNombreUnico(string $nombreOriginal): string
{
    $uploadDir = '../../uploads/';

    if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);

    $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
    return uniqid(bin2hex(random_bytes(8)), true) . '.' . $ext;
}




?>