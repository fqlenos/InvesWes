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
    
    $ticker = (string) Filter::String($_POST['id']);
    $valor_id = (integer) Api::findTicker($ticker, true)['valor_id'];
    $found_ticker = (boolean) Api::findTicker($ticker);

    if ($found_ticker) {

        $add_coin = $con -> prepare("DELETE FROM cartera_usuario WHERE valor_id = :valor_id");
        $add_coin -> bindParam(":valor_id", $valor_id, PDO::PARAM_INT);
        $add_coin -> execute();

        $return['redirect'] = "/cartera.php?Valor eliminado";

    } else {
        $return['redirect'] = "/cartera.php?Lo sentimos ese valor no está disponible ya.";
    }

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit;

} else {
    header("Location: /home.php");
}