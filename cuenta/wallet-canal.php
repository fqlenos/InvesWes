<?php

define('__CONFIG__', true);
require_once('../inc/config.php');

Force::forceActive();
$Usuario = new User($_SESSION['email']);
$canal_id = Get::findChanneller($Usuario -> usuario_id, true)['canal_id'];
$canal_nombre = Get::channelName($canal_id, true)['nombre'];
$canal_info = Get::getChannelData($canal_nombre, true);

$template = file_get_contents("../templates/wallet-canal.html");

$canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
$canal_name = Get::channelName($canal_id, true);

$template = str_replace(
    Array(
        "##micanal##"
    ),
    Array(
        '<a href="/canal.php?canal='.$canal_name['nombre'].'">Mi canal</a>'
    ),
    $template
);

$template_new = str_replace(
    Array(
        "##wallet##",
        "##footer##" 
    ),
    Array(
        $canal_info['wallet'],
        file_get_contents("../templates/footer.php")
    ),
    $template
);

$admin = (boolean) Get::findChanneller($Usuario->usuario_id);

if ($admin) {

    $canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
    $canal_nombre = Get::channelName($canal_id, true)['nombre'];

    $template_new = str_replace(
        "##menu##",
        '
        <a href="/cuenta.php">Información personal</a>
        <a href="/cuenta/password.php">Cambiar contraseña</a>
        <a class="active" href="/cuenta/wallet-canal.php?canal='.$canal_nombre.'" style="margin-top:2rem;">Wallet del canal</a>
        <a href="/cuenta/abandonar-canal.php?canal='.$canal_nombre.'">Eliminar canal</a>
        ',
        $template_new
    );

} else {

    header("Location: /cuenta.php");
    exit;
}

print($template_new);

?>