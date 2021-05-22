<?php

if (!defined('__CONFIG__')) {
    exit("Error, no hay archivo de configuración.");
}

if (!isset($_SESSION)) {
    session_start();
}

//permitir errores por pantalla
#error_reporting(-1);
#ini_set("display_errors", "On");

include_once("classes/Api.php");
include_once("classes/DB.php");
include_once("classes/Filter.php");
include_once("classes/Force.php");
include_once("classes/Get.php");
include_once("classes/Mailing.php");
include_once("classes/Pay.php");
include_once("classes/User.php");

?>