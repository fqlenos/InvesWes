<?php

define('__CONFIG__', true);
require_once('inc/config.php');

$template = file_get_contents("templates/verificacion.html");

if (isset($_GET['email'])) {
    $email = (string) Filter::String($_GET['email']);
    $email_found = Get::findEmail($email, true);

    if ($email_found && strcmp($email_found['activo'], '0') == 0) {

        $template_new = str_replace(
            Array(
                "##email##",
                '##footer##'
            ),
            Array(
                $email,
                file_get_contents("templates/footer.php")
            ),
            $template
        );

        print($template_new);

    } else {
        header("Location: /inicio.php");
    }
} else {
    header("Location: /inicio.php");
}


?>
