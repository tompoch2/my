<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Mosquitto test client</title>
        <meta name="description" content="An interactive getting started guide for Brackets.">
        <script type="text/javascript" src="../paho/js/mqttws31.js"></script>
        <script src="../paho/js/jquery-1.11.2.min.js"></script>
        <script src="../paho/js/iphone-style-checkboxes.js"></script>
        <link rel="stylesheet" href="../paho/css/style.css">
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
                    var status_del_elemento2 = los_elementos2[i].name;

                    var el_status_del_elemento2  = status_del_elemento2.replace(/-/g, "/");

                    var message = new Paho.MQTT.Message("el_status_node");
                    message.destinationName = el_status_del_elemento2;
                    client.send(message);
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
    </head>

    <body>
        <div class='table'>
            <table>
                <tr>
                    <td style='vertical-align: middle !important;'>
                        RELAY 1:
                    </td>
                    <td>
                        <input name="nina-primer_piso-living-5ccf7f8c125e_1" checked='checked' class='yesno' type='checkbox' />
                    </td>
                <tr>
                    <td style='vertical-align: middle !important;'>
                        RELAY 2:
                    </td>
                    <td>
                        <input name="nina-primer_piso-living-5ccf7f8c125e_2" checked='checked' class='yesno' type='checkbox' />
                    </td>
                </tr>
                </tr>
            </table>
        </div>
    </body>
</html>
