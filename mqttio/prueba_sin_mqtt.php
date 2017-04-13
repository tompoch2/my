<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <h1>MQTT en tiempo Real</h1>
        <script src="https://cdn.socket.io/socket.io-1.3.5.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="jquery/iphone-style-checkboxes.js"></script>
        <link rel="stylesheet" href="style.css">
        <script>
            $(document).ready(function () {
                $(':checkbox').iphoneStyle({
                    onChange: function (elem, val) {
                        if (val) {
                            socket.emit('publish', {
                                topic: "led",
                                payload: "on"
                            });
                        } else {
                            socket.emit('publish', {
                                topic: "led",
                                payload: "off"
                            });
                        }
                    }
                });
            });
        </script>



        <script>
//            var socket = io.connect('http://192.168.11.100:5000');
//            socket.on('connect', function () {
//                socket.on('mqtt', function (msg) {
//                    console.log(msg.topic + ' ' + msg.payload);
//                    if (msg.payload == "off") {
//                        if ($('input[name=led]').is(':checked')) {
//                            $('input[name=led]').prop('checked', false).change();
//                        }
//                    }
//                    if (msg.payload == "on") {
//                        if (!$('input[name=led]').is(':checked')) {
//                            $('input[name=led]').prop('checked', true).change();
//                        }
//                    }
//                });
//                socket.emit('subscribe', {
//                    topic: 'led'
//                });
//            });
        </script>
    </head>

    <body>
        <div class='table'>
            <table>
                <tr>
                    <td style='vertical-align: middle !important;'>
                        LED:
                    </td>
                    <td>
                        <input name="led" checked='checked' class='yesno' type='checkbox' />
                    </td>
                </tr>
            </table>
        </div>
    </body>

</html>
