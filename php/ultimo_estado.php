<?php require_once('conexion.php');
    $objeto_mqtt = $_GET["objeto_mqtt"];
    //$objeto_mqtt = "nina/primer_piso/multiuso/5ccf7f287c0e_1";

    mysql_select_db($database_conexion, $conexion);

    $query_OBJETO = "SELECT id_objeto FROM objetos WHERE mqtt_full = '$objeto_mqtt'";
    $OBJETO = mysql_query($query_OBJETO, $conexion) or die(mysql_error());
    $row_OBJETO = mysql_fetch_assoc($OBJETO);

        $id_objeto = $row_OBJETO['id_objeto'];

    $query_ESTADO = "SELECT id_estado_actual FROM eventos WHERE id_objeto = '$id_objeto'";
    $ESTADO = mysql_query($query_ESTADO, $conexion) or die(mysql_error());
    $row_ESTADO = mysql_fetch_assoc($ESTADO);
    $totalRows_ESTADO = mysql_num_rows($ESTADO);

    if ($totalRows_ESTADO)
    {
        $id_estado_actual = $row_ESTADO['id_estado_actual'];

        if ($id_estado_actual == 2)
        {
             $output = "{APAGADO}";
        }
        else if ($id_estado_actual == 1)
        {
            $output = "{APAGADO}";
        }
    }
    else
    {
        $output = ".";
    }

    print($output);

    if ($OBJETO){mysql_free_result($OBJETO);}
    if ($ESTADO){mysql_free_result($ESTADO);}
?>
