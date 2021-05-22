<?php

define('__CONFIG__', true);
require_once('inc/config.php');

Force::forceActive();
$Usuario = new User($_SESSION['email']);
$suscripciones = Get::getMyChannels($Usuario->usuario_id);
$admin = (boolean) Get::findChanneller($Usuario->usuario_id);

$template = file_get_contents("templates/suscripciones.html");
if (!$admin) {

    $template_new = str_replace(
        Array(
            "##topnav##",
            "##footer##" 
        ),
        Array(
            '
            <div class="topnav">
                <img src="/imgs/inveswes2.png" alt="">
                <a href="/home.php">Home</a>
                <a href="/cartera.php">Mi cartera</a>
                <a href="" class="active">Suscripciones</a>
                    
                <div class="topnav-right">
                    <a href="/crear-canal.php">Crear canal</a>
                    <a href="/cuenta.php">Mi cuenta</a>
                        <a href="/logout.php">Cerrar sesión</a>
                </div>
            </div>
            ',
            file_get_contents("templates/footer.php"),
        ),
    $template);

    if (count($suscripciones) > 0) {

        foreach($suscripciones as $c) {
            $template_new = str_replace(
                "##canal##",
                '
                <a href="/canal.php?canal='.$c['nombre'].'" class="carousel">
                    <div class="novedad">'.$c['precio_diario'].' TLMCoins</div>
                    <h1 class="nombre">'.$c['nombre'].'</h1>
                </a>
                ##canal##
                ',
                $template_new
            );
        }
        $template_new = str_replace(
            "##canal##",
            '',
            $template_new
        );

    } else {

        $template_new = str_replace(
            "##canal##",
            '
            <span>Debes suscribirte a algún canal primero.</span>
            ',
            $template_new
        );
        
    }    
} else {
    
    $canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
    $canal_nombre = Get::channelName($canal_id, true)['nombre'];

    $template_new = str_replace(
        Array(
            "##topnav##",
            "##footer##" 
        ),
        Array(
            '
            <div class="topnav">
                <img src="/imgs/inveswes2.png" alt="">
                <a href="/home.php">Home</a>
                <a href="/cartera.php">Mi cartera</a>
                <a class="active" href="">Suscripciones</a>
                
                <div class="topnav-right">
                    <a href="/canal.php?canal='.$canal_nombre.'">Mi canal</a>
                    <a href="/cuenta.php">Mi cuenta</a>
                    <a href="/logout.php">Cerrar sesión</a>
                </div>
            </div>
            ',
            file_get_contents("templates/footer.php"),
        ),
    $template);

    if (count($suscripciones) > 0) {

        foreach($suscripciones as $c) {
            $template_new = str_replace(
                "##canal##",
                '
                <a href="/canal.php?canal='.$c['nombre'].'" class="carousel">
                    <div class="novedad">'.$c['nombre'].'</div>
                    <img src="/imgs/logos/stockoverflow.png">
                </a>
                ##canal##
                ',
                $template_new
            );
        }
        $template_new = str_replace(
            "##canal##",
            '',
            $template_new
        );

    } else {

        $template_new = str_replace(
            "##canal##",
            '
            <span>Debes suscribirte a algún canal primero.</span>
            ',
            $template_new
        );
        
    }

}


print($template_new);

?>

