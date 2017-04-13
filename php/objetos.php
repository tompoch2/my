<?php
    require_once('conexion.php');

    $id_controlador = $_REQUEST['id_controlador'];
//    $id_controlador = "1";

    mysql_select_db($database_conexion, $conexion);
    $query_el_contro = "SELECT * FROM objetos WHERE id_controlador = '$id_controlador'";
    $el_contro = mysql_query($query_el_contro, $conexion) or die(mysql_error());
    $row_el_contro = mysql_fetch_assoc($el_contro);
    $totalRows_el_contro = mysql_num_rows($el_contro);
//    echo $id_controlador;
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
        console.log("onConnect");
        client.subscribe("#");

        lee_el_status();
    }

    function onConnectionLost(responseObject)
    {
        if (responseObject.errorCode !== 0)
        {
            console.log("onConnectionLost:" + responseObject.errorMessage);
        }
    }

    function onMessageArrived(message)
    {
        var topico = message.destinationName;
        var el_topico  = topico.replace(/\//g, "-");

        if (message.payloadString == "Off")
        {
            if ($('input[name='+el_topico+']').is(':checked'))
            {
                $('input[name='+el_topico+']').prop('checked',false).change();
            }
        }
        if (message.payloadString == "On")
        {
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
//                var los_elementos = document.getElementsByTagName('input');

//                for (var i=0; i<los_elementos.length; i++)
//                {
//                    if (los_elementos[i].type == 'checkbox')
//                    {
//                        los_elementos[i].checked = false;
//                    }
//                    var status_del_elemento = los_elementos[i].name;

//                    var el_status_del_elemento  = status_del_elemento.replace(/-/g, "/");
//                }

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
});
        </script>
    </head>

    <body>
<?php
    $objj = "";
    if ($totalRows_el_contro == 0)
    {
        $objj = "<div class='box-body'>";
        $objj .= "<span>Este Dispositivo no esta Configurado.</span></br>";
        $objj .= "<span>Ingrese a <strong>Configuración -> Dispositivos</strong>.</span>";
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

            $objj .= "
            <div class='box-body'>
                <table style='width:100%'>
                    <tr>
                        <td>
                            <span>" . $nombre_corto_objeto . "</span>
                        </td>
                        <td style='width=60' align='center'>
                            <input name='" . $mqtt_full_objeto2 . "' checked='checked' class='yesno' type='checkbox'/>
                        </td>
                    </tr>
                </table>
            </div>";
        }
        while ($row_el_contro = mysql_fetch_assoc($el_contro));
    }
    print($objj);
?>
    </body>
</html>