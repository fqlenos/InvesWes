<?php

if (!defined('__CONFIG__')) {
    exit("Error, no hay archivo de configuración.");
}

class Get {

    protected static $con;

    public static function findEmail($email, $return_assoc = false) {
        $con = DB::getConnection();
        $email = (string) Filter::String($email);

        $find_email = $con -> prepare("SELECT usuario_id, email, password, hash, activo FROM usuarios WHERE email = LOWER(:email) LIMIT 1");
        $find_email -> bindParam(":email", $email, PDO::PARAM_STR);
        $find_email -> execute();

        if ($return_assoc) return $find_email -> fetch(PDO::FETCH_ASSOC);

        $email_found = (boolean) $find_email -> rowCount();
        return $email_found;
    }

    public static function getChannelData($nombre, $return_assoc = false) {
        $con = DB::getConnection();
        $nombre = (string) Filter::String($nombre);

        $find_channel = $con -> prepare("SELECT canal_id, nombre, descripcion, precio_diario, wallet, activo FROM canales WHERE nombre = LOWER(:nombre) LIMIT 1");
        $find_channel -> bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $find_channel -> execute();

        if ($return_assoc) return $find_channel -> fetch(PDO::FETCH_ASSOC);

        $channel_found = (boolean) $find_channel -> rowCount();
        return $channel_found;
    }

    public static function findSubscriber($usuario_id, $canal_id, $return_assoc = false) {
        $con = DB::getConnection();
        $usuario_id = (integer) Filter::Int($usuario_id);
        $canal_id = (integer) Filter::Int($canal_id);

        $find_subscription = $con -> prepare("SELECT u.nombre, s.fecha_fin, s.admin FROM usuarios u LEFT JOIN suscripciones s ON u.usuario_id = s.usuario_id WHERE s.canal_id = :canal_id AND s.usuario_id = :usuario_id LIMIT 1");
        $find_subscription -> bindParam(":canal_id", $canal_id, PDO::PARAM_INT);
        $find_subscription -> bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $find_subscription -> execute();

        if ($return_assoc) return $find_subscription -> fetch(PDO::FETCH_ASSOC);

        $subscription_found = (boolean) $find_subscription -> rowCount();
        return $subscription_found;
    }

    public static function countSubscribers($canal_id) {
        $con = DB::getConnection();
        $canal_id = (integer) Filter::Int($canal_id);

        $subscribers = $con -> prepare("SELECT COUNT(nombre) FROM usuarios u LEFT JOIN suscripciones s ON u.usuario_id = s.usuario_id WHERE s.canal_id = :canal_id AND s.admin = 0");
        $subscribers -> bindParam(":canal_id", $canal_id, PDO::PARAM_INT);
        $subscribers -> execute();

        $num_subscribers = (integer) $subscribers -> fetchColumn();

        return $num_subscribers;
    }

    public static function getAllChannels() {
        $con = DB::getConnection();

        $getAll = $con -> prepare("SELECT canal_id, nombre, descripcion, precio_diario, wallet, activo FROM canales ORDER BY canal_id");
        $getAll -> execute();

        return $getAll -> fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMyChannels($usuario_id) {
        $con = DB::getConnection();

        $usuario_id = (integer) Filter::Int($usuario_id);

        $getAll = $con -> prepare("SELECT c.nombre, c.descripcion, c.precio_diario, c.wallet, c.activo FROM canales c LEFT JOIN suscripciones s ON c.canal_id = s.canal_id AND s.admin = 0 WHERE s.usuario_id = :usuario_id");
        $getAll -> bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $getAll -> execute();

        return $getAll -> fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMyChannel($usuario_id){
        $con = DB::getConnection();

        $usuario_id = (integer) Filter::Int($usuario_id);

        $getAll = $con -> prepare("SELECT c.canal_id FROM canales c LEFT JOIN suscripciones s ON c.canal_id = s.canal_id WHERE s.usuario_id = :usuario_id AND s.admin = 1 LIMIT 1");
        $getAll -> bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $getAll -> execute();

        return $getAll -> fetch(PDO::FETCH_ASSOC);
    }

    public static function findChanneller($usuario_id, $return_assoc = false) {
        $con = DB::getConnection();

        $usuario_id = (integer) Filter::Int($usuario_id);

        $findChanneller = $con -> prepare("SELECT canal_id FROM suscripciones WHERE usuario_id = :usuario_id AND admin = 1 LIMIT 1");
        $findChanneller -> bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $findChanneller -> execute();

        if ($return_assoc) return $findChanneller -> fetch(PDO::FETCH_ASSOC);

        $foundChanneller = (boolean) $findChanneller -> rowCount();

        return $foundChanneller;
    }

    public static function channelName($canal_id, $return_assoc = false) {
        $con = DB::getConnection();

        $canal_id = (integer) Filter::Int($canal_id);
        
        $findName = $con -> prepare("SELECT nombre FROM canales WHERE canal_id = :canal_id LIMIT 1");
        $findName -> bindParam(":canal_id", $canal_id, PDO::PARAM_INT);
        $findName -> execute();

        if ($return_assoc) return $findName -> fetch(PDO::FETCH_ASSOC);

        $name_found = (boolean) $findName -> rowCount();
        return $name_found;
    }
}

?>