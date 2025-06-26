<?php
require_once __DIR__ . '/../Datos/Database.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/UserController.php';

class EventController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create($data) {
        $sql = "INSERT INTO eventos (nombre, descripcion, idTipoEvento, plazas, precio, lugar, fInicio, fFinal, usuarios_id, imagen)
                VALUES (:nombre, :descripcion, :idTipoEvento, :plazas, :precio, :lugar, :fInicio, :fFinal, :usuarios_id, :imagen)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':idTipoEvento' => $data['idTipoEvento'],
            ':plazas' => $data['plazas'],
            ':precio' => $data['precio'],
            ':lugar' => $data['lugar'],
            ':fInicio' => $data['fInicio'],
            ':fFinal' => $data['fFinal'],
            ':usuarios_id' => $data['usuarios_id'],
            ':imagen' => $data['imagen']
        ]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM eventos");
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = new Event($row);
        }
        return $result;
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM eventos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new Event($row) : null;
    }

    public function getByNombre($nombre) {
        $stmt = $this->pdo->prepare("SELECT * FROM eventos WHERE nombre = ?");
        $stmt->execute([$nombre]);
        $row = $stmt->fetch();
        return $row ? new Event($row) : null;
    }

    public function update($id, $data) {
        $set = [];
        $params = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;
        $sql = "UPDATE eventos SET " . implode(', ', $set) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM eventos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function processCreateEvent($post, $email) {
        $nombre = trim($post['nombre'] ?? '');
        $fechaInicial = $post['fechaInicial'] ?? '';
        $fechaFinal = $post['fechaFinal'] ?? '';
        $idTipoEvento = $post['idTipoEvento'] ?? null;
        $informacionEvento = trim($post['informacionEvento'] ?? '');
        $lugar = trim($post['lugar'] ?? '');
        $precio = $post['costePorPlaza'] ?? '';
        $hoy = date('Y-m-d');
        $userController = new UserController();
        $usuarios_id = $userController->getIdByEmail($email);

        if (!$usuarios_id) {
            return 'El usuario no existe. No se puede crear el evento.';
        }
        if ($nombre === '' || $fechaInicial === '' || $fechaFinal === '' || !$idTipoEvento ||
            $informacionEvento === '' || $lugar === '' || $precio === '') {
            return 'Todos los campos del evento son obligatorios.';
        } elseif ($fechaInicial < $hoy) {
            return 'La fecha de inicio debe ser mayor o igual a hoy.';
        } elseif ($fechaInicial > $fechaFinal) {
            return 'La fecha de inicio no puede ser posterior a la fecha de finalización.';
        }

        if (empty($post['actividades']) || !is_array($post['actividades'])) {
            return 'Debes añadir al menos una actividad.';
        }

        $actividades = $post['actividades'];
        $totalPlazas = 0;
        foreach ($actividades as $i => &$actividad) {
            $actividad['nombre'] = trim($actividad['nombre'] ?? '');
            $actividad['descripcion'] = trim($actividad['descripcion'] ?? '');
            $actividad['plazas'] = (int)($actividad['plazas'] ?? 0);
            $actividad['lugar'] = trim($actividad['lugar'] ?? '');
            $actividad['fecha'] = $actividad['fecha'] ?? '';
            $actividad['orden'] = $i + 1;

            if (
                $actividad['nombre'] === '' ||
                $actividad['descripcion'] === '' ||
                $actividad['plazas'] <= 0 ||
                $actividad['lugar'] === '' ||
                $actividad['fecha'] === ''
            ) {
                return 'Todos los campos de cada actividad son obligatorios y las plazas deben ser mayores que 0.';
            }
            if ($actividad['fecha'] < $fechaInicial || $actividad['fecha'] > $fechaFinal) {
                return 'La fecha de cada actividad debe estar entre la fecha de inicio y fin del evento.';
            }
            $totalPlazas += $actividad['plazas'];
        }
        unset($actividad);

        if ($totalPlazas < 100) {
            return 'La suma total de plazas de todas las actividades debe ser al menos 100.';
        }

        $data = [
            'nombre' => $nombre,
            'descripcion' => $informacionEvento,
            'idTipoEvento' => $idTipoEvento,
            'plazas' => $totalPlazas,
            'precio' => (float)$precio,
            'lugar' => $lugar,
            'fInicio' => $fechaInicial,
            'fFinal' => $fechaFinal,
            'usuarios_id' => $usuarios_id,
            'imagen' => 'missing.png'
        ];
        if ($this->create($data)) {
            require_once __DIR__ . '/ActivityController.php';
            $activityController = new ActivityController();
            $evento = $this->getByNombre($post['nombre']);
            $eventoId = $evento ? $evento->id : null;

            if (!$eventoId) {
                return 'No se pudo obtener el ID del evento recién creado.';
            }

            foreach ($actividades as $actividad) {
                $actividad['idEvento'] = $eventoId;
                $activityController->create($actividad);
            }

            return 'Evento creado correctamente.';
        } else {
            return 'No se pudo crear el evento.';
        }
    }

    public function processEditEvent($originalNombre, $post) {
        $nombre = trim($post['nombre'] ?? '');
        $fechaInicial = $post['fechaInicial'] ?? '';
        $fechaFinal = $post['fechaFinal'] ?? '';
        $idTipoEvento = $post['idTipoEvento'] ?? '';
        $informacionEvento = trim($post['informacionEvento'] ?? '');
        $lugar = trim($post['lugar'] ?? '');
        $plazas = $post['plazas'] ?? '';
        $precio = $post['costePorPlaza'] ?? '';
        $hoy = date('Y-m-d');

        if ($nombre === '' || $fechaInicial === '' || $fechaFinal === '' || $idTipoEvento === '' ||
            $informacionEvento === '' || $lugar === '' || $plazas === '' || $precio === '') {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        } elseif ($fechaInicial < $hoy) {
            return ['success' => false, 'message' => 'La fecha de inicio debe ser mayor o igual a hoy.'];
        } elseif ($fechaInicial > $fechaFinal) {
            return ['success' => false, 'message' => 'La fecha de inicio no puede ser posterior a la fecha de finalización.'];
        } elseif ($plazas < 100) {
            return ['success' => false, 'message' => 'No se pueden crear eventos con menos de 100 plazas.'];
        }

        $evento = $this->getByNombre($originalNombre);
        if (!$evento) {
            return ['success' => false, 'message' => 'Evento no encontrado.'];
        }

        $updateData = [
            'nombre' => $nombre,
            'descripcion' => $informacionEvento,
            'idTipoEvento' => $idTipoEvento,
            'plazas' => (int)$plazas,
            'precio' => (float)$precio,
            'lugar' => $lugar,
            'fInicio' => $fechaInicial,
            'fFinal' => $fechaFinal
        ];

        if ($this->update($evento->id, $updateData)) {
            return ['success' => true, 'message' => 'Evento actualizado correctamente.', 'evento' => $this->getById($evento->id)];
        } else {
            return ['success' => false, 'message' => 'No se pudo actualizar el evento.'];
        }
    }

    public function processDeleteEvent($nombreEvento, $post) {
        $evento = $this->getByNombre($nombreEvento);
        if (!$evento) {
            return ['success' => false, 'message' => 'Evento no encontrado.'];
        }

        require_once __DIR__ . '/ActivityController.php';
        $activityController = new ActivityController();
        $actividades = $activityController->getByEvento($evento->id);
        foreach ($actividades as $actividad) {
            $activityController->delete($actividad->id);
        }

        if ($this->delete($evento->id)) {
            return ['success' => true, 'message' => 'Evento eliminado correctamente.'];
        } else {
            return ['success' => false, 'message' => 'No se pudo eliminar el evento.'];
        }
    }

    public function getTiposDeEventos() {
        $stmt = $this->pdo->query("SELECT id, tipo FROM TiposDeEventos");
        $tipos = [];
        while ($row = $stmt->fetch()) {
            $tipos[$row['id']] = $row['tipo'];
        }
        return $tipos;
    }

    public function getByUserEmail($email) {
        require_once __DIR__ . '/UserController.php';
        $userController = new UserController();
        $userId = $userController->getIdByEmail($email);
        $stmt = $this->pdo->prepare("SELECT * FROM eventos WHERE usuarios_id = ?");
        $stmt->execute([$userId]);
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = new Event($row);
        }
        return $result;
    }
}