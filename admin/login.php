<?php


/* require_once __DIR__ . '/includes/functions.php'; */
require '../includes/functions.php';
includeTemplate('header');


// gestión de conexión
require_once PATH_CONFIG . '/database.php';
session_start();

// Conexión a la BD
$db = conectDB();

// notifications
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar datos del formulario
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Preparar query
    $query = "SELECT id, username, password_hash, role, is_active 
          FROM users 
          WHERE username = ? 
          LIMIT 1";

    $stmt = mysqli_prepare($db, $query);

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($usuario = mysqli_fetch_assoc($result)) {
            if ($usuario['is_active']) {
                // Verificar contraseña
                if (password_verify($password, $usuario['password_hash'])) {
                    // Guardar sesión
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['username'] = $usuario['username'];
                    $_SESSION['role'] = $usuario['role'];

                    // Redirección según rol
                    if ($usuario['role'] === 'admin') {
                        header("Location: /admin/index.php");
                    } else {
                        header("Location: /");
                    }

                    // echo "✅ Bienvenido {$usuario['username']} (Rol: {$usuario['role']})";
                    // header("Location: dashboard.php"); // redirección opcional
                } else {
                    $error_message =  " Credenciales inválidas.";
                }
            } else {
                $error_messages = "Usuario inactivo.";
            }
        } else {
            $error_message = "Usuario no encontrado.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $error_message = "❌ Error al preparar la consulta: " . mysqli_error($db);
    }
}


mysqli_close($db);

?>

<main class="admin-layoutcmt mt-15">


    <section class="narrow-container auth-form top-margin ">
        <h2>Autenticación de Usuario</h2>
        <form method="POST">
            <label for="username">Usuario:</label>
            <input type="text" id="username" placeholder="Su cuenta de usuario" name="username" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" placeholder="" name="password" required>

            <button type="submit" class="btn btn-block-50 ">Ingresar </button>
        </form>

        <?php if (!empty($error_message)): ?>
            <div class="error-band">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>
    </section>

</main>

<script src="/build/js/bundle.js"></script>

<?php

includeTemplate('footer');

?>