<?php
require_once __DIR__ . '/../controllers/EventController.php';

$eventController = new EventController();
$usuarioEmail = $_SESSION['email'] ?? '';
$eventos = [];
if ($usuarioEmail) {
    $eventos = $eventController->getByUserEmail($usuarioEmail); // Debes crear este método en el controlador
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConFest</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="estilo-index.css?v=<?= time() ?>" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Conciertos y Festivales</h1>
        <div class="iconHeader">
            <a href="views/logIn.php" class="iconLogIn">
                <img src="Imagenes/login.png" alt="login">
            </a>
        </div>
    </header>
    
    <section class="events">
        <?php if ($eventos && count($eventos) > 0): ?>
            <?php foreach ($eventos as $evento): ?>
                <div class="cardEvent">
                    <img src="Imagenes/<?= htmlspecialchars($evento->imagen ?? 'missing.png') ?>" alt="<?= htmlspecialchars($evento->nombre) ?>">
                    <h3><?= htmlspecialchars($evento->nombre) ?></h3>
                    <p>
                        <?= htmlspecialchars($evento->fInicio) ?>
                        <?php if (!empty($evento->fFinal)): ?>
                            - <?= htmlspecialchars($evento->fFinal) ?>
                        <?php endif; ?>
                    </p>
                    <p><?= htmlspecialchars($evento->lugar) ?></p>
                    <a href="views/editEvent.php?evento=<?= urlencode($evento->nombre) ?>">Editar evento</a>
                    <a href="indexActivity.php?evento=<?= urlencode($evento->nombre) ?>">Lista actividades</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay eventos disponibles.</p>
        <?php endif; ?>
    </section>

    <div class="contenedorCrearEvento">
        <a href="views/createEvent.php" class="botonCrearEvento">Crear nuevo evento</a>
    </div>
    
    <footer>
        <div id="left">
            <a href="connectionUserPromoter.php" class="footer-link">Mensajes de los usuarios</a>
        </div>
        <div id="center">
            <p>&copy; 2024 Conciertos y Festivales. Todos los derechos reservados.</p>
        </div>
        <div id="right">
            <div class="redesSociales">
                <a href="#"><img src="Imagenes/facebook.png" alt="Facebook" id="facebook"></a>
                <a href="#"><img src="Imagenes/instagram.png" alt="Instagram" id="instagram"></a>
                <a href="#"><img src="Imagenes/twitter.png" alt="Twitter" id="x"></a>
            </div>
        </div>
    </footer>
</body>
</html>