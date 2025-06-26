<?php
session_start();

if (!isset($_SESSION['idRol'])) {
    header ('Location: Index.php');
} elseif ($_SESSION['idRol'] === 2) {
    include 'views/indexActivityPromotor.php';
} elseif ($_SESSION['idRol'] === 1) {
    include 'views/indexActivityUser.php';
} else {
    include 'views/indexActivityUser.php';
}
?>