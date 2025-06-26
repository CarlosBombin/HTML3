<?php
require_once __DIR__ . '/../Datos/Database.php';
require_once __DIR__ . '/../models/Request.php';

class RequestController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function createRequest($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE usuarios_id = (SELECT id FROM usuarios WHERE email = ?)");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            return 'Ya has solicitado el cambio de rol a promotor.';
        }

        $stmt = $this->pdo->prepare("INSERT INTO pedidos (usuarios_id) VALUES ((SELECT id FROM usuarios WHERE email = ?))");
        $stmt->execute([$email]);
        return 'Solicitud enviada correctamente.';
    }

    public function deleteRequest($email) {
        $stmt = $this->pdo->prepare("DELETE FROM pedidos WHERE usuarios_id = (SELECT id FROM usuarios WHERE email = ?)");
        $stmt->execute([$email]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM pedidos");
        return $stmt->fetchAll();
    }
}
?>