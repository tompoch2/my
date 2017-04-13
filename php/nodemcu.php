<?php require_once('conexion.php');
    $mac = $_GET["mac"];
//    $mac = "pppppp111";
    $ip  = $_GET["la_ip"];
//    $ip  = "33.33.33.34";

    mysql_select_db($database_conexion, $conexion);

    $query_CASA = "SELECT nombre_mqtt, nombre FROM casa";
    $CASA = mysql_query($query_CASA, $conexion) or die(mysql_error());
    $row_CASA = mysql_fetch_assoc($CASA);

        $casa_mqtt = $row_CASA['nombre_mqtt'];
        $casa_nomb = $row_CASA['nombre'];

    $query_MAC = "SELECT id_lugar,id_ubicacion FROM controladores WHERE mac = '$mac'";
    $MAC = mysql_query($query_MAC, $conexion) or die(mysql_error());
    $row_MAC = mysql_fetch_assoc($MAC);
    $totalRows_MAC = mysql_num_rows($MAC);

    if ($totalRows_MAC)
    {
        $id_lugar = $row_MAC['id_lugar'];
        $id_ubicacion = $row_MAC['id_ubicacion'];

        $query_LUGAR = "SELECT lugar_mqtt FROM lugares WHERE id_lugar = '$id_lugar'";
        $LUGAR = mysql_query($query_LUGAR, $conexion) or die(mysql_error());
        $row_LUGAR = mysql_fetch_assoc($LUGAR);

        $lugar_mqtt = $row_LUGAR['lugar_mqtt'];

        $query_UBICACION = "SELECT ubicacion_mqtt FROM ubicacion WHERE id_ubicacion = '$id_ubicacion'";
        $UBICACION = mysql_query($query_UBICACION, $conexion) or die(mysql_error());
        $row_UBICACION = mysql_fetch_assoc($UBICACION);

        $ubicacion_mqtt = $row_UBICACION['ubicacion_mqtt'];

        //update la ip

        $actualiza_ip = "UPDATE controladores SET ip = '$ip' WHERE mac  = '$mac'";
        $resultado = mysql_query($actualiza_ip, $conexion) or die (mysql_error());

        $output = "{" . $casa_mqtt . "/" . $ubicacion_mqtt . "/" . $lugar_mqtt . "/}";

        if ($LUGAR){mysql_free_result($LUGAR);}
        if ($UBICACION){mysql_free_result($UBICACION);}
    }
    else
    {
        //inserta el nuevo controlador
        $inzerta = "INSERT INTO controladores SET nombre = '$mac', ip = '$ip', mac  = '$mac'";
        $resultado_i = mysql_query($inzerta, $conexion) or die (mysql_error());

        $output = "Controlador Ingresado Correctamente, ahora configurelo en << My " . $casa_nomb . " >> y luego reinicielo.";
    }

    print($output);

    if ($CASA){mysql_free_result($CASA);}
    if ($MAC){mysql_free_result($MAC);}
?>
