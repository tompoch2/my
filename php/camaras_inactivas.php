<?php
    require_once('conexion.php');
        
    mysql_select_db($database_conexion, $conexion);
	$query_camaras_inact = "SELECT * FROM camaras WHERE id_estado_general = '0' ORDER BY nombre ASC";
	$camaras_inact = mysql_query($query_camaras_inact, $conexion) or die(mysql_error());
	$row_camaras_inact = mysql_fetch_assoc($camaras_inact);
	$totalRows_camaras_inact = mysql_num_rows($camaras_inact);
    
    echo ($totalRows_camaras_inact);
?>