<?php

define('__CONFIG__', true);
require_once('../inc/config.php');

if (isset($_SESSION['email'])) {
    
    $Usuario = new User($_SESSION['email']);
    $canal = Get::getMyChannel($Usuario -> usuario_id)['canal_id'];
    
    $con = DB::getConnection();

    $delete = $con -> prepare("DELETE FROM suscripciones WHERE canal_id = :canal_id");
    $delete -> bindParam(":canal_id", $canal, PDO::PARAM_INT);
    $delete -> execute();

    $delete_canal = $con -> prepare("DELETE FROM canales WHERE canal_id = :canal_id LIMIT 1");
    $delete_canal -> bindParam(":canal_id", $canal, PDO::PARAM_INT);
    $delete_canal -> execute();

    header("Location: /cuenta.php?Canal eliminado correctamente");

} else {
    header("Location: /home.php");
}