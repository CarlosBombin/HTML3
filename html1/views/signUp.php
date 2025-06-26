<?php
session_start();
require_once __DIR__ . '/../controllers/UserController.php';

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($nombre === '' || $apellidos === '' || $email === '' || $password === '' || $password2 === '') {
        $mensaje = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El correo electrónico no es válido.';
    } elseif ($password !== $password2) {
        $mensaje = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 10) {
        $mensaje = 'La contraseña debe tener al menos 10 caracteres.';
    } else {
        $userController = new UserController();
        $data = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => 'user'
        ];
        if ($userController->create($data)) {
            $usuario = $userController->getByEmail($email);
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['email'] = $usuario['email'];
            header('Location:../Index.php');
            exit;
        } else {
            $mensaje = 'El correo ya está registrado.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../estilo-index.css">
</head>
<body>
    <header>
        <h1>Conciertos y Festivales</h1>
        <div class="iconHeader">
            <a href="../index.php" class="iconLogIn">
                <img src="../Imagenes/Home.png" alt="login">
            </a>
        </div>
    </header>

    <div class="containerForm">
        <h2>Crear Cuenta</h2>
        <?php if ($mensaje): ?>
            <div style="color:red;"><?= $mensaje ?></div>
        <?php endif; ?>
        <form action="" method="POST" class="form">
            <div class="groupForm">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="groupForm">
                <label for="password2">Vuelva a introducir la contraseña</label>
                <input type="password" id="password2" name="password2">
            </div>
            <div class="groupForm">
                <button type="submit">Registrarse</button>
            </div>
        </form>
        <div class="signup">
            <a href="login.php" class="signup-link">¿Ya tienes una cuenta? Inicia sesión</a>
        </div>
    </div>
    
    <footer>
        <div id="left">
            <a href="#" class="footer-link">¿Tiene problemas?</a>
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