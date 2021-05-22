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
        // si existe probamos a hacer login
        $token = (string) Filter::String($_POST['token']);
        $hash = (string) $email_found['hash'];
        
        if (password_verify($token, $hash)){
            // comprobamos si sigue inactivo

            if (!$email_found['activo']) {
                // es correcto, sigue inactivo
                $update = $con -> prepare("UPDATE usuarios SET activo = 1, hash = NULL WHERE email = LOWER(:email) LIMIT 1");
                $update -> bindParam(":email", $email, PDO::PARAM_STR);
                $update -> execute();

                $Usuario = new User($email);
                $_SESSION['email'] = (string) $Usuario -> email;
                $return['redirect'] = '/home.php?Welcome to InvesWes';

            } else {

                $return['redirect'] = '/home.php';

            }
        } else {
            $return['error'] = 'Correo electrónico y/o contraseña incorrectas.';
        }

    } else {
        $return['error'] = 'Debes registrarte primero';
    }

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit;

} else {
    header("Location: /home.php");
}