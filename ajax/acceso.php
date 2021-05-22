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
        $password = (string) Filter::String($_POST['password']);
        $hash = (string) $email_found['password'];
        
        if (password_verify($password, $hash)){
            // comprobamos si ha verificado email
            if ($email_found['activo']) {
                // es correcto, damos acceso
                $Usuario = new User($email);
                $_SESSION['email'] = (string) $Usuario -> email;
                $return['redirect'] = '/home.php?Welcome to InvesWes';   
            } else {
                $return['redirect'] = '/verificacion.php?email'.$email;
            }
        } else {
            $return['error'] = 'Correo electrónico y/o contraseña incorrectas.';
        }

    } else {
        $return['error'] = 'Correo electrónico y/o contraseña incorrectas.';
    }

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit;

} else {
    header("Location: /home.php");
}