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
    
    $nombre = (string) Filter::String($_POST['nombre']);
    $apellidos = (string) Filter::String($_POST['apellidos']);

    $update = $con -> prepare("UPDATE usuarios SET nombre = LOWER(:nombre), apellidos = LOWER(:apellidos) WHERE email = LOWER(:email) LIMIT 1");
    $update -> bindParam(":nombre", $nombre, PDO::PARAM_STR);
    $update -> bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
    $update -> bindParam(":email", $Usuario->email, PDO::PARAM_STR);
    $update -> execute();

    $return['redirect'] = "/cuenta.php?¡Datos actualizados!";

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit;

} else {
    header("Location: /home.php");
}