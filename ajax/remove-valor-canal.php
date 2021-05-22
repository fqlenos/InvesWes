<?php

define('__CONFIG__', true);
require_once('../inc/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $Usuario = new User($_SESSION['email']);
    $canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
    $canal_nombre = Get::channelName($canal_id, true)['nombre'];
    static $con;
    $con = DB::getConnection();

    header('Content-Type: application/json');

    $return = array();
    $return = [];
    
    $ticker = (string) Filter::String($_POST['id']);
    $valor_id = (integer) Api::findTicker($ticker, true)['valor_id'];
    $found_ticker = (boolean) Api::findTicker($ticker);

    if ($found_ticker) {

        $add_coin = $con -> prepare("DELETE FROM cartera_canal WHERE canal_id = :canal_id AND valor_id = :valor_id");
        $add_coin -> bindParam(":canal_id", $canal_id, PDO::PARAM_INT);
        $add_coin -> bindParam(":valor_id", $valor_id, PDO::PARAM_INT);
        $add_coin -> execute();

        $return['redirect'] = "/canal.php?canal=".$canal_nombre;

    } else {
        $return['redirect'] = "/canal.php?canal=".$canal_nombre;
    }

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit;

} else {
    header("Location: /home.php");
}