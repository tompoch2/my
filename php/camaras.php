<?php
	require_once('conexion.php');
	
    session_start();
    
    if (!isset($_SESSION["username"]))
    {
        //header('Location: index.php');
        session_unset();      
        session_destroy();   
        header('Location: ../index3.php');
    }    
    
    mysql_select_db($database_conexion, $conexion);
	$query_camaras_act = "SELECT * FROM camaras WHERE id_estado_general = '1' ORDER BY nombre ASC";
	$camaras_act = mysql_query($query_camaras_act, $conexion) or die(mysql_error());
	$row_camaras_act = mysql_fetch_assoc($camaras_act);
	$totalRows_camaras_act = mysql_num_rows($camaras_act);
    
    mysql_select_db($database_conexion, $conexion);
	$query_camaras_inact = "SELECT * FROM camaras WHERE id_estado_general = '0' ORDER BY nombre ASC";
	$camaras_inact = mysql_query($query_camaras_inact, $conexion) or die(mysql_error());
	$row_camaras_inact = mysql_fetch_assoc($camaras_inact);
	$totalRows_camaras_inact = mysql_num_rows($camaras_inact);
    
    mysql_select_db($database_conexion, $conexion);
	$query_camaras = "SELECT * FROM camaras ORDER BY nombre ASC";
	$camaras = mysql_query($query_camaras, $conexion) or die(mysql_error());
	$row_camaras = mysql_fetch_assoc($camaras);
	$totalRows_camaras = mysql_num_rows($camaras);
    
    mysql_select_db($database_conexion, $conexion);
	$query_camaras2 = "SELECT * FROM camaras ORDER BY nombre ASC";
	$camaras2 = mysql_query($query_camaras2, $conexion) or die(mysql_error());
	$row_camaras2 = mysql_fetch_assoc($camaras2);
	$totalRows_camaras2 = mysql_num_rows($camaras2);
	
    $salida = "<div class='nav-tabs-custom'><ul class='nav nav-tabs'>";
    $clase_activa = "";
    $el_tab = 0;
    do
    {      
        $camara_nombre              = $row_camaras['nombre'];
        $camara_id_estado_general   = $row_camaras['id_estado_general'];
        
        if ($camara_id_estado_general == 0){$camara_estado_imagen = "images/camara_150_opaco.png";}
        else if ($camara_id_estado_general == 1){$camara_estado_imagen = "images/camara_150.png";}
        if ($clase_activa == ""){$clase_activa = "class='active'";}
        else{$clase_activa = "";}
        
        $el_tab = $el_tab + 1; 
                                              
        $salida .= "<li " . $clase_activa . "><a href='#tab_" . $el_tab . "' data-toggle='tab'>" . $camara_nombre . "</a></li>";                                    
    }
    while ($row_camaras = mysql_fetch_assoc($camaras));
    
    $salida .= "</ul><div class='tab-content'>";
                
    $el_tab2 = 0;
    do
    {
        $camara_ip_url2              = $row_camaras2['ip_url'];
        $camara_puerto2              = $row_camaras2['puerto'];        
        $camara_usuario2             = $row_camaras2['usuario'];
        $camara_password2            = $row_camaras2['password'];
        $camara_nombre2              = $row_camaras2['nombre'];
        $camara_id_estado_general2   = $row_camaras2['id_estado_general'];
        
        $el_tab2 = $el_tab2 + 1;
        
        $camara_link2 = $camara_ip_url2 . ":" . $camara_puerto2 ."/videostream.cgi?user=" . $camara_usuario2 . "&pwd=" . $camara_password2;       
                                    
        $salida .= "    
        <div class='tab-pane active' id='tab_" . $el_tab2 . "'><div class='row'><div><a href='" . $camara_link2 . "' class='thumbnail'><img height='620' width='620' src='" . $camara_link2 . "' alt='...'></a></div></div></div>";                                    
    }
    while ($row_camaras2 = mysql_fetch_assoc($camaras2));

    $salida .= "</div></div>";
       
    echo ($salida);
?>