<?php
require_once __DIR__ . '/../controllers/RequestController.php';
require_once __DIR__ . '/../controllers/UserController.php';

$requestController = new RequestController();
$userController = new UserController();

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    if (isset($_POST['aceptar'])) {
        $usuario = $userController->getByEmail($email);
        if ($usuario) {
            $userController->update($email, ['idRol' => 2]);
            $mensaje = "Rol de $email actualizado a promotor.";
        }
        $requestController->deleteRequest($email);
    } elseif (isset($_POST['rechazar'])) {
        $requestController->deleteRequest($email);
        $mensaje = "Solicitud de $email rechazada y eliminada.";
    }
}

$requests = $requestController->getAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Promotor - Admin</title>
    <link href="estilo-index.css?v=<?= time() ?>" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Solicitudes para ser Promotor</h1>
    </header>
    <main>
        <?php if ($mensaje): ?>
            <div class="messageStatus"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($requests && count($requests) > 0): ?>
            <div class="containerRequest">
                <table class="tableRequest">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($requests as $req): ?>
                        <?php
                            $usuario = $userController->getById($req['usuarios_id']);
                            $email = $usuario ? $usuario['email'] : 'Desconocido';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($email) ?></td>
                            <td>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                                    <button type="submit" name="aceptar" class="acceptButton">Aceptar</button>
                                    <button type="submit" name="rechazar" class="dismissButton">Rechazar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="noRequest">No hay solicitudes pendientes.</div>
        <?php endif; ?>
    </main>
</body>
</html>