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
    
    $ticker = (string) Filter::String($_POST['select-crypto']);
    $valor_id = (integer) Api::findTicker($ticker, true)['valor_id'];
    $precio = (string) Filter::String($_POST['precio']);
    $cantidad = (string) Filter::String($_POST['cantidad']);

    $canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
    $canal_nombre = Get::channelName($canal_id, true)['nombre'];

    $found_ticker = (boolean) Api::findTicker($ticker);

    if ($found_ticker) {

        $add_coin = $con -> prepare("INSERT IGNORE INTO cartera_canal(canal_id, valor_id, precio_compra, cantidad) VALUES (:canal_id, :valor_id, :precio_compra, :cantidad)");
        $add_coin -> bindParam(":canal_id", $canal_id, PDO::PARAM_INT);
        $add_coin -> bindParam(":valor_id", $valor_id, PDO::PARAM_INT);
        $add_coin -> bindParam(":precio_compra", $precio, PDO::PARAM_STR);
        $add_coin -> bindParam(":cantidad", $cantidad, PDO::PARAM_STR);
        $add_coin -> execute();

        $return['redirect'] = "/canal.php?canal=".$canal_nombre;

    } else {
        $redirect['error'] = "Lo sentimos ese valor no está disponible ya.";
    }

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit;

} else {
    header("Location: /home.php");
}