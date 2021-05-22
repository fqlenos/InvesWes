<?php

###################################################
# Todo lo relacionado con pagos y wallets         #
###################################################

if (!defined('__CONFIG__')) {
    exit("Error, no hay archivo de configuración.");
}

class Pay {

    public static function InvesWesWallet() {

        // Se obtiene el wallet del canal, necesario para crear wallets temporales, transferencias...

        $con = DB::getConnection();

        $page_wallet_id = $con -> prepare("SELECT wallet FROM admin WHERE id = 1 LIMIT 1");
        $page_wallet_id -> execute();

        $page_wallet = $page_wallet_id -> fetch(PDO::FETCH_ASSOC);
        
        return $page_wallet['wallet'];

    }

    public static function getWalletValue($wallet) {

        // filtro contra SQLi
        $wallet = Filter::String($wallet);

        $uri = "https://coin.tlm.unavarra.es:10001/api/view.php?w=".$wallet;
        $curl = curl_init($uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        
        $resp_array = json_decode($response, true);
        $msg = $resp_array['msg'];

        if (strcmp($msg, "no wallet") != 0) {
            $cantidad = substr($resp_array['wallet']['value'],0,-3); // /1000
        } else {
            $cantidad = null;
        }
        
        return array ($msg, $cantidad);

    }

    public static function transferTLM($cant, $wallet_src, $wallet_dst = null) {

        // filtro contra SQLi
        $cant = Filter::Int($cant);
        $cant = str_pad($cant, strlen($cant) + 3, 0); // x1000
        $wallet_src = Filter::String($wallet_src);
        
        if (is_null($wallet_dst)) {
            $wallet_dst = explode("-", self::InvesWesWallet())[0];
        } else {
            $wallet_dst = (integer) Filter::Int($wallet_dst);
        }

        $uri = "https://coin.tlm.unavarra.es:10001/api/transfer.php?srcw=".$wallet_src."&dstw=".$wallet_dst."&value=".$cant;
        $curl = curl_init($uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $resp_array = json_decode($response,true);
        $resp = strcmp($resp_array['msg'],"transfer done") == 0;

        if($resp) {
            $resp = (boolean) true;
        } else {
            $resp = (boolean) false;
        }
        
        return $resp;

    }

    public static function createWalletTmp($admin_wallet) {

        // filtro contra SQLi
        $admin_wallet = Filter::String($admin_wallet);

        $uri = "https://coin.tlm.unavarra.es:10001/api/create.php?w=".$admin_wallet; // admin wallet = la del canal, necesaria para crear una cartera
        $curl = curl_init($uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $resp_array = json_decode($response,true);

        $wallet_id = $resp_array['wallet']['id'];
        $wallet_secret = $wallet_id."-".$resp_array['wallet']['secret'];
        $value = $resp_array['wallet']['value'];
         
        return array ($wallet_id, $wallet_secret, $value);

    }

    public static function addTemporalWallet($email, $wallet) {

        $con = DB::getConnection();

        // filtrado de variables
        $email = (string) Filter::String($email);
        $wallet = (string) Filter::String($wallet);

        $email_found = (boolean) Get::findEmail($email, false);
        
        if ($email_found) {
            $update = $con -> prepare("UPDATE usuarios SET wallet = :wallet WHERE email = LOWER(:email) LIMIT 1");
            $update -> bindParam(":wallet", $wallet, PDO::PARAM_STR);
            $update -> bindParam(":email", $email, PDO::PARAM_STR);
            $update -> execute();
        }

    }

    public static function removeTemporalWallet($email) {

        $con = DB::getConnection();
        $email = (string) Filter::String($email);

        $remove_wallet = $con -> prepare("UPDATE usuarios SET wallet = NULL WHERE email = LOWER(:email)");
        $remove_wallet -> bindParam(":email", $email, PDO::PARAM_STR);
        $remove_wallet -> execute();

    }
}

?>