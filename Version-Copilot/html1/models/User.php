<?php

class User {
    public $nombre;
    public $apellidos;
    public $email;
    public $password;
    public $telefono;
    public $direccion;
    public $localidad;
    public $tarjeta;
    public $caducidad;
    public $cvc;
    public $saldo;
    public $rol;

    public function __construct($data) {
        $this->nombre = $data['nombre'] ?? '';
        $this->apellidos = $data['apellidos'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->telefono = $data['telefono'] ?? '';
        $this->direccion = $data['direccion'] ?? '';
        $this->localidad = $data['localidad'] ?? '';
        $this->tarjeta = $data['tarjeta'] ?? '';
        $this->caducidad = $data['caducidad'] ?? '';
        $this->cvc = $data['cvc'] ?? '';
        $this->saldo = $data['saldo'] ?? 0;
        $this->rol = $data['rol'] ?? 'user';
    }
}
?>