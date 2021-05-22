<?php

define('__CONFIG__', true);
require_once('inc/config.php');

Force::forceActive();
$Usuario = new User($_SESSION['email']);

$template = file_get_contents("templates/crear-canal.html");
$admin = (boolean) Get::findChanneller($Usuario->usuario_id);

if (!$admin) {
    $template_new = str_replace(
        Array(
            "##topnav##",
            "##footer##" 
        ),
        Array(
            '
            <img src="/imgs/inveswes2.png" alt="">
            <a href="/home.php">Home</a>
            <a href="/cartera.php">Mi cartera</a>
            <a href="/suscripciones.php">Suscripciones</a>
             
           <div class="topnav-right">
                <a href="/cuenta.php">Mi cuenta</a>
                 <a href="/logout.php">Cerrar sesión</a> 
            </div>
            ',
            file_get_contents("templates/footer.php")
        ),
      $template
    );    
} else {
    header("Location: /home.php");
    exit;
}

print($template_new);

?>