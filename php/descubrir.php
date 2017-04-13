<?php
    require_once('conexion.php');
    session_start();
    
    if (!isset($_SESSION["username"]))
    {
        session_unset();      
        session_destroy();   
        header('Location: ../index3.php');
    }    
    
    error_reporting(0);
    set_time_limit(0);    
    
//    $host = $_SERVER['argv'][1];
//    $sport = $_SERVER['argv'][2];
//    $eport = $_SERVER['argv'][3];
    
    $sport = "80";
    $eport = "80";
    $delay = 1;
    
    function usage()
    {
        print "ReneXXX Port Scanner\n";
        print "Uso: php {$_SERVER['argv'][0]} {hostname} {start} {end}\n";
        print "\n" . "Example : php {$_SERVER['argv'][0]}  google.com 80 443\n";
        die();
    }

    $localIP = $_SERVER['SERVER_ADDR'];    
    $porciones = explode(".", $localIP);
    $rango = $porciones[0]. "." . $porciones[1] . "." . $porciones[2] . ".";
    
    echo "<strong>Rango IP de busqueda </strong><br>" . $rango . " 1-255 <br><br>";
    
    $salida = "<table id='lista_red_local' class='table table-bordered table-hover'><thead><tr><th>IP</th><th>Protocolo</th><th>Puerto</th><th>MAC</th><th>Acciones</th></tr></thead><tbody>";
    
    for ($ii = 1; $ii<255;$ii++)
    {
        $host = $rango . "" . $ii;
//        $host = "192.168.0.104";            
        for($i = $sport; $i <= $eport; $i++)
        {
            $fp = fsockopen($host, $i, $errno, $errstr, $delay);
            if(getservbyport($i, 'tcp') == '') $protocol = 'Unknown'; 
            else $protocol = getservbyport($i, 'tcp');
        
            if ($fp)
            {
                $estado = "Activo";
//                print "Puerto <strong>$i</strong> [$protocol] en la IP <strong>$host</strong> esta Activo ";
                $ipAddress=$host;
                $macAddr=false;

//                $arp=`arp -n $ipAddress`; // LINUX
                $arp=`arp -a $ipAddress`; // WINDOWS
                
                if ($arp == "No se encontraron entradas ARP.")
                {
                    
                } 
                else
                {
                    $lines=explode("\n", $arp);

                    foreach($lines as $line)
                    {
                        $cols=preg_split('/\s+/', trim($line));
                        if ($cols[0]==$ipAddress)
                        {
                            if ($ipAddress == $localIP)
                            {
                                $macAddr = "Servidor";
                            }
                            else
                            {
                                $macAddr=$cols[1];
                            }                           
                        }
                    }
                    
                    mysql_select_db($database_conexion, $conexion);
                    $query_controlador_existe = "SELECT * FROM controladores WHERE mac = '$macAddr'";
                    $controlador_existe = mysql_query($query_controlador_existe, $conexion) or die(mysql_error());
                    $row_controlador_existe = mysql_fetch_assoc($controlador_existe);
                    $totalRows_controlador_existe = mysql_num_rows($controlador_existe);
                
                    if ($totalRows_controlador_existe == 1)
                    {
                        $comandos = "
                        <div class='col-md-3 col-sm-4'>
                            <i class='fa fa-fw fa-edit'></i>                            
                            <a data-dismiss='modal' data-toggle='modal' href='#editar_descubrir'>Click2</a>
                        </div>
                        <div class='col-md-3 col-sm-4'>
                            <i class='fa fa-fw fa-minus-circle'></i>
                        </div>";
//                      $comandos = "<a onclick=\$('#la_red').modal('hide');$('#editar_descubrir').modal('show');\' href='#'>Click</a>";
//                      <a onclick=\$('#la_red').modal('hide');$('#editar_descubrir').modal('show');\' href='#'>Click</a>
                    }
                    else
                    {
                        $comandos = "<div class='col-md-3 col-sm-4'><i class='fa fa-fw fa-plus-circle'></i></div>";
                        
                    }
                    
                    $salida .= "<tr>
                        <td>" . $host . "</td>
                        <td>" . $protocol . "</td>
                        <td>" . $i . "</td>
                        <td>" . $macAddr . "</td>
                        <td>" . $comandos . "</td>
                      </tr>";    
                }
                                              
                fclose($fp);
            } 
            else 
            {

            }
            flush();
        }                      
    }
    
    $salida .=  "</tbody><tfoot><tr><th>IP</th><th>Protocolo</th><th>Puerto</th><th>MAC</th><th>Acciones</th></tr></tfoot></table>";   
    
    echo ($salida);       
?>
