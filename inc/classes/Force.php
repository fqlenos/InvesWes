<?php 

if(!defined('__CONFIG__')){
    exit('No hay archivo de configuración.');
}

class Force {

    // fuerzo a que verifique el correo si quiere visitar las páginas
    public static function forceActive() {
        if (isset($_SESSION['email'])) {
            $Usuario = new User ($_SESSION['email']);
            $activo = (boolean) $Usuario -> activo;
            if (!$activo) {
                header("Location: /verificacion.php?email=".$Usuario -> email);
                exit;
            } 
        } else {
            header("Location: /acceso.php");
            exit;
        }
    }

    // fuerzo a mantenerle dentro, no puede acceder al login, registro si está logueado
    public static function forceIn() {
        if (isset($_SESSION['email'])) {
            $Usuario = new User($_SESSION['email']);
            $activo = (boolean) $Usuario -> activo;
            if (!$activo) {
                header("Location: /verificacion.php?email=".$Usuario -> email);
                exit;
            } else {
                header("Location: /home.php");
                exit;
            }
        }
    }
}

?>