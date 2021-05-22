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
    <div class="main error">
        <div class="row">
            <div class="error-div">
                <h1>Error 404</h1>
                <h2>Esta página no existe</h2>
                <a class="btn" href="/inicio.php">Volver al inicio</a>
            </div>
        </div>
    </div>
    <?php require_once("templates/footer.php"); ?>
</body>
</html>