<?php
session_start();
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/RequestController.php';

$userController = new UserController();

$email = $_SESSION['email'] ?? '';
$usuario = $userController->getByEmail($email);

if (!$usuario) {
    header('Location: logIn.php');
    exit;
}

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['solicitar_promotor'])) {
    $mensaje = $userController->processEditUser($email, $_POST);
    if ($mensaje === 'Datos actualizados correctamente.') {
        $_SESSION['email'] = trim($_POST['email']);
        $usuario = $userController->getByEmail($_SESSION['email']);
    }
}

$requestMensaje = '';
if (isset($_POST['solicitar_promotor'])) {
    $emailRequest = $_SESSION['email'] ?? '';
    $requestController = new RequestController();
    $requestMensaje = $requestController->createRequest($emailRequest);
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
        <div class="iconHeader">
            <a href="../index.php" class="iconLogIn">
                <img src="../Imagenes/home.png" alt="Volver al inicio">
            </a>
        </div>
    </header>

    <div class="containerForm">
        <h2>Editar Cuenta</h2>
        <?php if ($mensaje): ?>
            <div style="color:red;"><?= $mensaje ?></div>
        <?php endif; ?>
        <form action="" method="POST" class="form">
            <div class="groupForm">
                <label for="nombre">Nombre*</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="apellidos">Apellidos*</label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="password">Contraseña*</label>
                <input type="password" id="password" name="password" value="">
            </div>
            <div class="groupForm">
                <label for="password2">Vuelva a introducir la contraseña*</label>
                <input type="password" id="password2" name="password2" value="">
            </div>
            <div class="groupForm">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="localidad">Localidad</label>
                <input type="text" id="localidad" name="localidad" value="<?= htmlspecialchars($usuario['localidad'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="codigo_postal">Código Postal</label>
                <input type="text" id="codigoPostal" name="codigoPostal" value="<?= htmlspecialchars($usuario['codigoPostal'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="tarjeta">Número tarjeta de crédito</label>
                <input type="text" id="nTarjeta" name="nTarjeta" value="<?= htmlspecialchars($usuario['nTarjeta'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="caducidad">Fecha caducidad (YYYY-MM)</label>
                <input type="month" id="fCaducidad" name="fCaducidad"
                    value="<?= htmlspecialchars(
                        isset($usuario['fCaducidad']) && strlen($usuario['fCaducidad']) >= 7
                            ? substr($usuario['fCaducidad'], 0, 7)
                            : ''
                    ) ?>">
            </div>
            <div class="groupForm">
                <label for="CCV">CCV</label>
                <input type="text" id="CCV" name="CCV" value="<?= htmlspecialchars($usuario['CCV'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <button type="submit">Editar</button>
            </div>
        </form>
    </div>

    <div class="containerForm" style="margin-top:30px;">
        <form action="" method="POST">
            <div class="groupForm">
                <button type="submit" name="solicitar_promotor">Solicitar cambio de rol a promotor</button>
            </div>
        </form>
        <?php if (!empty($requestMensaje)): ?>
            <div style="color:green; margin-top:10px;"><?= htmlspecialchars($requestMensaje) ?></div>
        <?php endif; ?>
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