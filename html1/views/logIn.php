<?php
session_start();
require_once __DIR__ . '/../controllers/UserController.php';

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        $userController = new UserController();
        $usuario = $userController->getByEmail($email);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            header('Location:../Index.php');
            exit;
        } else {
            $mensaje = 'Email o contraseña incorrectos.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        <h2>Iniciar Sesión</h2>
        <?php if ($mensaje): ?>
            <div style="color:red;"><?= $mensaje ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="groupForm">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="groupForm">
                <button type="submit">Ingresar</button>
            </div>
        </form>
        <div class="signup"><a href="signUp.php" class="signup-link">Si no tiene cuenta cree una</a></div>
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