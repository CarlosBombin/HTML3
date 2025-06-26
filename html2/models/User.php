<?php

class User {
    public $id;
    public $nombre;
    public $apellidos;
    public $email;
    public $password;
    public $telefono;
    public $direccion;
    public $localidad;
    public $codigoPostal;
    public $nTarjeta;
    public $fCaducidad;
    public $CCV;
    public $saldo;
    public $idRol;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->apellidos = $data['apellidos'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->telefono = $data['telefono'] ?? '';
        $this->direccion = $data['direccion'] ?? '';
        $this->localidad = $data['localidad'] ?? '';
        $this->codigoPostal = $data['codigoPostal'] ?? '';
        $this->tarjeta = $data['nTarjeta'] ?? '';
        $this->caducidad = $data['fCaducidad'] ?? '';
        $this->cvc = $data['CCV'] ?? '';
        $this->saldo = $data['saldo'] ?? 0;
        $this->idRol = $data['idRol'] ?? null;
    }
}
?>