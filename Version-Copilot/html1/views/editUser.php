<?php
session_start();
require_once __DIR__ . '/../controllers/UserController.php';

$mensaje = '';
$userController = new UserController();

// Obtener el email del usuario logueado desde la sesión
$email = $_SESSION['email'] ?? '';
$usuario = $userController->getByEmail($email);

if (!$usuario) {
    header('Location: logIn.php');
    exit;
}

// Si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $nuevoEmail = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    // Validaciones básicas
    if ($nombre === '' || $apellidos === '' || $nuevoEmail === '' || $password === '' || $password2 === '') {
        $mensaje = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El correo electrónico no es válido.';
    } elseif ($password !== $password2) {
        $mensaje = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 10) {
        $mensaje = 'La contraseña debe tener al menos 10 caracteres.';
    } else {
        // Actualizar usuario
        $updateData = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'email' => $nuevoEmail,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        $userController->update($email, $updateData);
        $_SESSION['email'] = $nuevoEmail;
        $mensaje = 'Datos actualizados correctamente.';
        // Recargar datos actualizados
        $usuario = $userController->getByEmail($nuevoEmail);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cuenta</title>
    <link rel="stylesheet" href="../estilo-index.css?v=<?= time() ?>">
</head>
<body>
    <header>
        <h1>Conciertos y Festivales</h1>
        <a href="IndexUser.php" class="iconLogIn">
            <img src="../Imagenes/home.png" alt="Volver al inicio">
        </a>
    </header>

    <div class="containerForm">
        <h2>Editar Cuenta</h2>
        <?php if ($mensaje): ?>
            <div style="color:red;"><?= $mensaje ?></div>
        <?php endif; ?>
        <form action="" method="POST" class="form">
            <div class="groupForm">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" value="">
            </div>
            <div class="groupForm">
                <label for="password2">Vuelva a introducir la contraseña</label>
                <input type="password" id="password2" name="password2" value="">
            </div>
            <div class="groupForm">
                <button type="submit">Editar</button>
            </div>
        </form>
    </div>
    
    <footer>
        <div id="left">
            <a href="#" class="footer-link">Tiene problemas?</a>
        </div>
        <div id="center">
            <p>&copy; 2023 Conciertos y Festivales. Todos los derechos reservados.</p>
        </div>
        <div id="right">
            <div class="redesSociales">
                <a href="#"><img src="../Imagenes/facebook.png" alt="Facebook" id="facebook"></a>
                <a href="#"><img src="../Imagenes/instagram.png" alt="Instagram" id="instagram"></a>
                <a href="#"><img src="../Imagenes/twitter.png" alt="Twitter" id="x"></a>
            </div>
        </div>
    </footer>
</body>
</html>