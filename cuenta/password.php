<?php

define('__CONFIG__', true);
require_once('../inc/config.php');

Force::forceActive();
$Usuario = new User($_SESSION['email']);

$template = file_get_contents("../templates/password.html");

$template_new = str_replace(
    Array(
        "##fecha##",
        "##footer##" 
    ),
    Array(
        $Usuario->fecha_mod,
        file_get_contents("../templates/footer.php")
    ),
    $template
);

$admin = (boolean) Get::findChanneller($Usuario->usuario_id);

if ($admin) {

    $canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
    $canal_nombre = Get::channelName($canal_id, true)['nombre'];

    $template_new = str_replace(
        Array(
            "##topnav##",
            "##menu##"
        ),
        Array(
            '
            <img src="/imgs/inveswes2.png" alt="">
            <a href="/home.php">Home</a>
            <a href="/cartera.php">Mi cartera</a>
            <a href="/suscripciones.php">Suscripciones</a>
                
            <div class="topnav-right">
                <a href="/canal.php?canal='.$canal_nombre.'">Mi canal</a>
                <a href="" class="active">Mi cuenta</a>
                <a href="/logout.php">Cerrar sesión</a> 
            </div>
            ',
            '
            <a href="/cuenta.php">Información personal</a>
            <a class="active" href="/cuenta/password.php">Cambiar contraseña</a>
            <a href="/cuenta/wallet-canal.php?canal='.$canal_nombre.'" style="margin-top:2rem;">Wallet del canal</a>
            <a href="/cuenta/abandonar-canal.php?canal='.$canal_nombre.'">Eliminar canal</a>
            '
        ),
        $template_new
    );

} else {

    $template_new = str_replace(
        Array(
            "##topnav##",
            "##menu##"

        ),
        Array(
        '
        <img src="/imgs/inveswes2.png" alt="">
        <a href="/home.php">Home</a>
        <a href="/cartera.php">Mi cartera</a>
        <a href="/suscripciones.php">Suscripciones</a>
            
        <div class="topnav-right">
            <a href="/crear-canal.php">Crear canal</a>
            <a href="" class="active">Mi cuenta</a>
            <a href="/logout.php">Cerrar sesión</a> 
        </div>
        ',
        '
        <a href="/cuenta.php">Información personal</a>
        <a class="active" href="/cuenta/password.php">Cambiar contraseña</a>
        '),
        $template_new
    );
}

print($template_new);

?>