<?php
require_once __DIR__ . '/../controllers/ActivityController.php';
require_once __DIR__ . '/../controllers/EventController.php';

$nombreEvento = $_GET['evento'] ?? '';
$eventController = new EventController();
$evento = $eventController->getByNombre($nombreEvento);

$actividades = [];
if ($evento) {
    $activityController = new ActivityController();
    $actividades = $activityController->getByEvento($evento->id);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades de <?= htmlspecialchars($evento ? $evento->nombre : '') ?></title>
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

    <section class="activities">
        <?php if ($actividades && count($actividades) > 0): ?>
            <?php foreach ($actividades as $actividad): ?>
                <div class="cardActivities">
                    <img src="Imagenes/<?= htmlspecialchars($evento->imagen ?? 'missing.png') ?>" alt="<?= htmlspecialchars($evento->nombre) ?>">
                    <h3><?= htmlspecialchars($actividad->nombre) ?></h3>
                    <p><?= htmlspecialchars($actividad->fecha) ?></p>
                    <p>Plazas totales: <?= htmlspecialchars($actividad->plazas) ?></p>
                    <p><?= htmlspecialchars($actividad->lugar) ?></p>
                    <a href="views/editActivity.php?actividad=<?= urlencode($actividad->nombre) ?>&evento=<?= urlencode($evento->nombre) ?>">Editar actividad</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay actividades para este evento.</p>
        <?php endif; ?>
    </section>

    <div class="contenedorCrearEvento">
        <a href="views/createActivity.php?evento=<?= urlencode($evento->nombre) ?>" class="botonCrearEvento">Crear nueva actividad</a>
    </div>

    <footer>
        <div id="center">
            <p>&copy; 2024 Conciertos y Festivales. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>