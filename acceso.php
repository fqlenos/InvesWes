<?php

define('__CONFIG__', true);
require_once('inc/config.php');

Force::forceIn();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="icon" type="image/png" href="/imgs/inveswes_favicon.png">
</head>
<body>
    <div class="topnav">
        <img src="/imgs/inveswes2.png" alt="">
        <a href="/inicio.php">Why InvesWes?</a>
    </div>
    <div class="main acceso">
        <h1>Iniciar sesión</h1> 
        <div class="row">
            <div class="col-6">
                <div class="content">
                    <img src="/imgs/inveswes2.png" class="logo"></img>
                    <div class="info">
                        <div class="title">
                            ¿Necesitas más razones para empezar?
                        </div> 
                        <div class="list">
                            <ul>
                                <li>Sin compromiso.</li>
                                <ul><li>Cancela cuando quieras.</li></ul>
                                <li>Crea tu propia lista de seguimiento.</li>
                                <ul><li>Tendrás todos los valores a tiempo real a tu disposición.</li></ul>
                                <li>Gestiona tus suscripciones</li>
                                <ul><li>Date de alta en tantos canales como quieras.</li></ul>
                            </ul>
                        </div>   
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form login">
                    <label><span>Correo electrónico</span>
                        <input type="email" name="email" id="email" autofocus>
                    </label>
                    <label><span>Contraseña</span>
                        <input type="password" name="password" id="password">
                    </label>
                    <button class="btn" name="acceso" id="acceso" type="submit">Iniciar sesión</button>
                </div>
                <div class="paragraph">
                    <p>¿No tienes cuenta?</p>
                    <a class="btn" href="/registro.php">Prueba</a>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/acceso.js" defer async></script>
    <?php require_once("templates/footer.php"); ?>
</body>
</html>