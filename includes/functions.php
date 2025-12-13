<!-- functions.php -->

<?php

    /* Globals Functions file */

    /**
     * Genera una notificación HTML.
     *
     * @param int|string $result Puede ser un código numérico o directamente un mensaje personalizado
     * @param string|null $type Tipo de notificación: success | warning | error | info
     * @return string HTML de la notificación
     */
    function renderNotification($result, ?string $type = null): string
    {
        // Mapeo de códigos a mensajes y tipos
        $resultMessages = [
            1 => ['Producto registrado correctamente', 'success'],
            2 => ['Producto actualizado correctamente', 'success'],
            3 => ['Producto eliminado correctamente', 'warning'],
            9 => ['Error: Notifique al administrador de sistemas', 'error'],
        ];

        // Caso: resultado numérico
        if (is_int($result)) {
            if ($result === 0) {
                return ''; // No mostrar nada
            }
            [$message, $resolvedType] = $resultMessages[$result] ?? ['Acción desconocida o no registrada', 'warning'];
            $type                     = $type ?? $resolvedType;
        } else {
            // Caso: mensaje personalizado
            $message      = $result;
            $allowedTypes = ['success', 'warning', 'error', 'info'];
            $type         = in_array($type, $allowedTypes) ? $type : 'info';
        }

        // Sanitizar salida para evitar inyecciones
        $safeMessage = sanitizeHTML($message);

        return "<p class=\"notification-bar {$type} medium center hide\">{$safeMessage}</p>";

        // // Ejemplo con códigos predefinidos
        // echo renderNotification(1); // Producto registrado correctamente

        // // Ejemplo con mensaje personalizado
        // echo renderNotification('Este producto ya existe en la base de datos', 'warning');
        // echo renderNotification('Error crítico al conectar con la base de datos', 'error');
    }

    /**
     * Incluye un formulario de manera segura y DRY
     *
     * @param string $tpl Nombre del archivo de plantilla (sin extensión)
     * @param array $vars Variables que el formulario necesita
     */
    function ViewForm(string $tpl, array $vars = []): bool
    {
        // Convierte las claves del array en variables locales
        extract($vars, EXTR_SKIP);

        $path = __DIR__ . "/templates/forms/{$tpl}.php";

        if (file_exists($path)) {
            include $path;
            return true;
        } else {
            showNotification("Formulario no existe: " . htmlspecialchars($tpl), false);
            // echo "<div class='notification-bar error '>Formulario no encontrado: {$tpl}</div>";
            return false;
        }
    }
    // includes/functions.php
    function includeForm($form_name, $data = [])
    {
        /*
Formularios DRY: Estado + Scope + Seguridad
Uso: includeForm('product', ['producto' => $producto, 'sellers_list' => $sellers_list]);
*/

        // $form_path = $_SERVER['DOCUMENT_ROOT'] . "/templates/forms/{$form_name}.php";
        $form_path = PATH_INCLUDES . "/templates/forms/{$form_name}.php";

        if (! file_exists($form_path)) {
            showNotification("Formulario no existe: {$form_name}", true);
            return false;
        }

        // ✅ SEGURIDAD: Solo variables específicas de formulario
        $safe_data = [
            'producto'        => $data['producto'] ?? null,
            'errores'         => $data['errores'] ?? [],
            'categories_list' => $data['categories_list'] ?? [],
            'sellers_list'    => $data['sellers_list'] ?? [],
            'images_folder'   => $data['images_folder'] ?? '',
            'imagen_mostrar'  => $data['imagen_mostrar'] ?? '',
            'mostrar_spinner' => $data['mostrar_spinner'] ?? '',
            'imgNewName'      => $data['imgNewName'] ?? '',
            'no_image'        => $data['no_image'] ?? '',

        ];

        // ✅ RENDIMIENTO: Solo variables necesarias
        extract($safe_data, EXTR_SKIP | EXTR_REFS);

        // ✅ DRY: Formularios reutilizables
        include $form_path;
        return true;
    }

    // escapa el HTML
    function sanitizeHTML($html): string
    {
        // $s = htmlspecialchars($html);
        // return $s;
        return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    function debugResult($var = '', $must_end = true)
    {
        echo "
<pre>";
        var_dump($var);
        echo "</pre>";
        if ($must_end) {
            exit();
        }

    }

    function requireLogin()
    {
        session_start();
        if (! isset($_SESSION['user_id'])) {
            header("Location: /admin/login.php");
            exit;
        }
    }

    function requireRole($role)
    {
        session_start();
        if (! isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            header("Location: /admin/no-access.php");
            exit;
        }
    }

    function includeTemplate(string $page): bool
    {

        // templates whitelist permit
        $allowed = [
            'header',
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
            'product-form',
            'no-access',
            'footer',
            'scripts',
            'end-page',
        ];

        // Validar si la plantilla está permitida
        if (! in_array($page, $allowed, true)) {
            showNotification("Plantilla no ha sido autorizada: " . htmlspecialchars($page), true);
            return false;
        }
        $template_path = PATH_TEMPLATES . "/$page.php";

        // Validar si el archivo existe
        if (file_exists($template_path)) {
            extract(get_defined_vars(), EXTR_SKIP); // ← PASA TODAS variables
            include $template_path;                 // ✅ UX suave
            return true;

        } else {
            showNotification("Plantilla no existe: " . htmlspecialchars($page), true);
            return false;

        }

    }
    /**
     * Renderiza una notificación estándar
     */
    function showNotification(string $message, bool $hide): void
    {

        if ($hide) {
            echo '<div class="notification-bar warning high center hide">';
        } else {
            echo '<div class="notification-bar warning high center">';

        }
        echo ' <span class="icon">⚠️</span>';
        echo ' <div class="message">' . $message . '</div>';
        // echo '<span class="close-btn">';
        // echo 'img src="/assets/icons/close.svg" width="40px" height="40px" alt="">';
        // echo '</span>';
        echo '</div>';
    }

    function validarImagen(array $archivo): array
    {

        $maxSizeBytes  = 100 * 1024; // 100 KB
        $extPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // Verificar carga exitosa
        if ($archivo['error'] !== UPLOAD_ERR_OK || empty($archivo['tmp_name']) || ! is_uploaded_file($archivo['tmp_name'])) {
            return ['valida' => false, 'error' => 'Error en carga del archivo'];
        }

        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if (! in_array($ext, $extPermitidas)) {
            return ['valida' => false, 'error' => 'Extensión no permitida'];
        }

        // MÉTODO MODERNO: Validar por firma mágica de imagen
        $imageType = exif_imagetype($archivo['tmp_name']);
        if ($imageType === false) {
            return ['valida' => false, 'error' => 'No es una imagen válida'];
        }

        $mimeReal     = image_type_to_mime_type($imageType);
        $mimesValidos = [
            IMAGETYPE_JPEG => 'image/jpeg',
            IMAGETYPE_PNG  => 'image/png',
            IMAGETYPE_GIF  => 'image/gif',
            IMAGETYPE_WEBP => 'image/webp',

        ];

        if (! isset($mimesValidos[$imageType]) || $mimeReal !== $mimesValidos[$imageType]) {
            return ['valida' => false, 'error' => 'Tipo de imagen no permitido'];
        }

        // // Validar MIME type real del archivo
        // $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // $mime = finfo_file($finfo, $archivo['tmp_name']);
        // // finfo_close($finfo);

        // $mimePermitidos = [
        // 'jpg',
        // 'jpeg' => 'image/jpeg',
        // 'png' => 'image/png',
        // 'gif' => 'image/gif'
        // ];

        // if (!isset($mimePermitidos[$ext]) || $mime !== $mimePermitidos[$ext]) {
        // return ['valida' => false, 'error' => 'Tipo MIME inválido'];
        // }

        // Validar tamaño
        if ($archivo['size'] > $maxSizeBytes || $archivo['size'] === 0) {
            return ['valida' => false, 'error' => "El tamaño del archivo excede $maxSizeBytes bytes (imagen)"];
        }

        return ['valida' => true, 'archivo' => $archivo];
    }

    // Función para generar nombre único
    function generarNombreUnico(string $nombreOriginal): string
    {
        $uploadDir = '../../uploads/';

        if (! file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        return uniqid(bin2hex(random_bytes(8)), true) . '.' . $ext;
    }

?>