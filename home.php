<?php

define('__CONFIG__', true);
require_once('inc/config.php');

$channels = Get::getAllChannels();

$template = file_get_contents("templates/home.html");

if (isset($_SESSION['email'])) {
    $Usuario = new User($_SESSION['email']);
    $activo = (boolean) $Usuario -> activo;
    $admin = (boolean) Get::findChanneller($Usuario->usuario_id);
}

if (!isset($_SESSION['email']) || $activo == 0) {
    $template_new = str_replace(
        Array(
            "##topnav##",
            "##footer##" 
        ),
        Array(
            '
            <div class="topnav">
                <img src="/imgs/inveswes2.png" alt="">
                <a href="/inicio.php">Why InvesWes?</a>
                <a href="" class=" active">Home</a>
            <div class="topnav-right">
                    <a class="btn" href="/registro.php">Pruébalo</a>
                    <a href="/acceso.php">Iniciar sesión</a>
                </div>
            </div>
            ',
            file_get_contents("templates/footer.php")
        ),
        $template
    );

    if (count($channels) > 0) {

        foreach($channels as $c) {
            $template_new = str_replace(
                "##canal##",
                '
                <a href="/canal.php" class="carousel">
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
            <span>De momento no hay canales para mostrar.</span>
            ',
            $template_new
        );
        
    }    

} else if (!$admin) {

    $template_new = str_replace(
        Array(
            "##topnav##",
            "##footer##" 
        ),
        Array(
            '
            <div class="topnav">
                <img src="/imgs/inveswes2.png" alt="">
                <a href="" class="active" >Home</a>
                <a href="/cartera.php">Mi cartera</a>
                <a href="/suscripciones.php">Suscripciones</a>
                
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

    if (count($channels) > 0) {

        foreach($channels as $c) {
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
            <span>De momento no hay canales para mostrar.</ span>
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
                <a href="" class="active" >Home</a>
                <a href="/cartera.php">Mi cartera</a>
                <a href="/suscripciones.php">Suscripciones</a>
                
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

    if (count($channels) > 0) {

        foreach($channels as $c) {
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
            <span>De momento no hay canales para mostrar.</span>
            ',
            $template_new
        );
        
    }

}


print($template_new);

?>