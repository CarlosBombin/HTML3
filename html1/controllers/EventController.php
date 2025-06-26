<?php
require_once __DIR__ . '/../models/Event.php';

class EventController {
    private $file = __DIR__ . '/../Datos/Events.json';

    private function readEvents() {
        if (!file_exists($this->file)) return [];
        $json = file_get_contents($this->file);
        return json_decode($json, true) ?? [];
    }

    private function writeEvents($events) {
        file_put_contents($this->file, json_encode($events, JSON_PRETTY_PRINT));
    }

    public function create($data) {
        if ($this->getByNombre($data['nombre_evento'])) {
            return false;
        }
        $events = $this->getAll();
        $events[] = $data;
        $this->writeEvents($events);
        return true;
    }

    public function getAll() {
        return $this->readEvents();
    }

    public function getByNombre($nombre_evento) {
        $events = $this->getAll();
        foreach ($events as $event) {
            if ($event['nombre_evento'] === $nombre_evento) return $event;
        }
        return null;
    }

    public function update($nombre_evento, $newData) {
        $events = $this->getAll();
        foreach ($events as &$event) {
            if ($event['nombre_evento'] === $nombre_evento) {
                $event = array_merge($event, $newData);
                $this->writeEvents($events);
                return true;
            }
        }
        return false;
    }

    public function delete($nombre_evento) {
        $events = $this->getAll();
        foreach ($events as $i => $event) {
            if ($event['nombre_evento'] === $nombre_evento) {
                array_splice($events, $i, 1);
                $this->writeEvents($events);
                return true;
            }
        }
        return false;
    }
}
?>