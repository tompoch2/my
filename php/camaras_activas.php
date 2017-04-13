<?php
    require_once('conexion.php');
        
    mysql_select_db($database_conexion, $conexion);
	$query_camaras_act = "SELECT * FROM camaras WHERE id_estado_general = '1' ORDER BY nombre ASC";
	$camaras_act = mysql_query($query_camaras_act, $conexion) or die(mysql_error());
	$row_camaras_act = mysql_fetch_assoc($camaras_act);
	$totalRows_camaras_act = mysql_num_rows($camaras_act);
    
    echo ($totalRows_camaras_act);
?>