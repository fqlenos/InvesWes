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
    <title>InvesWes</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="icon" type="image/png" href="/imgs/inveswes_favicon.png">
</head>
<body>
    <div class="topnav">
        <img src="/imgs/inveswes2.png" alt="">
        <a href="" class=" active">Why InvesWes?</a>
        <a href="/home.php">Home</a>
       <div class="topnav-right">
            <a class="btn" href="/registro.php">Pruébalo</a>
            <a href="/acceso.php">Iniciar sesión</a>
        </div>
    </div>
    <div class="main inicio">
        <div class="row">
            <div class="col-6 container-left">
                <h1>Los canales de criptomonedas de los que todo el mundo habla</h1>
                <div class="info">
                    Pruébalo ahora mismo. <br>
                    Cancela cuando quieras.
                </div>
                <a href="/registro.php" class="btn btn-primary">Pruébalo</a>
            </div>
            <div class="col-6">
                <div class="panel">
                    <div class="fila">
                        <img src="imgs/coin-logos/bitcoin.png" alt="">
                        <img src="imgs/coin-logos/bitshares-bts-logo.png" alt="">
                        <img src="imgs/coin-logos/Ethereum-Logo.png" alt="">
                    </div>
                    <div class="fila">
                        <img src="imgs/coin-logos/iota-miota-logo.png" alt="">
                        <img src="imgs/coin-logos/Hshare-HSR-icon.png" alt="">
                        <img src="imgs/coin-logos/decred-dcr-logo.png" alt="">
                    </div>
                    <div class="fila">
                        <img src="imgs/coin-logos/litecoin.png" alt="">
                        <img src="imgs/coin-logos/monero-xmr-logo.png" alt="">
                        <img src="imgs/coin-logos/neo-logo-png-transparent.png" alt="">
                    </div>
                    <div class="fila">
                        <img src="imgs/coin-logos/steem-steem-logo.png" alt="">
                        <img src="imgs/coin-logos/stratis-strax-logo.png" alt="">
                        <img src="imgs/coin-logos/tether-usdt-logo.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("templates/footer.php"); ?>
</body>
</html>