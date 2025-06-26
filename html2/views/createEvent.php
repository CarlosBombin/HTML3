<?php
session_start();
require_once __DIR__ . '/../controllers/EventController.php';

$mensaje = '';
$eventController = new EventController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_evento'])) {
    if (!empty($_SESSION['email'])) {
        $mensaje = $eventController->processCreateEvent($_POST, $_SESSION['email']);
    } else {
        $mensaje = 'Debes iniciar sesión para crear un evento.';
    }
}

$tipos = $eventController->getTiposDeEventos();
$tipoSeleccionado = $_POST['idTipoEvento'] ?? '';

$numActividades = 1;
if (isset($_POST['num_actividades'])) {
    $numActividades = max(1, (int)$_POST['num_actividades']);
}
if (isset($_POST['add_actividad'])) {
    $numActividades++;
}
if (isset($_POST['remove_actividad']) && $numActividades > 1) {
    $numActividades--;
}

$actividades = $_POST['actividades'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento</title>
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
        <h2>Formulario de creación de eventos</h2>
        <?php if ($mensaje): ?>
            <div style="color:<?= $mensaje === 'Evento creado correctamente.' ? 'green' : 'red' ?>;margin-bottom:15px;">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="form" id="eventForm">
            <div class="groupForm">
                <label for="nombre">Nombre del evento</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="fechaInicial">Fecha de comienzo del evento</label>
                <input type="date" id="fechaInicial" name="fechaInicial" value="<?= htmlspecialchars($_POST['fechaInicial'] ?? '') ?>" >
            </div>
            <div class="groupForm">
                <label for="fechaFinal">Fecha de cuando acaba el evento</label>
                <input type="date" id="fechaFinal" name="fechaFinal" value="<?= htmlspecialchars($_POST['fechaFinal'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="idTipoEvento">¿Qué tipo de evento es?</label>
                <select name="idTipoEvento" id="idTipoEvento">
                    <option value="">Selecciona un tipo</option>
                    <?php foreach ($tipos as $id => $tipo): ?>
                        <option value="<?= $id ?>" <?= ($tipoSeleccionado == $id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tipo) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="groupForm">
                <label for="informacionEvento">Información sobre el evento</label>
                <input type="text" id="informacionEvento" name="informacionEvento" value="<?= htmlspecialchars($_POST['informacionEvento'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="lugar">Lugar del evento</label>
                <input type="text" id="lugar" name="lugar" value="<?= htmlspecialchars($_POST['lugar'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="costePorPlaza">Coste por plaza del evento</label>
                <input type="number" id="costePorPlaza" name="costePorPlaza" value="<?= htmlspecialchars($_POST['costePorPlaza'] ?? '') ?>" step="any" required>
            </div>

            <h3>Actividades del evento</h3>
            <?php for ($i = 0; $i < $numActividades; $i++): ?>
                <fieldset style="margin-bottom:15px; border:1px solid #ccc; padding:10px;">
                    <legend>Actividad <?= $i + 1 ?></legend>
                    <div class="groupForm">
                        <label>Nombre</label>
                        <input type="text" name="actividades[<?= $i ?>][nombre]" value="<?= htmlspecialchars($actividades[$i]['nombre'] ?? '') ?>">
                    </div>
                    <div class="groupForm">
                        <label>Descripción</label>
                        <input type="text" name="actividades[<?= $i ?>][descripcion]" value="<?= htmlspecialchars($actividades[$i]['descripcion'] ?? '') ?>">
                    </div>
                    <div class="groupForm">
                        <label>Plazas</label>
                        <input type="number" name="actividades[<?= $i ?>][plazas]" value="<?= htmlspecialchars($actividades[$i]['plazas'] ?? '') ?>" min="1">
                    </div>
                    <div class="groupForm">
                        <label>Lugar</label>
                        <input type="text" name="actividades[<?= $i ?>][lugar]" value="<?= htmlspecialchars($actividades[$i]['lugar'] ?? '') ?>">
                    </div>
                    <div class="groupForm">
                        <label>Fecha</label>
                        <input type="date" name="actividades[<?= $i ?>][fecha]" value="<?= htmlspecialchars($actividades[$i]['fecha'] ?? '') ?>">
                    </div>
                </fieldset>
            <?php endfor; ?>

            <input type="hidden" name="num_actividades" value="<?= $numActividades ?>">

            <div class="groupForm" style="display:flex;gap:10px;">
                <button type="submit" name="add_actividad" value="1">Añadir otra actividad</button>
                <?php if ($numActividades > 1): ?>
                    <button type="submit" name="remove_actividad" value="1">Quitar última actividad</button>
                <?php endif; ?>
            </div>

            <div class="groupForm">
                <button type="submit" name="crear_evento" value="1">Crear Evento</button>
            </div>
        </form>
    </div>
    
    <footer>
        <div id="center">
            <p>&copy; 2023 Conciertos y Festivales. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>