<?php

class Activity {
    public $id;
    public $nombre;
    public $descripcion;
    public $orden;
    public $plazas;
    public $lugar;
    public $fecha;
    public $idEvento;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->descripcion = $data['descripcion'] ?? '';
        $this->orden = $data['orden'] ?? null;
        $this->plazas = $data['plazas'] ?? 0;
        $this->lugar = $data['lugar'] ?? '';
        $this->fecha = $data['fecha'] ?? '';
        $this->idEvento = $data['idEvento'] ?? null;
    }
}
?>