<?php

define('__CONFIG__', true);
require_once('inc/config.php');

Force::forceIn();

?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="icon" type="image/png" href="/imgs/inveswes_favicon.png">
</head>
<body>
    <div class="topnav">
        <img src="/imgs/inveswes2.png" alt="">
        <a href="/inicio.php">Why InvesWes?</a>
       <div class="topnav-right">
            <a href="/acceso.php">Iniciar sesión</a>
        </div>
    </div>
    <div class="main acceso">
        <h1>Crear cuenta</h1>
        <div class="row">
            <div class="col-6">
                <div class="content">
                    <img src="/imgs/inveswes2.png" class="logo"></img>
                    <div class="info">
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
                        <input type="email" name="email" id="email" value="" autofocus>
                    </label>
                    <label><span>Nombre</span>
                        <input type="text" name="nombre" id="nombre" value="">
                    </label>
                    <label><span>Apellidos</span>
                        <input type="text" name="apellidos" id="apellidos" value="">
                    </label>
                    <label><span>Contraseña</span>
                        <input type="password" name="password" id="password" value="">
                    </label>
                    <button class="btn" name="registro" id="registro" type="submit">Registrarme</button>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("templates/footer.php"); ?>
    <script src="/assets/js/acceso.js" defer async></script>
</body>
</html>