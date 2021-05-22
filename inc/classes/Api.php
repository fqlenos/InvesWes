<?php 

if(!defined('__CONFIG__')){
    exit('No hay archivo de configuración.');
}

class Api {

    public static function loadTickers() {

        $uri = "https://cex.io/api/last_prices/USD";
        $curl = curl_init($uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $tickers = json_decode($response, true);

        $con = DB::getConnection();

        $updateDB = $con -> prepare("INSERT IGNORE INTO valores (ticker) VALUES (LOWER(:ticker))");
        foreach ($tickers['data'] as $t => $u) {

            $updateDB -> bindParam(":ticker", $u['symbol1'], PDO::PARAM_STR);
            $updateDB -> execute();
            
        }
    }

    public static function getTickers() {

        $con = DB::getConnection();

        $getTickers = $con -> prepare("SELECT ticker FROM valores ORDER BY ticker");
        $getTickers -> execute();

        return $getTickers -> fetchAll(PDO::FETCH_ASSOC);

    }

    public static function findTicker($ticker, $return_assoc = false) {

        $con = DB::getConnection();
        $ticker = (string) Filter::String($ticker);

        $findTicker = $con -> prepare("SELECT valor_id, ticker FROM valores WHERE ticker = LOWER(:ticker) LIMIT 1");
        $findTicker -> bindParam(":ticker", $ticker, PDO::PARAM_STR);
        $findTicker -> execute();

        if ($return_assoc) return $findTicker -> fetch(PDO::FETCH_ASSOC);

        $found_ticker = (boolean) $findTicker -> rowCount();
        return $found_ticker;
    }

    public static function findTickerName($valor_id, $return_assoc = false) {
        $con = DB::getConnection();
        $valor_id = (integer) Filter::Int($valor_id);

        $findTicker = $con -> prepare("SELECT valor_id, ticker FROM valores WHERE valor_id = LOWER(:valor_id) LIMIT 1");
        $findTicker -> bindParam(":valor_id", $valor_id, PDO::PARAM_INT);
        $findTicker -> execute();

        if ($return_assoc) return $findTicker -> fetch(PDO::FETCH_ASSOC);

        $found_ticker = (boolean) $findTicker -> rowCount();
        return $found_ticker;
    }

    public static function findTickerValue($ticker) {
        
        $ticker = (string) Filter::String($ticker);
        $uri =  "https://cex.io/api/ticker/".strtoupper($ticker)."/USD";

        $curl = curl_init($uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $resp = json_decode($response, true);

        /*
        $resp['low']
        $resp['high']
        $resp['last']
        $resp['volume']
        $resp['volume30']
        $resp['bid']
        $resp['ask']
        */

        return $resp['last'];

    }

    public static function getCanalCartera($canal_id) {
        $con = DB::getConnection();

        $canal_id = (integer) Filter::Int($canal_id);

        $findCartera = $con -> prepare("SELECT canal_id, valor_id, precio_compra, cantidad FROM cartera_canal WHERE canal_id = :canal_id");
        $findCartera -> bindParam(":canal_id", $canal_id, PDO::PARAM_INT);
        $findCartera -> execute();

        return $findCartera -> fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUsuarioCartera($usuario_id) {
        $con = DB::getConnection();

        $usuario_id = (integer) Filter::Int($usuario_id);

        $findCartera = $con -> prepare("SELECT usuario_id, valor_id, precio_compra, cantidad FROM cartera_usuario WHERE usuario_id = :usuario_id");
        $findCartera -> bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $findCartera -> execute();

        return $findCartera -> fetchAll(PDO::FETCH_ASSOC);
    }

    
}


?>