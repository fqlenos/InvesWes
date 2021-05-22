<?php 

if(!defined('__CONFIG__')){
    exit('No hay archivo de configuración.');
}

class Filter {
    // filtra string, evitamos xss, SQLi
    public static function String($string, $html = false) {
        
        if (!$html) {
            $string = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        } else {
            $string = filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        return $string;

    }

    // filtra integer, evitamos xss, SQLi
    public static function Int($integer) {
        return (int) $integer = filter_var($integer, FILTER_SANITIZE_NUMBER_INT);
    }
}
?>