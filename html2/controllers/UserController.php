<?php
require_once __DIR__ . '/../Datos/Database.php';
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create($data) {
        if ($this->getByEmail($data['email'])) {
            return false;
        }
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion, localidad, codigoPostal, nTarjeta, fCaducidad, CCV, saldo, idRol)
                VALUES (:nombre, :apellidos, :email, :password, :telefono, :direccion, :localidad, :codigoPostal, :nTarjeta, :fCaducidad, :CCV, :saldo, :idRol)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':apellidos' => $data['apellidos'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':telefono' => $data['telefono'] ?? null,
            ':direccion' => $data['direccion'] ?? null,
            ':localidad' => $data['localidad'] ?? null,
            ':codigoPostal' => $data['codigoPostal'] ?? null,
            ':nTarjeta' => $data['nTarjeta'] ?? null,
            ':fCaducidad' => $data['fCaducidad'] ?? null,
            ':CCV' => $data['CCV'] ?? null,
            ':saldo' => $data['saldo'] ?? 0,
            ':idRol' => $data['idRol'] ?? 1
        ]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll();
    }

    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($email, $newData) {
        $set = [];
        $params = [];
        foreach ($newData as $key => $value) {
            $set[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $email;
        $sql = "UPDATE usuarios SET " . implode(', ', $set) . " WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($email) {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE email = ?");
        return $stmt->execute([$email]);
    }

    public function validateUserEdit($data) {
        $nombre = trim($data['nombre'] ?? '');
        $apellidos = trim($data['apellidos'] ?? '');
        $nuevoEmail = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $password2 = $data['password2'] ?? '';
        $telefono = trim($data['telefono'] ?? '');
        $codigoPostal = trim($data['codigoPostal'] ?? '');
        $nTarjeta = trim($data['nTarjeta'] ?? '');
        $CCV = trim($data['CCV'] ?? '');
        $fCaducidad = trim($data['fCaducidad'] ?? '');

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
        } elseif ($codigoPostal !== '' && !preg_match('/^\d{5}$/', $codigoPostal)) {
            return 'El código postal debe tener 5 dígitos.';
        } elseif ($nTarjeta !== '' && !preg_match('/^\d{16}$/', $nTarjeta)) {
            return 'El número de tarjeta debe tener 16 dígitos.';
        } elseif ($CCV !== '' && !preg_match('/^\d{3}$/', $CCV)) {
            return 'El CCV debe tener 3 dígitos.';
        } elseif ($fCaducidad !== '' && !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $fCaducidad)) {
            return 'La fecha de caducidad debe tener el formato AAAA-MM.';
        } elseif ($fCaducidad !== '') {
            $caducidadTime = strtotime($fCaducidad . '-01');
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
        
        if ($post['fCaducidad'] && strlen($post['fCaducidad']) === 7) {
            $post['fCaducidad'] .= '-01';
        }

        $updateData = [
            'nombre' => trim($post['nombre'] ?? ''),
            'apellidos' => trim($post['apellidos'] ?? ''),
            'email' => trim($post['email'] ?? ''),
            'password' => password_hash($post['password'], PASSWORD_DEFAULT),
            'telefono' => trim($post['telefono'] ?? ''),
            'direccion' => trim($post['direccion'] ?? ''),
            'localidad' => trim($post['localidad'] ?? ''),
            'codigoPostal' => trim($post['codigoPostal'] ?? ''),
            'nTarjeta' => trim($post['nTarjeta'] ?? ''),
            'CCV' => trim($post['CCV'] ?? ''),
            'fCaducidad' => trim($post['fCaducidad'] ?? '')
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

    public function getIdByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ? $row['id'] : null;
    }

    public function processLogin($post) {
        $email = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';

        if ($email === '' || $password === '') {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        }

        $usuario = $this->loginUser($email, $password);
        if ($usuario) {
            return ['success' => true, 'user' => $usuario];
        } else {
            return ['success' => false, 'message' => 'Email o contraseña incorrectos.'];
        }
    }

    public function processRegister($post) {
        $nombre = trim($post['nombre'] ?? '');
        $apellidos = trim($post['apellidos'] ?? '');
        $email = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';
        $password2 = $post['password2'] ?? '';

        if ($nombre === '' || $apellidos === '' || $email === '' || $password === '' || $password2 === '') {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El correo electrónico no es válido.'];
        } elseif ($password !== $password2) {
            return ['success' => false, 'message' => 'Las contraseñas no coinciden.'];
        } elseif (strlen($password) < 10) {
            return ['success' => false, 'message' => 'La contraseña debe tener al menos 10 caracteres.'];
        }

        if ($this->getByEmail($email)) {
            return ['success' => false, 'message' => 'El correo ya está registrado.'];
        }

        $data = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'idRol' => 1
        ];

        if ($this->create($data)) {
            $usuario = $this->getByEmail($email);
            return ['success' => true, 'user' => $usuario];
        } else {
            return ['success' => false, 'message' => 'Error al registrar el usuario.'];
        }
    }
}
?>