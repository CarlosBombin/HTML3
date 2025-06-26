<?php

class Event {
    public $nombre_evento;
    public $descripcion;
    public $tipo_evento;
    public $plazas;
    public $lugar;
    public $fecha_inicio;
    public $fecha_fin;
    public $actividades;

    public function __construct($data) {
        $this->nombre_evento = $data['nombre_evento'] ?? '';
        $this->descripcion = $data['descripcion'] ?? '';
        $this->tipo_evento = $data['tipo_evento'] ?? '';
        $this->plazas = $data['plazas'] ?? 0;
        $this->lugar = $data['lugar'] ?? '';
        $this->fecha_inicio = $data['fecha_inicio'] ?? '';
        $this->fecha_fin = $data['fecha_fin'] ?? '';
        $this->actividades = $data['actividades'] ?? [];
    }
}
?>