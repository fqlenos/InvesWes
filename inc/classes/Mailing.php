<?php

if (!defined('__CONFIG__')) {
    exit("Error, no hay archivo de configuración.");
}

class Mailing {

    public static function SendVerificationMail($emailDst, $name, $token, $hash) {

        require("PHPMailer/PHPMailerAutoload.php");

        $mail = new PHPMailer();
        $mail -> isSMTP();  
        $mail -> Host = 'smtp.gmail.com';
        $mail -> Port = 587;
        $mail -> SMTPAuth = true;
        $mail -> Username = "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<";
        $mail -> Password = "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<";
        $mail -> SMTPSecure = "tls";
        $mail -> CharSet = "UTF-8";

        $mail -> setFrom("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<@gmail.com","InvesWes Support");
        $mail -> addAddress($emailDst, $name);
        $mail -> Subject = "Correo de verificación";
        $mail -> Body = "
        Hola ".mb_convert_case($name, MB_CASE_TITLE, "UTF-8").":
            
        Nos alegra verte en InvesWes, ¡ahora solo nos queda verificar que realmente eres tú!
        Si no te has registrado en nuestra web simplemente ignora este correo.

        -------------------------------------------------------------------------------------
        Correo electrónico de acceso: ".$emailDst."
        Código de verificación: ".$token."
        -------------------------------------------------------------------------------------

        Recuerda: no compartas el código anterior con nadie.
        
        
        © 2021 InvesWes Group.
        ";

        if (!$mail -> send()) {

            return "error";

        } else {
            
            return "done";

        }
    }
}