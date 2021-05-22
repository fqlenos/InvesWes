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

    $email_found = Get::findEmail($Usuario->email, true); // de aquí saco la password del usuario actual
    $hash = (string) $email_found['password'];

    $password_new = (string) Filter::String($_POST['password_new']);
    $password_new_repeat = (string) Filter::String($_POST['password_new_repeat']);
    $password_current = (string) Filter::String($_POST['password_current']);
    
    if (strcmp($password_new, $password_new_repeat) === 0) {

        if (password_verify($password_current, $hash)) {

            $password = password_hash($password_new, PASSWORD_DEFAULT);

            $updatePassword = $con -> prepare("UPDATE usuarios SET password = :password, fecha_mod = NOW() WHERE email = LOWER(:email) LIMIT 1");
            $updatePassword -> bindParam(":password", $password, PDO::PARAM_STR);
            $updatePassword -> bindParam(":email", $Usuario->email, PDO::PARAM_STR);
            $updatePassword -> execute();

            $Usuario = new User($_SESSION['email']);
            $return['redirect'] = '/cuenta.php?¡Contraseña actualizada correctamente!';

        } else {
            $return['error'] = 'La contraseña es incorrecta.';
        }
    } else {
        $return['error'] = 'Las contraseñas no coinciden.';
    }

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit;

} else {
    header("Location: /home.php");
}


?>