<?php
require_once __DIR__ . '/../models/Request.php';

class RequestController {
    private $file = __DIR__ . '/../Datos/Request.json';

    private function readRequests() {
        if (!file_exists($this->file)) return [];
        $json = file_get_contents($this->file);
        return json_decode($json, true) ?? [];
    }

    private function writeRequests($requests) {
        file_put_contents($this->file, json_encode($requests, JSON_PRETTY_PRINT));
    }

    public function createRequest($email) {
        $requests = $this->readRequests();

        // Evitar solicitudes duplicadas
        foreach ($requests as $req) {
            if (isset($req['email']) && $req['email'] === $email) {
                return 'Ya has solicitado el cambio de rol a promotor.';
            }
        }

        $requests[] = ['email' => $email];
        $this->writeRequests($requests);
        return 'Solicitud enviada correctamente.';
    }

    public function deleteRequest($email) {
        $requests = $this->readRequests();
        $requests = array_filter($requests, function($req) use ($email) {
            return $req['email'] !== $email;
        });
        $this->writeRequests(array_values($requests));
    }

    public function getAll() {
        return $this->readRequests();
    }
}
?>