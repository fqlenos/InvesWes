<?php

define('__CONFIG__', true);
require_once('inc/config.php');
Force::forceActive();

$Usuario = new User($_SESSION['email']);
$template = file_get_contents("templates/canal.html");
$propietario = (boolean) Get::findChanneller($Usuario->usuario_id);

if ($propietario) {
    $canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
    $canal_name = Get::channelName($canal_id, true);

    $template = str_replace(
        "##topnav##",
        '
        <img src="/imgs/inveswes2.png" alt="">
        <a href="/home.php" >Home</a>
        <a href="/cartera.php">Mi cartera</a>
        <a href="/suscripciones.php">Suscripciones</a>
         
       <div class="topnav-right">
            <a href="/canal.php?canal='.$canal_name['nombre'].'">Mi canal</a>
            <a href="/cuenta.php">Mi cuenta</a>
            <a href="/logout.php">Cerrar sesión</a>
        </div>
        ',
        $template
    );
} else {
    $template = str_replace(
        "##topnav##",
        '
        <img src="/imgs/inveswes2.png" alt="">
        <a href="/home.php" >Home</a>
        <a href="/cartera.php">Mi cartera</a>
        <a href="/suscripciones.php">Suscripciones</a>
         
       <div class="topnav-right">
            <a href="/crear-canal.php">Crear canal</a>
            <a href="/cuenta.php">Mi cuenta</a>
             <a href="/logout.php">Cerrar sesión</a> 
        </div>
        ',
        $template
    );
}

if (isset($_GET['canal'])) {
    
    $nombre = (string) Filter::String($_GET['canal']);
    $name_found = Get::getChannelData($nombre, true);

    $suscrito = (boolean) Get::findSubscriber($Usuario->usuario_id, $name_found['canal_id']);

    if ($suscrito) {
        
        $admin = Get::findSubscriber($Usuario->usuario_id, $name_found['canal_id'], true)['admin'];
        
        if (!$admin) {
            $now = date("Y-m-d H:i:s");
            // to timestamp for comparing
            $nowTimestamp = strtotime($now);
            $fecha_fin = strtotime(Get::findSubscriber($Usuario->usuario_id, $name_found['canal_id'], true)['fecha_fin']);
            
            if ($nowTimestamp > $fecha_fin) {
                print($Usuario->usuario_id);
                print("\n". $name_found['canal_id']);
                $con = DB::getConnection();
                $update = $con -> prepare("DELETE FROM suscripciones WHERE canal_id = :canal_id AND usuario_id = :usuario_id");
                $update -> bindParam(":canal_id", $name_found['canal_id'], PDO::PARAM_INT);
                $update -> bindParam(":usuario_id", $Usuario -> usuario_id, PDO::PARAM_INT);
                $update -> execute();

                $suscrito = (boolean) Get::findSubscriber($Usuario->usuario_id, $name_found['canal_id']);
            }
        }
    }
    
    if ($name_found && !$suscrito) {

        if (isset($_GET['suscripcion'])) {
            
            $Usuario = new User($_SESSION['email']);

            if (strcmp($Usuario -> wallet, "") != 0) {

                if (strcmp(explode("-", $Usuario -> wallet)[2], $name_found['nombre'] == 0)) {

                    $wallet_exists = Pay::getWalletValue(explode("-", $Usuario->wallet)[0]);

                    if (strcmp($wallet_exists[0], "wallet info") === 0) {

                        if ($wallet_exists[1] < $name_found['precio_diario']) {
                            if ($wallet_exists[1] < 1) {
                                $wallet_exists[1] = 0;
                            }

                            $template_new = str_replace(
                                Array(
                                    "##nombre##",
                                    "##descripcion##",
                                    "##suscriptores##",
                                    "##precio_diario##",
                                    "##wallet-info##",
                                    "##boton##",
                                    "##bajabtn##",
                                    "##footer##" 
                                ),
                                Array(
                                    mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                                    $name_found['descripcion'],
                                    Get::countSubscribers($name_found['canal_id']),
                                    $name_found['precio_diario'],
                                    '
                                        Ingresa '.$name_found['precio_diario'].' TLMCoins al wallet <b>'.explode("-", $Usuario -> wallet)[0].'</b><br>
                                        El wallet ahora tiene '.$wallet_exists[1].' TLMCoins.
                                    ',
                                    '',
                                    '',
                                    file_get_contents("templates/footer.php")
                                ),
                                $template
                            );
                
                            print($template_new);

                        } else if ($wallet_exists[1] === $name_found['precio_diario']) {

                            $resp = Pay::transferTLM($name_found['precio_diario'], explode("-", $Usuario->wallet)[0]."-".explode("-", $Usuario->wallet)[1], $name_found['wallet']);

                            if ($resp) {
                                
                                $con = DB::getConnection();
                                $createSubscription = $con -> prepare("INSERT INTO suscripciones (usuario_id, canal_id) VALUES (:usuario_id, :canal_id)");
                                $createSubscription -> bindParam(":usuario_id", $Usuario->usuario_id, PDO::PARAM_INT);
                                $createSubscription -> bindParam(":canal_id", $name_found['canal_id'], PDO::PARAM_INT);
                                $createSubscription -> execute();

                                Pay::removeTemporalWallet($Usuario->email);

                                header("Location: /canal.php?canal=".$name_found['nombre']);

                            } else {
                                
                                $template_new = str_replace(
                                    Array(
                                        "##nombre##",
                                        "##descripcion##",
                                        "##suscriptores##",
                                        "##precio_diario##",
                                        "##wallet-info##",
                                        "##boton##",
                                        "##footer##" 
                                    ),
                                    Array(
                                        mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                                        $name_found['descripcion'],
                                        Get::countSubscribers($name_found['canal_id']),
                                        $name_found['precio_diario'],
                                        '
                                        <div class="description" style="color: orange;">
                                            Parece que hay un error con la transacción.<br>
                                            Comprueba que el wallet '.explode("-", $Usuario -> wallet)[0].' siga activo.<br>
                                            También puede ser que el canal no admita pagos en estos momentos.<br>
                                            Inténtalo de nuevo más tarde.
                                        </div>
                                        ',
                                        '',
                                        file_get_contents("templates/footer.php")
                                    ),
                                    $template
                                );
                    
                                print($template_new);

                            }

                        } else if ($wallet_exists[1] > $name_found['precio_diario']) {
                            
                            $template_new = str_replace(
                                Array(
                                    "##nombre##",
                                    "##descripcion##",
                                    "##suscriptores##",
                                    "##precio_diario##",
                                    "##wallet-info##",
                                    "##boton##",
                                    "##footer##" 
                                ),
                                Array(
                                    mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                                    $name_found['descripcion'],
                                    Get::countSubscribers($name_found['canal_id']),
                                    $name_found['precio_diario'],
                                    '
                                    <div class="description" style="color: orange;">
                                        El wallet ahora tiene '.$wallet_exists[1].' TLMCoins.<br><br>
                                        Retira las monedas sobrantes, solo aceptamos la cantidad exacta.
                                    </div>
                                    <div class="retirar">
                                        <div class="block">
                                            <label><span>Cantidad a retirar</span>
                                                <input type="number" min="0" name="cantidad" id="cantidad">
                                            </label>
                                            <label><span>Wallet destino</span>
                                                <input type="number" min="0" name="wallet_dst" id="wallet_dst">
                                            </label>
                                        </div>
                                        <button class="btn" type="submit" class="retirar_sobrante" id="retirar_sobrante">Retirar sobrante</button>
                                    </div>
                                    ',
                                    '',
                                    file_get_contents("templates/footer.php")
                                ),
                                $template
                            );
                
                            print($template_new);

                        }

                    } else {

                        // InvesWesWallet() es la cartera oficial de InvesWes, con único fin: crear carteras temporales
                        $wallet_tmp = Pay::createWalletTmp(Pay::InvesWesWallet());
                        $wallet = $wallet_tmp[0];
                        $wallet_full = $wallet_tmp[1];
                        $wallet_value = $wallet_tmp[2];

                        Pay::addTemporalWallet($Usuario -> email, $wallet_full."-".$name_found['nombre']);
                        
                        $template_new = str_replace(
                            Array(
                                "##nombre##",
                                "##descripcion##",
                                "##suscriptores##",
                                "##precio_diario##",
                                "##wallet-info##",
                                "##boton##",
                                "##footer##" 
                            ),
                            Array(
                                mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                                $name_found['descripcion'],
                                Get::countSubscribers($name_found['canal_id']),
                                $name_found['precio_diario'],
                                '',
                                '
                                <li><a href="canal.php?canal='.$name_found['nombre'].'&suscripcion" class="btn" type="submit" name="diaria" id="diaria">Suscribirme</a></li>
                                ',
                                file_get_contents("templates/footer.php")
                            ),
                            $template
                        );
            
                        print($template_new);
                    }
                    

                } else {
                    
                    $template_new = str_replace(
                        Array(
                            "##nombre##",
                            "##descripcion##",
                            "##suscriptores##",
                            "##precio_diario##",
                            "##wallet-info##",
                            "##boton##",
                            "##footer##" 
                        ),
                        Array(
                            mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                            $name_found['descripcion'],
                            Get::countSubscribers($name_found['canal_id']),
                            $name_found['precio_diario'],
                            '
                            <div class="description" style="color: orange;">
                                ¡Atención! Parece que tenías pagos pendientes, hemos eliminado todos los procesos para poder continuar con la suscripción de '.$name_found['nombre'].'<br>
                                Ingresa '.$name_found['precio_diario'].' TLMCoins al wallet <b>'.$wallet.'</b>
                            </div>
                            ',
                            '',
                            file_get_contents("templates/footer.php")
                        ),
                        $template
                    );
        
                    print($template_new);

                }

            } else {

                // InvesWesWallet() es la cartera oficial de InvesWes, con único fin: crear carteras temporales
                $wallet_tmp = Pay::createWalletTmp(Pay::InvesWesWallet());
                $wallet = $wallet_tmp[0];
                $wallet_full = $wallet_tmp[1];
                $wallet_value = $wallet_tmp[2];

                Pay::addTemporalWallet($Usuario -> email, $wallet_full."-".$name_found['nombre']);

                $template_new = str_replace(
                    Array(
                        "##nombre##",
                        "##descripcion##",
                        "##suscriptores##",
                        "##precio_diario##",
                        "##wallet-info##",
                        "##boton##",
                        "##footer##" 
                    ),
                    Array(
                        mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                        $name_found['descripcion'],
                        Get::countSubscribers($name_found['canal_id']),
                        $name_found['precio_diario'],
                        '',
                        '
                        <li><a href="canal.php?canal='.$name_found['nombre'].'&suscripcion" class="btn" type="submit" name="diaria" id="diaria">Suscribirme</a></li>
                        ',
                        file_get_contents("templates/footer.php")
                    ),
                    $template
                );
    
                print($template_new);
            }
        
        } else {

            if (strcmp($Usuario -> wallet, "") != 0) {

                if (strcmp(explode("-", $Usuario -> wallet)[2], $name_found['nombre'] == 0)) {

                    $wallet_exists = Pay::getWalletValue(explode("-", $Usuario->wallet)[0]);

                    if (strcmp($wallet_exists[0], "wallet info") === 0) {
                        if ($wallet_exists[1] < 1) {
                            $wallet_exists[1] = 0;
                        }

                        if ($wallet_exists[1] < $name_found['precio_diario']) {
                            $template_new = str_replace(
                                Array(
                                    "##nombre##",
                                    "##descripcion##",
                                    "##suscriptores##",
                                    "##precio_diario##",
                                    "##wallet-info##",
                                    "##boton##",
                                    "##footer##" 
                                ),
                                Array(
                                    mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                                    $name_found['descripcion'],
                                    Get::countSubscribers($name_found['canal_id']),
                                    $name_found['precio_diario'],
                                    '
                                    
                                        Ingresa '.$name_found['precio_diario'].' TLMCoins al wallet <b>'.explode("-", $Usuario -> wallet)[0].'</b><br>
                                        El wallet ahora tiene '.$wallet_exists[1].' TLMCoins.
                                    
                                    ',
                                    '',
                                    file_get_contents("templates/footer.php")
                                ),
                                $template
                            );
                
                            print($template_new);
                        } else if ($wallet_exists[1] === $name_found['precio_diario']) {

                            $resp = Pay::transferTLM($name_found['precio_diario'], explode("-", $Usuario->wallet)[0]."-".explode("-", $Usuario->wallet)[1], $name_found['wallet']);

                            if ($resp) {
                                
                                $con = DB::getConnection();
                                $createSubscription = $con -> prepare("INSERT INTO suscripciones (usuario_id, canal_id) VALUES (:usuario_id, :canal_id)");
                                $createSubscription -> bindParam(":usuario_id", $Usuario->usuario_id, PDO::PARAM_INT);
                                $createSubscription -> bindParam(":canal_id", $name_found['canal_id'], PDO::PARAM_INT);
                                $createSubscription -> execute();

                                Pay::removeTemporalWallet($Usuario->email);

                                header("Location: /canal.php?canal=".$name_found['nombre']);

                            } else {
                                
                                $template_new = str_replace(
                                    Array(
                                        "##nombre##",
                                        "##descripcion##",
                                        "##suscriptores##",
                                        "##precio_diario##",
                                        "##wallet-info##",
                                        "##boton##",
                                        "##footer##" 
                                    ),
                                    Array(
                                        mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                                        $name_found['descripcion'],
                                        Get::countSubscribers($name_found['canal_id']),
                                        $name_found['precio_diario'],
                                        '
                                        <div class="description" style="color: orange;">
                                            Parece que hay un error con la transacción.<br>
                                            Comprueba que el wallet '.explode("-", $Usuario -> wallet)[0].' siga activo.<br>
                                            También puede ser que el canal no admita pagos en estos momentos.<br>
                                            Inténtalo de nuevo más tarde.
                                        </div>
                                        ',
                                        '',
                                        file_get_contents("templates/footer.php")
                                    ),
                                    $template
                                );
                    
                                print($template_new);

                            }

                        } else if ($wallet_exists[1] > $name_found['precio_diario']) {
                            
                            $template_new = str_replace(
                                Array(
                                    "##nombre##",
                                    "##descripcion##",
                                    "##suscriptores##",
                                    "##precio_diario##",
                                    "##wallet-info##",
                                    "##boton##",
                                    "##footer##" 
                                ),
                                Array(
                                    mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                                    $name_found['descripcion'],
                                    Get::countSubscribers($name_found['canal_id']),
                                    $name_found['precio_diario'],
                                    '
                                    <div class="description" style="color: orange;">
                                        El wallet ahora tiene '.$wallet_exists[1].' TLMCoins.<br><br>
                                        Retira las monedas sobrantes, solo aceptamos la cantidad exacta.
                                    </div>
                                    <div class="retirar">
                                        <div class="block">
                                            <label><span>Cantidad a retirar</span>
                                                <input type="number" min="0" name="cantidad" id="cantidad">
                                            </label>
                                            <label><span>Wallet destino</span>
                                                <input type="number" min="0" name="wallet_dst" id="wallet_dst">
                                            </label>
                                        </div>
                                        <button class="btn" type="submit" name="retirar_sobrante" id="retirar_sobrante">Retirar sobrante</button>
                                    </div>
                                    ',
                                    '',
                                    file_get_contents("templates/footer.php")
                                ),
                                $template
                            );
                
                            print($template_new);

                        }
                    } else {
                        $template_new = str_replace(
                            Array(
                                "##nombre##",
                                "##descripcion##",
                                "##suscriptores##",
                                "##precio_diario##",
                                "##wallet-info##",
                                "##boton##",
                                "##footer##" 
                            ),
                            Array(
                                mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                                $name_found['descripcion'],
                                Get::countSubscribers($name_found['canal_id']),
                                $name_found['precio_diario'],
                                '',
                                '
                                <li><a href="canal.php?canal='.$name_found['nombre'].'&suscripcion" class="btn" type="submit" name="diaria" id="diaria">Suscribirme</a></li>
                                ',
                                file_get_contents("templates/footer.php")
                            ),
                            $template
                        );
        
                        print($template_new);
                    }
                }
            } else {

                $template_new = str_replace(
                    Array(
                        "##nombre##",
                        "##descripcion##",
                        "##suscriptores##",
                        "##precio_diario##",
                        "##wallet-info##",
                        "##boton##",
                        "##footer##" 
                    ),
                    Array(
                        mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                        $name_found['descripcion'],
                        Get::countSubscribers($name_found['canal_id']),
                        $name_found['precio_diario'],
                        '',
                        '
                        <li><a href="canal.php?canal='.$name_found['nombre'].'&suscripcion" class="btn" type="submit" name="diaria" id="diaria">Suscribirme</a></li>
                        ',
                        file_get_contents("templates/footer.php")
                    ),
                    $template
                );

                print($template_new);
            }
        
        }

    } else if ($name_found && $suscrito) {

        $template = file_get_contents("templates/ideas-activas.html");

        $propietario = (boolean) Get::findChanneller($Usuario->usuario_id);

        if ($propietario) {

            $canal_id = Get::findChanneller($Usuario->usuario_id, true)['canal_id'];
            $canal_name = Get::channelName($canal_id, true);

            $template = str_replace(
                "##topnav##",
                '
                <img src="/imgs/inveswes2.png" alt="">
                <a href="/home.php" >Home</a>
                <a href="/cartera.php">Mi cartera</a>
                <a href="/suscripciones.php">Suscripciones</a>
                
                <div class="topnav-right">
                    <a href="/canal.php?canal='.$canal_name['nombre'].'">Mi canal</a>
                    <a href="/cuenta.php">Mi cuenta</a>
                    <a href="/logout.php">Cerrar sesión</a> 
                </div>
                ',
                $template
            );
        } else {
            $template = str_replace(
                "##topnav##",
                '
                <img src="/imgs/inveswes2.png" alt="">
                <a href="/home.php" >Home</a>
                <a href="/cartera.php">Mi cartera</a>
                <a href="/suscripciones.php">Suscripciones</a>
                
            <div class="topnav-right">
                    <a href="/crear-canal.php">Crear canal</a>
                    <a href="/cuenta.php">Mi cuenta</a>
                    <a href="/logout.php">Cerrar sesión</a> 
                </div>
                ',
                $template
            );
        }

        $template = str_replace(
            "##nombre##",
            mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
            $template
        );  

        $admin = Get::findSubscriber($Usuario->usuario_id, $name_found['canal_id'], true)['admin'];

        if (!$admin) {

            $canal_id = Get::getChannelData($name_found['nombre'], true)['canal_id'];
            $valores = Api::getCanalCartera($canal_id);
            foreach($valores as $v) {
                $ticker = Api::findTickerName($v['valor_id'], true)['ticker'];
                $precio_actual = Api::findTickerValue($ticker);
                $template = str_replace(
                    "##tr##",
                    "
                    <tr>
                        <td>".strtoupper($ticker)."/USD</td>
                        <td>".$v['precio_compra']."$</td>
                        <td>".round($precio_actual,3)."$</td>
                        <td>".$v['cantidad']."</td>
                        <td>".round(($precio_actual - $v['precio_compra']) * $v['cantidad'], 2)."$ </td>
                        <td>".round((($precio_actual - $v['precio_compra'])/$v['precio_compra']) * 100, 2)."% </td>
                        <td></td>
                    </tr>
                    ##tr##",
                    $template
                );
            }

            $template_new = str_replace("##tr##", "", $template);

            $template_new = str_replace(
                Array(
                    "##col-izquierda##",
                    "##footer##" 
                ),
                Array(
                    '
                    <h2>'.mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8").'</h2>
                    <div class="para_cartera">
                        <ul>
                            <li>Descripción
                                <p>'.$name_found['descripcion'].'</p>
                            </li>
                            <li>'.(Get::countSubscribers($name_found['canal_id'])).' suscriptores</p></li>
                            <li>Cancelación el '.(Get::findSubscriber($Usuario->usuario_id, $name_found['canal_id'], true)['fecha_fin']).'</li>
                            <li><a href="baja.php?canal='.$name_found['nombre'].'" class="btn">Dame de baja</a></li>
                        </ul>
                    </div>
                    ',
                    file_get_contents("templates/footer.php")
                ),
                $template_new
            );
            
            print($template_new);

        } else {

            $canal_id = Get::getChannelData($name_found['nombre'], true)['canal_id'];
            $valores = Api::getCanalCartera($canal_id);
            foreach($valores as $v) {
                $ticker = Api::findTickerName($v['valor_id'], true)['ticker'];
                $precio_actual = Api::findTickerValue($ticker);
                $template = str_replace(
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
                    $template
                );
            }

            $template_new = str_replace("##tr##", " ", $template);

            $template_new = str_replace(
                Array(
                    "##nombre##",
                    "##col-izquierda##",
                    "##footer##" 
                ),
                Array(
                    mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8"),
                    '
                    <h2>'.mb_convert_case($name_found['nombre'], MB_CASE_TITLE, "UTF-8").' - (propietario)</h2>
                    <p style="padding-right: 1.3rem;">
                        <span>Indica a tus suscriptores en qué valores y con qué precio de compra tienes cada criptomoneda.<br>Las plusvalías se calculan automáticamente en función del precio actual del valor y el precio indicado de compra.</span>
                    </p>
                    <p>
                        <span>Número de suscriptores: <b>'.Get::countSubscribers($name_found['canal_id']).'</b></span><br>
                        <span style="font-weight: bold">Información: wallet <b>'.$name_found['wallet'].'</b> con <b>'.Pay::getWalletValue($name_found['wallet'])[1].'</b> TLMCoins</span>
                    </p>
                    <div class="form">
                        <label><span>Seleccionar un valor</span>
                            <select id="select-crypto">
                                <option selected="selected" disabled>Selecciona un valor</option>
                                ##ticker##
                            </select>
                        </label>
                        <label><span>Precio al que has comprado ($)</span>
                            <input type="number" step="0.01" nombre="precio" id="precio">
                        </label>
                        <label><span>Cantidad</span>
                            <input type="number" step="0.01" nombre="cantidad" id="cantidad">
                        </label>
                        <button class="btn" type="submit" name="guardar" id="guardar_valor_canal">Añadir a la cartera</button>
                    </div>
                    ',
                    file_get_contents("templates/footer.php")
                ),
                $template_new
            );

            $tickers = Api::getTickers();
            foreach($tickers as $t) {
                $template_new = str_replace(
                    "##ticker##",
                    '<option value="'.$t['ticker'].'">'.strtoupper($t['ticker']).'/USD </option>##ticker##
                    '
                    ,
                    $template_new
                );   
            }

            
            print($template_new);

        }

    } else {

        header("Location: /home.php");

    }
} else {
    header("Location: /home.php");
}

?>

