<?php
require_once __DIR__ . '/../controllers/EventController.php';

$mensaje = '';
$eventController = new EventController();
$tipos = $eventController->getTiposDeEventos();

$nombreEvento = $_GET['evento'] ?? $_POST['nombre_original'] ?? '';
$evento = $eventController->getByNombre($nombreEvento);
$plazasTotales = $evento ? $eventController->getPlazasTotalesPorEvento($evento->id) : 0;

if (!$evento) {
    $mensaje = "Evento no encontrado.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        $result = $eventController->processDeleteEvent($nombreEvento, $_POST);
        $mensaje = $result['message'];
        if ($result['success']) {
            header('Location: ../Index.php');
            exit;
        }
    } else {
        $result = $eventController->processEditEvent($nombreEvento, $_POST);
        $mensaje = $result['message'];
        if ($result['success']) {
            $evento = $result['evento'];
            $plazasTotales = $eventController->getPlazasTotalesPorEvento($evento->id);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="../estilo-index.css?v=<?= time() ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
        <h2>Formulario de edición de eventos</h2>
        <?php if ($mensaje): ?>
            <div style="color:<?= $mensaje === 'Evento actualizado correctamente.' ? 'green' : 'red' ?>;margin-bottom:15px;">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>
        <?php if ($evento): ?>
        <form action="" method="POST" class="form" id="eventForm">
            <input type="hidden" name="nombre_original" value="<?= htmlspecialchars($evento->nombre) ?>">
            <div class="groupForm">
                <label for="nombre">Nombre del evento</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? $evento->nombre) ?>">
            </div>
            <div class="groupForm">
                <label for="fechaInicial">Fecha de comienzo del evento</label>
                <input type="date" id="fechaInicial" name="fechaInicial" value="<?= htmlspecialchars($_POST['fechaInicial'] ?? $evento->fInicio) ?>">
            </div>
            <div class="groupForm">
                <label for="fechaFinal">Fecha de cuando acaba el evento</label>
                <input type="date" id="fechaFinal" name="fechaFinal" value="<?= htmlspecialchars($_POST['fechaFinal'] ?? $evento->fFinal) ?>">
            </div>
            <div class="groupForm">
                <label for="idTipoEvento">¿Qué tipo de evento es?</label>
                <select name="idTipoEvento" id="idTipoEvento">
                    <option value="">Selecciona un tipo</option>
                    <?php foreach ($tipos as $id => $tipo): ?>
                        <option value="<?= $id ?>" <?= (($_POST['idTipoEvento'] ?? $evento->idTipoEvento) == $id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tipo) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="groupForm">
                <label for="informacionEvento">Información sobre el evento</label>
                <input type="text" id="informacionEvento" name="informacionEvento" value="<?= htmlspecialchars($_POST['informacionEvento'] ?? $evento->descripcion) ?>">
            </div>
            <div class="groupForm">
                <label for="lugar">Lugar del evento</label>
                <input type="text" id="lugar" name="lugar" value="<?= htmlspecialchars($_POST['lugar'] ?? $evento->lugar) ?>">
            </div>
            <div class="groupForm">
                <label for="plazas">Plazas totales del evento</label>
                <input type="number" id="plazas" name="plazas" value="<?= htmlspecialchars($plazasTotales) ?>" readonly>
            </div>
            <div class="groupForm">
                <label for="costePorPlaza">Coste por plaza del evento</label>
                <input type="number" id="costePorPlaza" name="costePorPlaza" value="<?= htmlspecialchars($_POST['costePorPlaza'] ?? $evento->precio) ?>" step="any">
            </div>
            <div class="groupForm">
                <button type="submit">Editar Evento</button>
            </div>
        </form>
        <div class="groupForm">
            <form action="" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este evento?');">
                <input type="hidden" name="nombre_original" value="<?= htmlspecialchars($evento->nombre) ?>">
                <button type="submit" name="eliminar" value="1" class="botonEliminarEvento" style="background-color:red;color:white;">Eliminar Evento</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    
    <footer>
        <div id="center">
            <p>&copy; 2023 Conciertos y Festivales. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>