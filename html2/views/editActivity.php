<?php
session_start();
require_once __DIR__ . '/../controllers/ActivityController.php';
require_once __DIR__ . '/../controllers/EventController.php';

$mensaje = '';
$activityController = new ActivityController();
$eventController = new EventController();

$nombreActividad = $_GET['actividad'] ?? '';
$nombreEvento = $_GET['evento'] ?? '';
$actividad = $activityController->getByNombre($nombreActividad, $nombreEvento);

if (!$actividad) {
    $mensaje = 'Actividad no encontrada.';
} else {
    $evento = $eventController->getById($actividad->idEvento);
    $todasActividades = $activityController->getByEvento($actividad->idEvento);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_actividad'])) {
        $mensaje = $activityController->processEditActivity($actividad, $_POST, $todasActividades);
        if ($mensaje === "Actividad editada correctamente.") {
            $actividad = $activityController->getById($actividad->id);
            $todasActividades = $activityController->getByEvento($actividad->idEvento);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_actividad'])) {
        $mensaje = $activityController->processDeleteActivity($actividad, $todasActividades);
        if ($mensaje === "Actividad eliminada correctamente.") {
            $actividad = null;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Actividad</title>
    <link rel="stylesheet" href="../estilo-index.css?v=<?= time() ?>">
</head>
<body>
    <header>
        <h1>Editar Actividad<?= isset($evento) && $evento ? ' de ' . htmlspecialchars($evento->nombre) : '' ?></h1>
        <div class="iconHeader">
            <a href="../index.php" class="iconLogIn">
                <img src="../Imagenes/home.png" alt="Volver al inicio">
            </a>
        </div>
    </header>
    <div class="containerForm">
        <h2>Formulario de edición de actividad</h2>
        <?php if ($mensaje): ?>
            <div style="color:<?= strpos($mensaje, 'correctamente') !== false ? 'green' : 'red' ?>;margin-bottom:15px;">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>
        <?php if ($actividad): ?>
        <form action="" method="POST" class="form" id="activityForm">
            <fieldset style="margin-bottom:15px; border:1px solid #ccc; padding:10px;">
                <legend>Editar Actividad</legend>
                <div class="groupForm">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? $actividad->nombre) ?>">
                </div>
                <div class="groupForm">
                    <label>Descripción</label>
                    <input type="text" name="descripcion" value="<?= htmlspecialchars($_POST['descripcion'] ?? $actividad->descripcion) ?>">
                </div>
                <div class="groupForm">
                    <label>Plazas</label>
                    <input type="number" name="plazas" value="<?= htmlspecialchars($_POST['plazas'] ?? $actividad->plazas) ?>" min="1">
                </div>
                <div class="groupForm">
                    <label>Lugar</label>
                    <input type="text" name="lugar" value="<?= htmlspecialchars($_POST['lugar'] ?? $actividad->lugar) ?>">
                </div>
                <div class="groupForm">
                    <label>Fecha</label>
                    <input type="date" name="fecha" value="<?= htmlspecialchars($_POST['fecha'] ?? $actividad->fecha) ?>">
                </div>
            </fieldset>
            <div class="groupForm">
                <button type="submit" name="editar_actividad" value="1">Guardar Cambios</button>
            </div>
        </form>
        <?php if (count($todasActividades) > 1): ?>
        <form action="" method="POST" class="form" id="deleteForm" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta actividad? Esta acción no se puede deshacer.');">
            <div class="groupForm">
                <button type="submit" name="eliminar_actividad" value="1" style="background-color:red;color:white;">Eliminar Actividad</button>
            </div>
        </form>
        <?php endif; ?>
        <?php endif; ?>
    </div>
    <footer>
        <div id="center">
            <p>&copy; 2023 Conciertos y Festivales. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>