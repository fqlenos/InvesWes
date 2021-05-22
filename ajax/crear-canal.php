<?php

define('__CONFIG__', true);
require_once('../inc/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $Usuario = new User($_SESSION['email']);

    static $con;
    $con = DB::getConnection();

    header('Content-Type: application/json');

    $return = array();
    $return = [];

    // filtrado  
    $nombre = (string) Filter::String($_POST['nombre']);
    $name_found = (boolean) Get::getChannelData($nombre, false);

   if ($name_found) {

        $return['error'] = "Este nombre ya está en uso.";

    } else {

        $descripcion = (string) Filter::String($_POST['descripcion']);
        $precio_diario = (integer) Filter::Int($_POST['precio_diario']);
        $wallet = (integer) Filter::Int($_POST['wallet']);
        $activo = (boolean) 1;

        $add_channel = $con -> prepare("INSERT INTO canales (nombre, descripcion, precio_diario, wallet, activo) VALUES (LOWER(:nombre), :descripcion, :precio_diario, :wallet, :activo)");

        $add_channel -> bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $add_channel -> bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
        $add_channel -> bindParam(":precio_diario", $precio_diario, PDO::PARAM_INT);
        $add_channel -> bindParam(":wallet", $wallet, PDO::PARAM_INT);
        $add_channel -> bindParam(":activo", $activo, PDO::PARAM_BOOL);

        $add_channel -> execute();

        $return['redirect'] = "/canal.php?canal=".$nombre;

        $canal_id = (integer) Get::getChannelData($nombre, true)['canal_id'];
        $admin = (boolean) 1;
        $create_channeller = $con -> prepare("INSERT INTO suscripciones (usuario_id, canal_id, admin) VALUES (:usuario_id, :canal_id, :admin)");

        $create_channeller -> bindParam(":usuario_id", $Usuario -> usuario_id, PDO::PARAM_INT);
        $create_channeller -> bindParam(":canal_id", $canal_id, PDO::PARAM_INT);
        $create_channeller -> bindParam(":admin", $admin, PDO::PARAM_BOOL);

        $create_channeller -> execute();
        
    }

    echo json_encode($return, JSON_PRETTY_PRINT); 
    exit;

} else {
    header("Location: /home.php");
}

?>