<?php

define('__CONFIG__', true);
require_once('inc/config.php');

Force::forceActive();
$Usuario = new User($_SESSION['email']);
$admin = (boolean) Get::findChanneller($Usuario->usuario_id);

$template = file_get_contents("templates/cartera.html");

$tickers = Api::getTickers();
foreach($tickers as $t) {
    $template = str_replace(
        "##ticker##",
        '<option value="'.$t['ticker'].'">'.strtoupper($t['ticker']).'/USD </option>
        ##ticker##'
        ,
        $template
    );   
}

$template_new = str_replace(
    "##footer##",
    file_get_contents("templates/footer.php"),
    $template
);

$valores = Api::getUsuarioCartera($Usuario -> usuario_id);
foreach($valores as $v) {
    $ticker = Api::findTickerName($v['valor_id'], true)['ticker'];
    $precio_actual = Api::findTickerValue($ticker);
    $template_new = str_replace(
        "##tr##",
        "
        <tr>
            <td>".strtoupper($ticker)."/USD</td>
            <td>".$v['precio_compra']."$</td>
            <td>".round($precio_actual,3)."$</td>
            <td>".$v['cantidad']."</td>
            <td>".round(($precio_actual - $v['precio_compra']) * $v['cantidad'], 2)."$ </td>
            <td>".round((($precio_actual - $v['precio_compra'])/$v['precio_compra']) * 100, 2)."% </td>
            <td><a class='pop' id='".$ticker."'>Cerrar</a></td>
        </tr>
        ##tr##",
        $template_new
    );
}

$template_new = str_replace("##tr##", "", $template_new);

if (!$admin) {
    $template_new = str_replace(
        "##topnav##",
        '
        <img src="/imgs/inveswes2.png" alt="">
        <a href="/home.php">Home</a>
        <a href="/cartera.php" class="active">Mi cartera</a>
        <a href="/suscripciones.php">Suscripciones</a>
        <div class="topnav-right">
            <a href="/crear-canal.php">Crear canal</a>
            <a href="/cuenta.php">Mi cuenta</a>
            <a href="/logout.php">Cerrar sesión</a> 
        </div>
        ',
        $template_new
    );

} else {
    $canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
    $canal_name = Get::channelName($canal_id, true);

    $template_new = str_replace(
        "##topnav##",
        '
        <img src="/imgs/inveswes2.png" alt="">
        <a href="/home.php">Home</a>
        <a href="/cartera.php" class="active">Mi cartera</a>
        <a href="/suscripciones.php">Suscripciones</a>
        <div class="topnav-right">
            <a href="/canal.php?canal='.$canal_name['nombre'].'">Mi canal</a>
            <a href="/cuenta.php">Mi cuenta</a>
            <a href="/logout.php">Cerrar sesión</a> 
        </div>
        ',
        $template_new
    );
}

print($template_new);

?>
