<?php
session_start();

if (!isset($_SESSION['rol'])) {
    include 'views/index.php';
} elseif ($_SESSION['rol'] === 'promoter') {
    include 'views/indexPromotor.php';
} elseif ($_SESSION['rol'] === 'admin') {
    include 'views/indexAdmin.php';
} elseif ($_SESSION['rol'] === 'user') {
    include 'views/indexUser.php';
}
?>