<?php

class Event {
    public $id;
    public $nombre;
    public $descripcion;
    public $idTipoEvento;
    public $plazas;
    public $precio;
    public $lugar;
    public $fInicio;
    public $fFinal;
    public $usuarios_id;
    public $imagen;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->descripcion = $data['descripcion'] ?? '';
        $this->idTipoEvento = $data['idTipoEvento'] ?? null;
        $this->plazas = $data['plazas'] ?? 0;
        $this->precio = $data['precio'] ?? 0;
        $this->lugar = $data['lugar'] ?? '';
        $this->fInicio = $data['fInicio'] ?? '';
        $this->fFinal = $data['fFinal'] ?? '';
        $this->usuarios_id = $data['usuarios_id'] ?? null;
        $this->imagen = $data['imagen'] ?? '';
    }
}
?>