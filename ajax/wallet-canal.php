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
    
    $wallet = (integer) Filter::Int($_POST['wallet']);
    $email_found = Get::findEmail($Usuario -> email, true);

    if ($email_found) {
        $canal_id = Get::findChanneller($Usuario -> usuario_id, true)['canal_id'];
        $password = (string) Filter::String($_POST['password']);
        $hash = (string) $email_found['password'];
        
        if (password_verify($password, $hash)){

            $update = $con -> prepare("UPDATE canales SET wallet = :wallet WHERE canal_id = :canal_id  LIMIT 1");
            $update -> bindParam(":wallet", $wallet, PDO::PARAM_INT);
            $update -> bindParam(":canal_id", $canal_id, PDO::PARAM_INT);
            $update -> execute();
        
            $return['redirect'] = "/cuenta.php?Wallet actualizada!";

        } else {
            $return['error'] = "Contraseña incorrecta.";
        }
    } else {
        $return['redirect'] = "/home.php?Error en el acceso.";
    }

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit;

} else {
    header("Location: /home.php");
}