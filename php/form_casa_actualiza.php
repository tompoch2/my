<?php
    include_once("conexion.php");

    $casa_nombre        = $_POST['casa_nombre1'];
    $casa_direccion     = $_POST['casa_direccion1'];
    $casa_mqtt          = $_POST['casa_mqtt1'];

    mysql_select_db($database_conexion, $conexion);
    $query_CASA     = "SELECT * FROM casa";
    $CASA           = mysql_query($query_CASA, $conexion) or die (mysql_error());
    $row_CASA       = mysql_fetch_assoc($CASA);
    $total_CASA     = mysql_num_rows($CASA);

    if ($total_CASA == 0)
    {
        //inserta
    }
    else
    {
        //actualiza
        $updateSQL = sprintf("UPDATE casa SET nombre='%s', direccion='%s', nombre_mqtt='%s'",
                     addslashes($casa_nombre),
                     addslashes($casa_direccion),
                     addslashes($casa_mqtt));

        $resultado = mysql_query($updateSQL, $conexion) or die (mysql_error());
    }
    echo "Datos Actualizados\nDebe cerrar Sesión para aplicar totalmente los cambios";

?>
