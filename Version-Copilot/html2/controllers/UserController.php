<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $file = __DIR__ . '/../Datos/Users.json';

    private function readUsers() {
        if (!file_exists($this->file)) return [];
        $json = file_get_contents($this->file);
        return json_decode($json, true) ?? [];
    }

    private function writeUsers($users) {
        file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
    }

    public function create($data) {
        if ($this->getByEmail($data['email'])) {
            return false;
        }

        $users = $this->readUsers();
        $users[] = $data;
        $this->writeUsers($users);
        return true;
    }

    public function getAll() {
        return $this->readUsers();
    }

    public function getByEmail($email) {
        $users = $this->readUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) return $user;
        }
        return null;
    }

    public function update($email, $newData) {
        $users = $this->readUsers();
        foreach ($users as &$user) {
            if ($user['email'] === $email) {
                $user = array_merge($user, $newData);
                $this->writeUsers($users);
                return true;
            }
        }
        return false;
    }

    public function delete($email) {
        $users = $this->readUsers();
        foreach ($users as $i => $user) {
            if ($user['email'] === $email) {
                array_splice($users, $i, 1);
                $this->writeUsers($users);
                return true;
            }
        }
        return false;
    }

    public function validateUserEdit($data) {
        $nombre = trim($data['nombre'] ?? '');
        $apellidos = trim($data['apellidos'] ?? '');
        $nuevoEmail = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $password2 = $data['password2'] ?? '';
        $telefono = trim($data['telefono'] ?? '');
        $codigo_postal = trim($data['codigo_postal'] ?? '');
        $tarjeta = trim($data['tarjeta'] ?? '');
        $cvc = trim($data['cvc'] ?? '');
        $caducidad = trim($data['caducidad'] ?? '');

        if (
            $nombre === '' || $apellidos === '' || $nuevoEmail === '' || $password === '' || $password2 === ''
        ) {
            return 'Nombre, Apellidos, Email y ambas contraseñas son obligatorios.';
        } elseif (!filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
            return 'El correo electrónico no es válido.';
        } elseif ($password !== $password2) {
            return 'Las contraseñas no coinciden.';
        } elseif (strlen($password) < 10) {
            return 'La contraseña debe tener al menos 10 caracteres.';
        } elseif ($telefono !== '' && !preg_match('/^\d{9,15}$/', $telefono)) {
            return 'El teléfono debe ser un número válido (9-15 dígitos).';
        } elseif ($codigo_postal !== '' && !preg_match('/^\d{5}$/', $codigo_postal)) {
            return 'El código postal debe tener 5 dígitos.';
        } elseif ($tarjeta !== '' && !preg_match('/^\d{16}$/', $tarjeta)) {
            return 'El número de tarjeta debe tener 16 dígitos.';
        } elseif ($cvc !== '' && !preg_match('/^\d{3}$/', $cvc)) {
            return 'El CVC debe tener 3 dígitos.';
        } elseif ($caducidad !== '' && !preg_match('/^(0[1-9]|1[0-2])\/\d{4}$/', $caducidad)) {
            return 'La fecha de caducidad debe tener el formato MM/AAAA.';
        } elseif ($caducidad !== '') {
            list($mes, $anio) = explode('/', $caducidad);
            $caducidadTime = strtotime($anio . '-' . $mes . '-01');
            $actualTime = strtotime(date('Y-m-01'));
            if ($caducidadTime < $actualTime) {
                return 'La fecha de caducidad no puede ser anterior al mes actual.';
            }
        }
        return '';
    }

    public function processEditUser($email, $post) {
        $mensaje = $this->validateUserEdit($post);
        if ($mensaje !== '') {
            return $mensaje;
        }

        $updateData = [
            'nombre' => trim($post['nombre'] ?? ''),
            'apellidos' => trim($post['apellidos'] ?? ''),
            'email' => trim($post['email'] ?? ''),
            'password' => password_hash($post['password'], PASSWORD_DEFAULT),
            'telefono' => trim($post['telefono'] ?? ''),
            'direccion' => trim($post['direccion'] ?? ''),
            'localidad' => trim($post['localidad'] ?? ''),
            'codigo_postal' => trim($post['codigo_postal'] ?? ''),
            'tarjeta' => trim($post['tarjeta'] ?? ''),
            'cvc' => trim($post['cvc'] ?? ''),
            'caducidad' => trim($post['caducidad'] ?? '')
        ];

        $this->update($email, $updateData);
        return 'Datos actualizados correctamente.';
    }

    public function loginUser($email, $password) {
        $usuario = $this->getByEmail($email);
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return null;
    }
}
?>