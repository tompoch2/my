<?php
    require_once('conexion.php');
    session_start();

    header('Content-Type: text/html; charset=ISO-8859-1');
    if (!isset($_SESSION["username"]))
    {
        session_unset();
        session_destroy();
        header('Location: ../index3.php');
    }

    $id_lugar = $_REQUEST['id_lugar'];
//    $id_lugar = "4";

    mysql_select_db($database_conexion, $conexion);
    $query_lugar = "SELECT * FROM lugares WHERE id_lugar = '$id_lugar'";
    $lugar = mysql_query($query_lugar, $conexion) or die(mysql_error());
    $row_lugar = mysql_fetch_assoc($lugar);

    $lugar = $row_lugar['lugar'];
    $el_lugar_imagen = $row_lugar['lugar_imagen'];

    if ($el_lugar_imagen == "")
    {
        $lugar_imagen = "images/exclamacion.png";
    }
    else
    {
        $lugar_imagen = "images/" . $el_lugar_imagen;
    }

    $id_ubicacion = $row_lugar['id_ubicacion'];

    mysql_select_db($database_conexion, $conexion);
    $query_controlador_lugar = "SELECT * FROM controladores WHERE id_lugar = '$id_lugar'";
    $controlador_lugar = mysql_query($query_controlador_lugar, $conexion) or die(mysql_error());
    $row_controlador_lugar = mysql_fetch_assoc($controlador_lugar);
    $totalRows_controlador_lugar = mysql_num_rows($controlador_lugar);

?>

<div id="datos_ubicacion2" class='box box-widget widget-user'>
    <div class='widget-user-header bg-aqua-active'>
        <h3 class='widget-user-username'><?php echo $lugar; ?></h3>
    </div>
    <div class="widget-user-image">
        <img class="img-circle" src="<?php echo $lugar_imagen; ?>" alt="User Avatar">
    </div>
    <div class="box-footer">

<?php
    do
    {
        $id_controlador = $row_controlador_lugar['id_controlador'];
        $nombre         = $row_controlador_lugar['nombre'];
        $descripcion    = $row_controlador_lugar['descripcion'];
        $ip             = $row_controlador_lugar['ip'];
        $mac            = $row_controlador_lugar['mac'];
        $visible        = $row_controlador_lugar['visible'];
        $id_tipo        = $row_controlador_lugar['id_tipo'];

        mysql_select_db($database_conexion, $conexion);
        $query_tipo = "SELECT tipo FROM tipo WHERE id_tipo = '$id_tipo'";
        $tipo = mysql_query($query_tipo, $conexion) or die(mysql_error());
        $row_tipo = mysql_fetch_assoc($tipo);

            $tipo = $row_tipo['tipo'];

        $id_tipo_sub = $row_controlador_lugar['id_tipo_sub'];

        mysql_select_db($database_conexion, $conexion);
        $query_tipo_sub = "SELECT tipo_sub FROM tipo_sub WHERE id_tipo_sub = '$id_tipo_sub'";
        $tipo_sub = mysql_query($query_tipo_sub, $conexion) or die(mysql_error());
        $row_tipo_sub = mysql_fetch_assoc($tipo_sub);

            $tipo_sub = $row_tipo_sub['tipo_sub'];


        $los_controladores = "
                <div class='box box-success'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'>" . $nombre ."</h3>
                    </div>
                    <div>
                        <iframe sandbox='allow-same-origin allow-scripts allow-popups allow-forms' frameBorder='0' width='100%' src='php/objetos.php?id_controlador=" . $id_controlador . "' onload='resizeIframe(this);'></iframe>
                    </div>
                </div>";

        print($los_controladores);
    }
    while ($row_controlador_lugar = mysql_fetch_assoc($controlador_lugar));
?>
    </div>
</div>
<div>
    <label id="elvalordeidubicacion" hidden="true" size="25"><?php echo $id_ubicacion ?></label>
</div>
