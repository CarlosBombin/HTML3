<?php
class Request {
    public $email;

    public function __construct ($data) {
        $this->email = $data['email'] ?? '';
    }
}
?>