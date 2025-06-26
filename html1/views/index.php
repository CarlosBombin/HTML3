<?php
require_once __DIR__ . '/../controllers/EventController.php';

$eventController = new EventController();
$eventos = $eventController->getAll();
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
        <a href="logIn.php" class="iconLogIn">
            <img src="Imagenes/Login.png" alt="login">
        </a>
    </header>

    <section class="hero">
        <div class="textHero">
            <h2>Descubre los mejores eventos musicales cerca de ti</h2>
        </div>
    </section>
    
    <section class="events">
        <?php if ($eventos && count($eventos) > 0): ?>
            <?php foreach ($eventos as $evento): ?>
                <div class="cardEvent">
                    <img src="Imagenes/<?= htmlspecialchars($evento['imagen'] ?? 'missing.png') ?>" alt="<?= htmlspecialchars($evento['nombre_evento']) ?>">
                    <h3><?= htmlspecialchars($evento['nombre_evento']) ?></h3>
                    <p>
                        <?= htmlspecialchars($evento['fecha_inicio']) ?>
                        <?php if (!empty($evento['fecha_fin'])): ?>
                            - <?= htmlspecialchars($evento['fecha_fin']) ?>
                        <?php endif; ?>
                    </p>
                    <p><?= htmlspecialchars($evento['lugar']) ?></p>
                    <a href="signup.php?evento=<?= urlencode($evento['nombre_evento']) ?>">Regístrate para participar</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay eventos disponibles.</p>
        <?php endif; ?>
    </section>

    <footer>
        <div id="left">
            <a href="#" class="footer-link">¿Tiene problemas?</a>
        </div>
        <div id="center">
            <p>&copy; 2023 Conciertos y Festivales. Todos los derechos reservados.</p>
        </div>
        <div id="right">
            <div class="redesSociales">
                <a href="#"><img src="Imagenes/facebook.png" alt="Facebook" id="facebook"></a>
                <a href="#"><img src="Imagenes/instagram.png" alt="Instagram" id="instagram"></a>
                <a href="#"><img src="Imagenes/twitter.png" alt="Twitter" id="x"></a>
            </div>
        </div>