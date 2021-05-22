<?php

define('__CONFIG__', true);
require_once('inc/config.php');

Force::forceActive();

$Usuario = new User($_SESSION['email']);

if (isset($_GET['canal'])) {

    $nombre = (string) Filter::String($_GET['canal']);
    $name_found = Get::getChannelData($nombre, true);

    $con = DB::getConnection();
    $unsubscribe = $con -> prepare("DELETE FROM suscripciones WHERE canal_id = :canal_id AND usuario_id = :usuario_id");
    $unsubscribe -> bindParam(":canal_id", $name_found['canal_id'], PDO::PARAM_INT);
    $unsubscribe -> bindParam(":usuario_id", $Usuario->usuario_id, PDO::PARAM_INT);
    $unsubscribe -> execute();

    header("Location: /home.php?Has abandonado el canal correctamente.");

} else {
    header("Location: /home.php");
}
?>