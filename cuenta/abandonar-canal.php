<?php

define('__CONFIG__', true);
require_once('../inc/config.php');

Force::forceActive();
$Usuario = new User($_SESSION['email']);

$template = file_get_contents("../templates/abandonar-canal.html");

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
        $Usuario->wallet,
        file_get_contents("../templates/footer.php")
    ),
    $template
);

$admin = (boolean) Get::findChanneller($Usuario->usuario_id);

if ($admin) {

    $canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
    $canal_nombre = Get::channelName($canal_id, true)['nombre'];
    $canal = Get::getChannelData($canal_nombre, true);

    $template_new = str_replace(
        Array(
            "##menu##",
            "##nombre##",
            "##num_suscriptores##",
        ),
        Array(
            '
            <a href="/cuenta.php">Información personal</a>
            <a href="/cuenta/password.php">Cambiar contraseña</a>
            <a href="/cuenta/wallet-canal.php?canal='.$canal_nombre.'" style="margin-top:2rem;">Wallet del canal</a>
            <a class="active" href="/cuenta/abandonar-canal.php?canal='.$canal_nombre.'">Eliminar canal</a>
            ',
            mb_convert_case($canal_nombre, MB_CASE_TITLE, "UTF-8"),
            Get::countSubscribers($canal['canal_id'])
        ),
        $template_new
    );

} else {

    header("Location: /cuenta.php");
    exit;
}

print($template_new);

?>