<?php

if(!defined('__CONFIG__')){
    exit('No hay archivo de configuración');
}

class DB {
    protected static $con;

    private function __construct(){
        
        try{
            self::$con = new PDO(
                'mysql:charset=utf8;
                host=db_server;
                port=3306;
                dbname=db',
                'user',
                'password');
            self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$con->setAttribute(PDO::ATTR_PERSISTENT, false);

        }catch(PDOException $e){
            print('No se ha podido conectar a la base de datos.');
            exit;
        }
    }

    public static function getConnection(){
        if(!self::$con){
            new DB();
        }
        return self::$con;
    }
}

?>
