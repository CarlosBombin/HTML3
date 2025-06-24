<?php
require_once __DIR__ . '/../controllers/EventController.php';

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $eventController = new EventController();
        $data = [
            'nombre_evento' => $nombre,
            'descripcion' => $informacionEvento,
            'tipo_evento' => $tipoEvento,
            'plazas' => (int)$plazas,
            'lugar' => $lugar,
            'fecha_inicio' => $fechaInicial,
            'fecha_fin' => $fechaFinal,
            'coste_por_plaza' => (float)$costePorPlaza,
            'actividades' => []
        ];
        if ($eventController->create($data)) {
            $mensaje = 'Evento creado correctamente.';
        } else {
            $mensaje = 'Ya existe un evento con ese nombre.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento</title>
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
                <input type="date" id="fechaInicial" name="fechaInicial" value="<?= htmlspecialchars($_POST['fechaInicial'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="fechaFinal">Fecha de cuando acaba el evento</label>
                <input type="date" id="fechaFinal" name="fechaFinal" value="<?= htmlspecialchars($_POST['fechaFinal'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="tipoEvento">¿Qué tipo de evento es?</label>
                <select name="tipoEvento" id="tipoEvento">
                    <option value="Concierto" <?= (isset($_POST['tipoEvento']) && $_POST['tipoEvento'] == 'Concierto') ? 'selected' : '' ?>>Concierto</option>
                    <option value="Cine" <?= (isset($_POST['tipoEvento']) && $_POST['tipoEvento'] == 'Cine') ? 'selected' : '' ?>>Cine</option>
                    <option value="PruebaDeportiva" <?= (isset($_POST['tipoEvento']) && $_POST['tipoEvento'] == 'PruebaDeportiva') ? 'selected' : '' ?>>Prueba Deportiva</option>
                    <option value="Otro" <?= (isset($_POST['tipoEvento']) && $_POST['tipoEvento'] == 'Otro') ? 'selected' : '' ?>>Otro</option>
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
                <label for="plazas">Plazas totales del evento</label>
                <input type="number" id="plazas" name="plazas" value="<?= htmlspecialchars($_POST['plazas'] ?? '') ?>">
            </div>
            <div class="groupForm">
                <label for="costePorPlaza">Coste por plaza del evento</label>
                <input type="number" id="costePorPlaza" name="costePorPlaza" value="<?= htmlspecialchars($_POST['costePorPlaza'] ?? ($evento['coste_por_plaza'] ?? '')) ?>">
            </div>
            <div class="groupForm">
                <button type="submit">Crear Evento</button>
            </div>
        </form>
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
                var name = $('#nombre').val();
                var fIni = $('#fechaInicial').val();
                var fFin = $('#fechaFinal').val();                
                var tEvent = $('#tipoEvento').val();
                var iEvent = $('#informacionEvento').val();
                var place = $('#lugar').val();
                var seats = $('#plazas').val();
                var cost = $('#costePorPlaza').val();
                var isError = false;

                if (name === '') {
                    alert('Se ha de introducir un nombre.');
                    $('#nombre').addClass('errorField');
                    isError = true;
                }

                if (fIni === '') {
                    alert('Se ha de introducir una fecha de inicio.');
                    $('#fechaInicial').addClass('errorField');
                    isError = true;
                }

                if (fFin === '') {
                    alert('Se ha de introducir fecha de finalización.');
                    $('#fechaFinal').addClass('errorField');
                    isError = true;
                }

                if (fIni > fFin) {
                    alert('La fecha de inicio no puede ser posterior a la fecha de finalización.');
                    $('#fechaInicial').addClass('errorField');
                    $('#fechaFinal').addClass('errorField');
                    isError = true;
                }

                if (iEvent === '') {
                    alert('Se ha de introducir la información del evento.');
                    $('#informacionEvento').addClass('errorField');
                    isError = true;
                }

                if (place === '') {
                    alert('Se ha de introducir el lugar del evento.');
                    $('#lugar').addClass('errorField');
                    isError = true;
                }

                if (seats === '') {
                    alert('Se ha de introducir el número de plazas del evento.');
                    $('#plazas').addClass('errorField');
                    isError = true;
                }

                if (seats < 100) {
                    alert('No se pueden crear eventos con menos de 100 plazas.');
                    $('#plazas').addClass('errorField');
                    isError = true;
                }

                if (cost === '') {
                    alert('Se ha de introducir el coste por plaza del evento.');
                    $('#costePorPlaza').addClass('errorField');
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