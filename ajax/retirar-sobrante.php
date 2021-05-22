<?php

define('__CONFIG__', true);
require_once('../inc/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    static $con;
    $con = DB::getConnection();
    $Usuario = new User($_SESSION['email']);

    header('Content-Type: application/json');

    $return = array();
    $return = [];

    // filtrado
    $cantidad = (integer) Filter::Int($_POST['cantidad']);
    $wallet_dst = (integer) Filter::Int($_POST['wallet_dst']);

    $resp = Pay::transferTLM($cantidad, explode("-", $Usuario->wallet)[0]."-".explode("-", $Usuario->wallet)[1], $wallet_dst);

    if ($resp) {
        $return['redirect'] = "/canal.php";
    } else {
        $return['error'] = "Error en la retirada, comprueba toda la información.";
    }

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit;

} else {
    header("Location: /home.php");
}