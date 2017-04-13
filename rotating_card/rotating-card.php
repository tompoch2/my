
<?php
require_once('../php/conexion.php');

//session_start();

//$id_controlador = $_REQUEST['id_controlador'];
    $id_controlador = "1";

mysql_select_db($database_conexion, $conexion);
$query_el_contro = "SELECT * FROM objetos WHERE id_controlador = '$id_controlador'";
$el_contro = mysql_query($query_el_contro, $conexion) or die(mysql_error());
$row_el_contro = mysql_fetch_assoc($el_contro);
$totalRows_el_contro = mysql_num_rows($el_contro);
////    echo $id_controlador;

//if (!isset($_SESSION["username"]))
//{
//    header('Location: index.php');
//}

//if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900))
//{
//    session_unset();
//    session_destroy();
//    header('Location: index3.php');
//}

//$el_user =  $_SESSION["username"];

//$query_el_userr  = "SELECT id_usuario FROM usuario WHERE usuario = '$el_user'";
//$el_userr = mysql_query($query_el_userr, $conexion) or die(mysql_error());
//$row_el_userr = mysql_fetch_assoc($el_userr);

//$id_usuario = $row_el_userr['id_usuario'];

//$query_el_admin  = "SELECT * FROM usuarios_perfiles WHERE id_usuario = '$id_usuario' AND id_perfil ='1'";
//$el_admin = mysql_query($query_el_admin, $conexion) or die(mysql_error());
//$row_el_admin = mysql_fetch_assoc($el_admin);
//$totalRows_el_admin = mysql_num_rows($el_admin);

?>

<!DOCTYPE html>
<html>
<head>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">

    <script type="text/javascript" src="js/mqttws31.js"></script>
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/iphone-style-checkboxes.js"></script>
    <link rel="stylesheet" href="css/style.css">

    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/iphone-style-checkboxes.js"></script>
    <link rel="stylesheet" href="css/style_switch.css">

    <link href='css/bootstrap.css' rel='stylesheet' />
    <link href='css/rotating-card.css' rel='stylesheet' />
<!--    <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>-->


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
                })
        });
    </script>

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-10">
<!--             <div class="col-sm-1"></div> -->
                <div class="col-md-4 col-sm-6">
                    <div class="card-container manual-flip">
                        <div class="card">
                            <div class="front">
                                <div class="cover">
                                    <img src="images/saladeestar2.jpg"/>
                                </div>
                                <div class="user">
                                    <img class="img-circle" src="images/saladeestar.jpg"/>
                                </div>
                                <div class="content">
                                    <div class="main">
                                        <h3 class="name">Living</h3>
                                        <p class="profession"></p>
                                        <p class="text-center">"Dispositivos activados<br> en Living"</p>
                                    </div>
                                    <div class="footer">
                                        <button class="btn btn-simple" onclick="rotateCard(this)">
                                            <i class="fa fa-plus-circle"></i>  Ver mas
                                        </button>
                                    </div>
                                </div>
                            </div> <!-- end front panel -->
                            <div class="back">
<!--                                <img src="images/rotating_card_thumb3.png"  class="well-searchbox">-->
                                <div class="header">
                                    <h5 class="motto">"DISPOSITIVOS"</h5>
                                </div>
                                <div class="content">
                                    <div class="main">
<!--                                <label>Living <input type="checkbox" class="ios-switch" /></label>
                                <label>Cocina<input type="checkbox" class="ios-switch" checked /></label>
                                <label>Patio<input type="checkbox" class="ios-switch" checked /></label>
-->
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
                            ";

//                                                if ($totalRows_el_admin == "1")
//                                                {
//                                                    $objj .= "Admin SI";
//                                                }
//                                                else
//                                                {
//                                                    $objj .= "Admin NO";
//                                                }


                                                $objj .= "
                        </td>
                    </tr>
                </table>
            </div>";
                                            }
                                            while ($row_el_contro = mysql_fetch_assoc($el_contro));
                                        }
                                        print($objj);
                                        ?>

                                        <div class="stats-container">
                                            <div class="stats">
                                                <h4>235</h4>
                                                <p>
                                                    Seguidores
                                                </p>
                                            </div>
                                            <div class="stats">
                                                <h4>114</h4>
                                                <p>
                                                    Siguendo
                                                </p>
                                            </div>
                                            <div class="stats">
                                                <h4>35</h4>
                                                <p>
                                                    Projects
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <button class="btn btn-simple" onclick="rotateCard(this)">
                                        <i class="fa fa-arrow-circle-o-left "></i> Atras
                                    </button>
                                </div>
                            </div>
                        </div> <!-- end back panel -->
                    </div> <!-- end card -->
                </div> <!-- end card-container --> <!-- end col sm 3 -->
<!--         <div class="col-sm-1"></div> -->
                <div class="col-md-4 col-sm-6">
                    <div class="card-container">
                        <div class="card">
                            <div class="front">
                                <div class="cover">
                                    <img src="images/rotating_card_thumb3.png"/>
                                </div>
                                <div class="user">
                                    <img class="img-circle" src="images/rotating_card_profile.png"/>
                                </div>
                                <div class="content">
                                    <div class="main">
                                        <h3 class="name">Inna Corman</h3>
                                        <p class="profession">Product Manager</p>

                                        <p class="text-center">"I'm the new Sinatra, and since I made it here I can make it anywhere, yeah, they love me everywhere"</p>
                                    </div>
                                    <div class="footer">
                                        <div class="rating">
                                            <i class="fa fa-mail-forward"></i> Auto Rotation
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end front panel -->
                            <div class="back">
                                <div class="header">
                                    <h5 class="motto">"To be or not to be, this is my awesome motto!"</h5>
                                </div>
                                <div class="content">
                                    <div class="main">
                                        <h4 class="text-center">Job Description</h4>
                                        <p class="text-center">Web design, Adobe Photoshop, HTML5, CSS3, Corel and many others...</p>

                                        <div class="stats-container">
                                            <div class="stats">
                                                <h4>235</h4>
                                                <p>
                                                    Followers
                                                </p>
                                            </div>
                                            <div class="stats">
                                                <h4>114</h4>
                                                <p>
                                                    Following
                                                </p>
                                            </div>
                                            <div class="stats">
                                                <h4>35</h4>
                                                <p>
                                                    Projects
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <div class="social-links text-center">
                                        <a href="http://creative-tim.com" class="facebook"><i class="fa fa-facebook fa-fw"></i></a>
                                        <a href="http://creative-tim.com" class="google"><i class="fa fa-google-plus fa-fw">   </i></a>
                                        <a href="http://creative-tim.com" class="twitter"><i class="fa fa-twitter fa-fw"></i></a>
                                    </div>
                                </div>
                            </div> <!-- end back panel -->
                        </div> <!-- end card -->
                    </div> <!-- end card-container -->
                </div> <!-- end col-sm-3 -->
            </div> <!-- end col-sm-10 -->
        </div> <!-- end row -->
        <div class="space-200"></div>
    </div>
<!--
    <script src="js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
-->
    <script type="text/javascript">
        function rotateCard(btn)
        {
            var $card = $(btn).closest('.card-container');
            console.log($card);
            if($card.hasClass('hover'))
            {
                $card.removeClass('hover');
            }
            else
            {
                $card.addClass('hover');
            }
        }
    </script>

    </body>
</html>
