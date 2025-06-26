<?php
session_start();
require_once __DIR__ . '/../controllers/UserController.php';

$mensaje = '';
$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $userController->processRegister($_POST);
    if ($result['success']) {
        $_SESSION['idRol'] = $result['user']['idRol'];
        $_SESSION['email'] = $result['user']['email'];
        header('Location:../Index.php');
        exit;
    } else {
        $mensaje = $result['message'];
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
            <div style="color:red;"><?= htmlspecialchars($mensaje) ?></div>
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