<?php

define('__CONFIG__', true);
require_once('../inc/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    static $con;
    $con = DB::getConnection();

    header('Content-Type: application/json');

    $return = array();
    $return = [];

    // filtrado 
    $email = (string) Filter::String($_POST['email']);
    $email_found = Get::findEmail($email, true);

    if ($email_found) {
        #array_push($return, array('error' => "Ese correo ya está en uso."));
        $return['error'] = "Ese correo ya está en uso.";

    } else {

        $nombre = (string) Filter::String($_POST['nombre']);
        $apellidos = (string) Filter::String($_POST['apellidos']);
        $password = (string) Filter::String($_POST['password']);
        $password = (string) password_hash($password, PASSWORD_DEFAULT);
        $token = (integer) random_int(000000,999999);
        $hash = (string) password_hash($token, PASSWORD_DEFAULT);

        $add_user = $con -> prepare("INSERT INTO usuarios (email, nombre, apellidos, password, hash) VALUES (LOWER(:email), LOWER(:nombre), LOWER(:apellidos), :password, :hash)");

        $add_user -> bindParam(":email", $email, PDO::PARAM_STR);
        $add_user -> bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $add_user -> bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
        $add_user -> bindParam(":password", $password, PDO::PARAM_STR);
        $add_user -> bindParam(":hash", $hash, PDO::PARAM_STR);
        
        $resp = Mailing::SendVerificationMail($email, $nombre, $token, $hash);

        if (strcmp($resp, "error") === 0) {
            $return['error'] = "No se ha podido enviar el correo a esta dirección. Prueba con otro para registrarte.";
        }
        if (strcmp($resp, "done") === 0) {
            $add_user -> execute();
            $Usuario = new User($email);
            $_SESSION['email'] = $Usuario -> email;
            $return['redirect'] = "/verificacion.php?email=".$email;
        }

    }

    echo json_encode($return, JSON_PRETTY_PRINT); 
    exit;

} else {
    header("Location: /home.php");
}

?>