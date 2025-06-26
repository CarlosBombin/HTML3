<?php
session_start();
require_once __DIR__ . '/../controllers/ActivityController.php';
require_once __DIR__ . '/../controllers/EventController.php';

$mensaje = '';
$activityController = new ActivityController();
$eventController = new EventController();

$nombreEvento = $_GET['evento'] ?? '';
$evento = $eventController->getByNombre($nombreEvento);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_actividad']) && $evento) {
    $mensaje = $activityController->processCreateActivity($_POST, $evento);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Actividad</title>
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
        <h2>Formulario de creación de actividad</h2>
        <?php if ($mensaje): ?>
            <div style="color:<?= strpos($mensaje, 'correctamente') !== false ? 'green' : 'red' ?>;margin-bottom:15px;">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>
        <?php if ($evento): ?>
        <form action="" method="POST" class="form" id="activityForm">
            <fieldset style="margin-bottom:15px; border:1px solid #ccc; padding:10px;">
                <legend>Nueva Actividad</legend>
                <div class="groupForm">
                    <label>Nombre</label>
                    <input type="text" name="actividad[nombre]">
                </div>
                <div class="groupForm">
                    <label>Descripción</label>
                    <input type="text" name="actividad[descripcion]">
                </div>
                <div class="groupForm">
                    <label>Plazas</label>
                    <input type="number" name="actividad[plazas]">
                </div>
                <div class="groupForm">
                    <label>Lugar</label>
                    <input type="text" name="actividad[lugar]">
                </div>
                <div class="groupForm">
                    <label>Fecha</label>
                    <input type="date" name="actividad[fecha]">
                </div>
            </fieldset>
            <div class="groupForm">
                <button type="submit" name="crear_actividad" value="1">Crear Actividad</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
    <footer>
        <div id="center">
            <p>&copy; 2023 Conciertos y Festivales. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>