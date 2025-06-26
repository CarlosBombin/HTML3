<?php

require_once __DIR__ . '/../Datos/Database.php';
require_once __DIR__ . '/../models/Activity.php';

class ActivityController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO actividades (nombre, descripcion, orden, plazas, lugar, fecha, idEvento)
                VALUES (:nombre, :descripcion, :orden, :plazas, :lugar, :fecha, :idEvento)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':orden' => $data['orden'],
            ':plazas' => $data['plazas'],
            ':lugar' => $data['lugar'],
            ':fecha' => $data['fecha'],
            ':idEvento' => $data['idEvento']
        ]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM actividades");
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = new Activity($row);
        }
        return $result;
    }

    public function getByEvento($idEvento) {
        $stmt = $this->pdo->prepare("SELECT * FROM actividades WHERE idEvento = ?");
        $stmt->execute([$idEvento]);
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = new Activity($row);
        }
        return $result;
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM actividades WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new Activity($row) : null;
    }

    public function update($id, $data) {
        $sql = "UPDATE actividades SET nombre = :nombre, descripcion = :descripcion, orden = :orden, plazas = :plazas, lugar = :lugar, fecha = :fecha, idEvento = :idEvento WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':orden' => $data['orden'],
            ':plazas' => $data['plazas'],
            ':lugar' => $data['lugar'],
            ':fecha' => $data['fecha'],
            ':idEvento' => $data['idEvento'],
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM actividades WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function processCreateActivity($post, $evento) {
        $actividad = $post['actividad'] ?? [
            'nombre' => '',
            'descripcion' => '',
            'plazas' => '',
            'lugar' => '',
            'fecha' => ''
        ];

        $nombre = trim($actividad['nombre'] ?? '');
        $descripcion = trim($actividad['descripcion'] ?? '');
        $plazas = (int)($actividad['plazas'] ?? 0);
        $lugar = trim($actividad['lugar'] ?? '');
        $fecha = $actividad['fecha'] ?? '';

        if ($nombre === '' || $descripcion === '' || $plazas <= 0 || $lugar === '' || $fecha === '') {
            return "Todos los campos son obligatorios y las plazas deben ser mayores que 0.";
        }

        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'orden' => 1,
            'plazas' => $plazas,
            'lugar' => $lugar,
            'fecha' => $fecha,
            'idEvento' => $evento->id
        ];
        $this->create($data);
        return 'Actividad creada correctamente.';
    }

    public function getByNombre($nombre) {
        $stmt = $this->pdo->prepare("SELECT * FROM actividades WHERE nombre = ?");
        $stmt->execute([$nombre]);
        $row = $stmt->fetch();
        return $row ? new Activity($row) : null;
    }

    public function processEditActivity($actividad, $post, $todasActividades) {
        $mensaje = '';
        $nuevaActividad = [
            'nombre' => trim($post['nombre'] ?? ''),
            'descripcion' => trim($post['descripcion'] ?? ''),
            'plazas' => (int)($post['plazas'] ?? 0),
            'lugar' => trim($post['lugar'] ?? ''),
            'fecha' => $post['fecha'] ?? '',
            'orden' => $actividad->orden,
            'idEvento' => $actividad->idEvento
        ];

        if (
            $nuevaActividad['nombre'] === '' ||
            $nuevaActividad['descripcion'] === '' ||
            $nuevaActividad['plazas'] <= 0 ||
            $nuevaActividad['lugar'] === '' ||
            $nuevaActividad['fecha'] === ''
        ) {
            return "Todos los campos son obligatorios y las plazas deben ser mayores que 0.";
        }

        $sumaPlazas = 0;
        foreach ($todasActividades as $act) {
            if ($act->id == $actividad->id) {
                $sumaPlazas += $nuevaActividad['plazas'];
            } else {
                $sumaPlazas += $act->plazas;
            }
        }
        if ($sumaPlazas < 100) {
            return "La suma total de plazas de todas las actividades del evento debe ser al menos 100. Actualmente: $sumaPlazas";
        }

        $this->update($actividad->id, $nuevaActividad);
        return "Actividad editada correctamente.";
    }

    public function processDeleteActivity($actividad, $todasActividades) {
        if (count($todasActividades) <= 1) {
            return "No puedes eliminar la única actividad del evento.";
        }
        $sumaPlazas = 0;
        foreach ($todasActividades as $act) {
            if ($act->id != $actividad->id) {
                $sumaPlazas += $act->plazas;
            }
        }
        if ($sumaPlazas < 100) {
            return "No puedes eliminar esta actividad porque la suma de plazas del evento quedaría por debajo de 100 (quedaría en $sumaPlazas).";
        }
        $this->delete($actividad->id);
        return "Actividad eliminada correctamente.";
    }
}
?>