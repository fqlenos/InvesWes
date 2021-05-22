<?php

if (!defined('__CONFIG__')) {
    exit("Error, no hay archivo de configuración");
}

class User {
    private $con;

    public $usuario_id;
    public $email;
    public $nombre;
    public $apellidos;
    public $wallet;
    public $fecha_mod;
    public $activo;

    public function __construct(string $email) {
        $this -> con = DB::getConnection();

        // filtrado de variable
        $email = Filter::String($email);

        $usuario = $this -> con -> prepare("SELECT usuario_id, email, nombre, apellidos, wallet, fecha_mod, activo FROM usuarios WHERE email = LOWER(:email) LIMIT 1");
        $usuario -> bindParam(':email', $email, PDO::PARAM_STR);
        $usuario -> execute();

        if ($usuario -> rowCount() == 1) {

            $usuario = $usuario -> fetch(PDO::FETCH_OBJ);

            $this -> usuario_id = (string) $usuario -> usuario_id;
            $this -> email = (string) $usuario -> email;
            $this -> nombre = (string) $usuario -> nombre;
            $this -> apellidos = (string) $usuario -> apellidos;
            $this -> wallet = (string) $usuario -> wallet;
            $this -> fecha_mod = (string) $usuario -> fecha_mod;
            $this -> activo = (boolean) $usuario -> activo;
        }
    }
}

?>