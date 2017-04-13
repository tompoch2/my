<?php
    require_once('conexion.php');

    session_start();
    header('Content-Type: text/html; charset=ISO-8859-1');

    $id_controlador = $_REQUEST['id_controlador'];
//    $id_controlador = "1";

    mysql_select_db($database_conexion, $conexion);
    $query_el_contro = "SELECT * FROM objetos WHERE id_controlador = '$id_controlador'";
    $el_contro = mysql_query($query_el_contro, $conexion) or die(mysql_error());
    $row_el_contro = mysql_fetch_assoc($el_contro);
    $totalRows_el_contro = mysql_num_rows($el_contro);
//    echo $id_controlador;

    if (!isset($_SESSION["username"]))
    {
        header('Location: index.php');
    }

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900))
    {
        session_unset();
        session_destroy();
        header('Location: index3.php');
    }

    $el_user =  $_SESSION["username"];

    $query_el_userr  = "SELECT id_usuario FROM usuario WHERE usuario = '$el_user'";
    $el_userr = mysql_query($query_el_userr, $conexion) or die(mysql_error());
    $row_el_userr = mysql_fetch_assoc($el_userr);

    $id_usuario = $row_el_userr['id_usuario'];

    $query_el_admin  = "SELECT * FROM usuarios_perfiles WHERE id_usuario = '$id_usuario' AND id_perfil ='1'";
    $el_admin = mysql_query($query_el_admin, $conexion) or die(mysql_error());
    $row_el_admin = mysql_fetch_assoc($el_admin);
    $totalRows_el_admin = mysql_num_rows($el_admin);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mosquitto test client</title>
        <script type="text/javascript" src="../paho/js/mqttws31.js"></script>
        <script src="../paho/js/jquery-1.11.2.min.js"></script>
        <script src="../paho/js/iphone-style-checkboxes.js"></script>
        <link rel="stylesheet" href="../paho/css/style.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="../plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="../css/index.css">
        <link rel="stylesheet" type="text/css" href="../css/sweetalert.css">

        <link rel="stylesheet" href="../plugins/toggles/css/toggles.css">

        <link rel="stylesheet" href="../plugins/toggles/css/toggles.css">
        <link rel="stylesheet" href="../plugins/toggles/css/themes/toggles-all.css">

        <script type="text/javascript">

            var hostname = "cd3307.myfoscam.org";
            var port = 1884;
            var cliente = randomString();
            var client = new Paho.MQTT.Client(hostname, Number(port), cliente);

            client.onConnectionLost = onConnectionLost;
            client.onMessageArrived = onMessageArrived;

            client.connect(
            {
                onSuccess: onConnect
            });

            function onConnect()
            {
                console.log("Conectado");
                client.subscribe("#");

                lee_el_status();
            }

            function doFail(e)
            {
                console.log(e);
            }

            function onConnectionLost(responseObject)
            {
                if (responseObject.errorCode !== 0)
                {
                    console.log("Conexion Perdida:" + responseObject.errorMessage);
                }
            }

            function onMessageArrived(message)
            {
                var topico = message.destinationName;
                console.log(topico);
                var el_topico  = topico.replace(/\//g, "-");

                if (message.payloadString == "Off")
                {
//                    var aaa = $('div[id='+el_topico+']');
//                    console.log(aaa);

                    if ($('div[id='+el_topico+']').is(':checked'))
                    {                        $('input[name='+el_topico+']').prop('checked',false).change();
                    }
                    if ($('input[name='+el_topico+']').is(':checked'))
                    {
                        $('input[name='+el_topico+']').prop('checked',false).change();
                    }
                }
                if (message.payloadString == "On")
                {
//                    var aaa = $('div[id='+el_topico+']');
//                    console.log(aaa);
                    if (!$('input[name='+el_topico+']').is(':checked'))
                    {
                        $('input[name='+el_topico+']').prop('checked',true).change();
                    }
                }
            }

            function send()
            {
                if (!client)
                {
                    return;
                }
                var message = new Paho.MQTT.Message("Hola " + cliente);
                message.destinationName = "Cliente";
                client.send(message);
            }

            function resetea()
            {
                if (!client)
                {
                    return;
                }
                else
                {

                }
                var message = new Paho.MQTT.Message("Hola " + cliente);
                message.destinationName = "Cliente";
                client.send(message);
            }

            function randomString()
            {
                var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
                var string_length = 8;
                var randomstring = '';
                for (var i=0; i<string_length; i++)
                {
                    var rnum = Math.floor(Math.random() * chars.length);
                    randomstring += chars.substring(rnum,rnum+1);
                }
                return randomstring;
            }

            function lee_el_status()
            {
                var los_elementos2 = document.getElementsByTagName('input');

                for (var i=0; i<los_elementos2.length; i++)
                {
                    (function(i)
                    {
                        setTimeout(function()
                        {
                            var status_del_elemento2 = los_elementos2[i].name;
                            var el_status_del_elemento2  = status_del_elemento2.replace(/-/g, "/");

                            var message = new Paho.MQTT.Message("el_status_node");
                            message.destinationName = el_status_del_elemento2;
                            client.send(message);
                        }, 1000 * i);
                    }(i));
                }
            }
        </script>

        <script>
            $(document).ready(function()
            {
                $(':checkbox').iphoneStyle(
                {
                    onChange: function(elem,val)
                    {
                        var el_nombre = $(elem).attr('name');

                        var topico2 = el_nombre;
                        var el_topico2  = topico2.replace(/-/g, "/");

                        if (val)
                        {
                            var message = new Paho.MQTT.Message("On");
                            message.destinationName = el_topico2;
                            client.send(message);
                        }
                        else
                        {
                            var message = new Paho.MQTT.Message("Off");
                            message.destinationName = el_topico2;
                            client.send(message);
                        }
                    }
                });

                $('.toggles').toggles(
                {
                    drag: true,click: true,text:{on: 'ON',off: 'OFF'},on: true, animate: 250, easing: 'swing', checkbox: null, clicker: null, width: 50, height: 22, type: 'select'
                });


                $('.toggles').on('toggle', function(e, active)
                {
                    var myToggle = $('.toggles').data('toggles');

//                    console.log(myToggle.active); // estado

                    var idd = e.currentTarget.id;

                    var topico22 = idd;
                    var el_topico22  = topico22.replace(/-/g, "/");

//                    console.log(idd);
//                    console.log(el_topico22);
                    if (active)
                    {
//                        console.log(idd + ' ENCENDIDO!');
                        var message = new Paho.MQTT.Message("On");
                        message.destinationName = el_topico22;
                        client.send(message);
                    }
                    else
                    {
//                        console.log(idd + ' APAGADO!');
                        var message = new Paho.MQTT.Message("Off");
                        message.destinationName = el_topico22;
                        client.send(message);
                    }
                })
            });
        </script>
        <script>
            function resett(obj)
            {
                var idd = obj.id;

//console.log("idd " + idd);

                idd = idd.replace("_restart", "");
                idd = idd.replace("-", "/")
                idd = idd.replace("-", "/")
                idd = idd.replace("-", "/")
                idd = idd.replace("-", "/")
                idd = idd.replace("-", "/")
                idd = idd.replace("-", "/")
                idd = idd.replace("-", "/")
                idd = idd.replace("-", "/")
                idd = idd.replace("-", "/")

                var largo = idd.length;

                var largo2 = largo - 2;

                var res = idd.substring(0, largo2);

//console.log("res " + res);

                var message = new Paho.MQTT.Message("restart");
                message.destinationName = res;
                client.send(message);

                console.log(message);
            }
        </script>
    </head>

    <body>
<?php
    $objj = "";
    if ($totalRows_el_contro == 0)
    {
        $objj = "<div class='box-body'>";
        $objj .= "<span>Este Dispositivo no esta Configurado.</span></br>";
        $objj .= "<span>Ingrese al men&uacute; :<br><br><strong>Configuraci&oacute;n &rarr; Dispositivos &rarr; Configurar Dispositivo.</strong>
        <br><br> y seleccionelo de la lista.</span>";
        $objj .= "</div>";
    }
    else
    {
        do
        {
            $id_objeto = $row_el_contro['id_objeto'];
            $nombre_objeto = $row_el_contro['nombre'];
            $nombre_corto_objeto = $row_el_contro['nombre_corto'];
            $mqtt_full_objeto = $row_el_contro['mqtt_full'];

            $mqtt_full_objeto2 = str_replace("/","-",$mqtt_full_objeto);

            $mqtt_full_objeto2_restart = $mqtt_full_objeto2 . "_restart";

//            $mqtt_full_objeto2_restart = "_2" . "";

            $objj .= "
            <div class='box-body'>
                <table style='width:100%'>
                    <tr>
                        <td>
                            <span>" . $nombre_corto_objeto . "</span>
                        </td>
                        <td style='width=60' align='center'>
                            <!--<input name='" . $mqtt_full_objeto2 . "' checked='checked' class='yesno' type='checkbox'/>-->
                            ";
            $objj .= "
                        </td>
                        <td>
                            <div class='toggles toggle-light' id='" . $mqtt_full_objeto2 . "'>
                        </td>
                    </tr>
                </table>
            </div>";
        }
        while ($row_el_contro = mysql_fetch_assoc($el_contro));

        if ($totalRows_el_admin == "1")
        {
            $objj .= "<center>
            <input id='" . $mqtt_full_objeto2_restart . "' onClick='resett(this)' type='button' value='Reiniciar' class='btn btn-success'></center>";
        }
        else
        {
            //$objj .= "<center></center>";
        }

    }
    print($objj);
?>
        <script src="../plugins/toggles/toggles.js" type="text/javascript"></script>
    </body>
</html>
