<?php
require_once __DIR__ . '/../controllers/EventController.php';

$mensaje = '';
$eventController = new EventController();

// Obtener el nombre del evento por GET o POST
$nombreEvento = $_GET['evento'] ?? $_POST['nombre_original'] ?? '';
$evento = $eventController->getByNombre($nombreEvento);

if (!$evento) {
    $mensaje = "Evento no encontrado.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $fechaInicial = $_POST['fechaInicial'] ?? '';
    $fechaFinal = $_POST['fechaFinal'] ?? '';
    $tipoEvento = $_POST['tipoEvento'] ?? '';
    $informacionEvento = trim($_POST['informacionEvento'] ?? '');
    $lugar = trim($_POST['lugar'] ?? '');
    $plazas = $_POST['plazas'] ?? '';
    $costePorPlaza = $_POST['costePorPlaza'] ?? '';

    $hoy = date('Y-m-d');

    if ($nombre === '' || $fechaInicial === '' || $fechaFinal === '' || $tipoEvento === '' ||
        $informacionEvento === '' || $lugar === '' || $plazas === '' || $costePorPlaza === '') {
        $mensaje = 'Todos los campos son obligatorios.';
    } elseif ($fechaInicial < $hoy) {
        $mensaje = 'La fecha de inicio debe ser mayor o igual a hoy.';
    } elseif ($fechaInicial > $fechaFinal) {
        $mensaje = 'La fecha de inicio no puede ser posterior a la fecha de finalización.';
    } elseif ($plazas < 100) {
        $mensaje = 'No se pueden crear eventos con menos de 100 plazas.';
    } else {
        $updateData = [
            'nombre_evento' => $nombre,
            'descripcion' => $informacionEvento,
            'tipo_evento' => $tipoEvento,
            'plazas' => (int)$plazas,
            'lugar' => $lugar,
            'fecha_inicio' => $fechaInicial,
            'fecha_fin' => $fechaFinal,
            'coste_por_plaza' => (float)$costePorPlaza
        ];
        if ($eventController->update($nombreEvento, $updateData)) {
            $mensaje = 'Evento actualizado correctamente.';
            $evento = $eventController->getByNombre($nombre);
        } else {
            $mensaje = 'No se pudo actualizar el evento.';
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
            <input type="hidden" name="nombre_original" value="<?= htmlspecialchars($evento['nombre_evento']) ?>">
            <div class="groupForm">
                <label for="nombre">Nombre del evento</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? $evento['nombre_evento']) ?>">
            </div>
            <div class="groupForm">
                <label for="fechaInicial">Fecha de comienzo del evento</label>
                <input type="date" id="fechaInicial" name="fechaInicial" value="<?= htmlspecialchars($_POST['fechaInicial'] ?? ($evento['fecha_inicio'] ?? '')) ?>">
            </div>
            <div class="groupForm">
                <label for="fechaFinal">Fecha de cuando acaba el evento</label>
                <input type="date" id="fechaFinal" name="fechaFinal" value="<?= htmlspecialchars($_POST['fechaFinal'] ?? ($evento['fecha_fin'] ?? '')) ?>">
            </div>
            <div class="groupForm">
                <label for="tipoEvento">¿Qué tipo de evento es?</label>
                <select name="tipoEvento" id="tipoEvento">
                    <option value="Concierto" <?= (($_POST['tipoEvento'] ?? $evento['tipo_evento']) == 'Concierto') ? 'selected' : '' ?>>Concierto</option>
                    <option value="Cine" <?= (($_POST['tipoEvento'] ?? $evento['tipo_evento']) == 'Cine') ? 'selected' : '' ?>>Cine</option>
                    <option value="PruebaDeportiva" <?= (($_POST['tipoEvento'] ?? $evento['tipo_evento']) == 'PruebaDeportiva') ? 'selected' : '' ?>>Prueba Deportiva</option>
                    <option value="Otro" <?= (($_POST['tipoEvento'] ?? $evento['tipo_evento']) == 'Otro') ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>
            <div class="groupForm">
                <label for="informacionEvento">Información sobre el evento</label>
                <input type="text" id="informacionEvento" name="informacionEvento" value="<?= htmlspecialchars($_POST['informacionEvento'] ?? $evento['descripcion']) ?>">
            </div>
            <div class="groupForm">
                <label for="lugar">Lugar del evento</label>
                <input type="text" id="lugar" name="lugar" value="<?= htmlspecialchars($_POST['lugar'] ?? $evento['lugar']) ?>">
            </div>
            <div class="groupForm">
                <label for="plazas">Plazas totales del evento</label>
                <input type="number" id="plazas" name="plazas" value="<?= htmlspecialchars($_POST['plazas'] ?? $evento['plazas']) ?>">
            </div>
            <div class="groupForm">
                <label for="costePorPlaza">Coste por plaza del evento</label>
                <input type="number" id="costePorPlaza" name="costePorPlaza" value="<?= htmlspecialchars($_POST['costePorPlaza'] ?? ($evento['coste_por_plaza'] ?? '')) ?>">
            </div>
            <div class="groupForm">
                <button type="submit">Editar Evento</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
    
    <footer>
        <div id="center">
            <p>&copy; 2023 Conciertos y Festivales. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            $('#eventForm').on('submit', function(event) {
                $('input').removeClass('errorField');
                var fIni = $('#fechaInicial').val();
                var fFin = $('#fechaFinal').val();
                var seats = $('#plazas').val();
                var isError = false;

                if (fIni > fFin) {
                    alert('La fecha de inicio no puede ser posterior a la fecha de finalización.');
                    $('#fechaInicial').addClass('errorField');
                    $('#fechaFinal').addClass('errorField');
                    isError = true;
                }

                if (seats < 100) {
                    alert('No se pueden crear eventos con menos de 100 plazas.');
                    $('#plazas').addClass('errorField');
                    isError = true;
                }

                if (isError) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>