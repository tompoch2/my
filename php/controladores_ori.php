<script type="text/javascript" src="paho/js/mqttws31.js"></script>
<script src="paho/js/jquery-1.11.2.min.js"></script>
<script src="paho/js/iphone-style-checkboxes.js"></script>
<link rel="stylesheet" href="paho/css/style.css">  

<script type="text/javascript">
    var hostname = "192.168.11.100";
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
        console.log("onMessageArrived");
        var topico = message.destinationName;
        var el_topico  = topico.replace(/\//g, "-");
        console.log(el_topico+ " " + message.payloadString);
        if (message.payloadString == "Off")
        {
            console.log("Paso 1 Off");
            if ($('input[name='+el_topico+']').is(':checked'))
            {
                console.log("Paso 2 Off");
                $('input[name='+el_topico+']').prop('checked',false).change();
            }
        }
        if (message.payloadString == "On")
        {
            console.log("Paso 1 On");
            if (!$('input[name='+el_topico+']').is(':checked'))
            {
                console.log("Paso 2 On");
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
            var status_del_elemento2 = los_elementos2[i].name;
            var el_status_del_elemento2  = status_del_elemento2.replace(/-/g, "/");

//                    var message = new Paho.MQTT.Message("el_status_node");
//                    message.destinationName = el_status_del_elemento2;
//                    client.send(message);
        }
    }
</script>

<script>
$(document).ready(function()
{
    var los_elementos = document.getElementsByTagName('input');

    for (var i=0; i<los_elementos.length; i++)
    {
        if (los_elementos[i].type == 'checkbox')
        {
            los_elementos[i].checked = false;
        }
        
        var status_del_elemento = los_elementos[i].name;
        var el_status_del_elemento  = status_del_elemento.replace(/-/g, "/");
    }

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

<?php
    require_once('conexion.php');
//    session_start();

//    if (!isset($_SESSION["username"]))
//    {
//        session_unset();
//        session_destroy();
//        header('Location: ../index3.php');
//    }

    $id_lugar = $_REQUEST['id_lugar'];
//    $id_lugar = "4";

    mysql_select_db($database_conexion, $conexion);
    $query_lugar = "SELECT * FROM lugares WHERE id_lugar = '$id_lugar'";
    $lugar = mysql_query($query_lugar, $conexion) or die(mysql_error());
    $row_lugar = mysql_fetch_assoc($lugar);

    $lugar = $row_lugar['lugar'];
    $el_lugar_imagen = $row_lugar['lugar_imagen'];

    if ($el_lugar_imagen == "")
    {
        $lugar_imagen = "images/exclamacion.png";
    }
    else
    {
        $lugar_imagen = "images/" . $el_lugar_imagen;
    }

    $id_ubicacion = $row_lugar['id_ubicacion'];

    mysql_select_db($database_conexion, $conexion);
    $query_controlador_lugar = "SELECT * FROM controladores WHERE id_lugar = '$id_lugar'";
    $controlador_lugar = mysql_query($query_controlador_lugar, $conexion) or die(mysql_error());
    $row_controlador_lugar = mysql_fetch_assoc($controlador_lugar);
    $totalRows_controlador_lugar = mysql_num_rows($controlador_lugar);

?>    
        
<div id="datos_ubicacion2" class='box box-widget widget-user'>
    <div class='widget-user-header bg-aqua-active'>
        <h3 class='widget-user-username'><?php echo $lugar; ?></h3>
    </div>
    <div class="widget-user-image">
        <img class="img-circle" src="<?php echo $lugar_imagen; ?>" alt="User Avatar">
    </div>
    <div class="box-footer">
        <div class="box-body no-padding">
            <ul class="users-list clearfix">
<?php
    do
    {
        $id_controlador = $row_controlador_lugar['id_controlador'];
        $nombre         = $row_controlador_lugar['nombre'];
        $descripcion    = $row_controlador_lugar['descripcion'];
        $ip             = $row_controlador_lugar['ip'];
        $mac            = $row_controlador_lugar['mac'];
        $visible        = $row_controlador_lugar['visible'];
        $id_tipo        = $row_controlador_lugar['id_tipo'];

        mysql_select_db($database_conexion, $conexion);
        $query_tipo = "SELECT tipo FROM tipo WHERE id_tipo = '$id_tipo'";
        $tipo = mysql_query($query_tipo, $conexion) or die(mysql_error());
        $row_tipo = mysql_fetch_assoc($tipo);

            $tipo = $row_tipo['tipo'];

        $id_tipo_sub = $row_controlador_lugar['id_tipo_sub'];

        mysql_select_db($database_conexion, $conexion);
        $query_tipo_sub = "SELECT tipo_sub FROM tipo_sub WHERE id_tipo_sub = '$id_tipo_sub'";
        $tipo_sub = mysql_query($query_tipo_sub, $conexion) or die(mysql_error());
        $row_tipo_sub = mysql_fetch_assoc($tipo_sub);

            $tipo_sub = $row_tipo_sub['tipo_sub'];

        $los_controladores = "<li>";        
        
        mysql_select_db($database_conexion, $conexion);
        $query_el_contro = "SELECT * FROM objetos WHERE id_controlador = '$id_controlador'";
        $el_contro = mysql_query($query_el_contro, $conexion) or die(mysql_error());
        $row_el_contro = mysql_fetch_assoc($el_contro);
        
        $los_controladores = "
        <table>
            <tr>
                <td></td>
                <td></td>
            </tr>";

        do
        {
            $id_objeto = $row_el_contro['id_objeto'];
            $nombre_objeto = $row_el_contro['nombre'];
            $nombre_corto_objeto = $row_el_contro['nombre_corto'];
            $mqtt_full_objeto = $row_el_contro['mqtt_full'];
     
            $los_controladores .= "<tr>";

            $los_controladores .= "<td>" . $nombre_corto_objeto . "</td>";
            $los_controladores .= "
            <td>
                <input name='" . $mqtt_full_objeto . "' checked='checked' class='yesno' type='checkbox' />
            </td>";
//            $los_controladores .= "<td>" . $mqtt_full_objeto . "</td>";
            
            $los_controladores .= "</tr>";    
        }
        while ($row_el_contro = mysql_fetch_assoc($el_contro));
        
        $los_controladores .= "</table></li>";
        
        print($los_controladores);
    }
    while ($row_controlador_lugar = mysql_fetch_assoc($controlador_lugar));
?>
            </ul>
        </div>
    </div>
</div>
<div>
    <label id="elvalordeidubicacion" hidden="true" size="25"><?php echo $id_ubicacion ?></label>
</div>
