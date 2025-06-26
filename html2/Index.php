<?php
session_start();

if (!isset($_SESSION['idRol'])) {
    include 'views/index.php';
} elseif ($_SESSION['idRol'] === 2) {
    include 'views/indexPromotor.php';
} elseif ($_SESSION['idRol'] === 3) {
    include 'views/indexAdmin.php';
} elseif ($_SESSION['idRol'] === 1) {
    include 'views/indexUser.php';
} else {
    include 'views/index.php';
}
?>