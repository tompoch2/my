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

        $id_ubicacion = $_REQUEST['id_ubicacion'];
//        $id_ubicacion = "1";

        mysql_select_db($database_conexion, $conexion);
        $query_ubicacion = "SELECT ubicacion, ubicacion_imagen FROM ubicacion WHERE id_ubicacion = '$id_ubicacion'";
        $ubicacion = mysql_query($query_ubicacion, $conexion) or die(mysql_error());
        $row_ubicacion = mysql_fetch_assoc($ubicacion);
        $totalRows_ubicacion = mysql_num_rows($ubicacion);

        $ubicacion                  = $row_ubicacion['ubicacion'];
        $la_ubicacion_imagen           = $row_ubicacion['ubicacion_imagen'];

        if ($la_ubicacion_imagen == "")
        {
            $ubicacion_imagen = "images/exclamacion.png";
        }
        else
        {
            $ubicacion_imagen = "images/" . $la_ubicacion_imagen;
        }

        mysql_select_db($database_conexion, $conexion);
        $query_lugar = "SELECT * FROM lugares WHERE id_ubicacion = '$id_ubicacion' ORDER BY lugar ASC";
        $lugar = mysql_query($query_lugar, $conexion) or die(mysql_error());
        $row_lugar = mysql_fetch_assoc($lugar);
        $totalRows_lugar = mysql_num_rows($lugar);

?>
    <div id="datos_ubicacion" class='box box-widget widget-user'>
        <div class='widget-user-header bg-aqua-active'>
            <h3 class='widget-user-username'><?php echo $ubicacion; ?></h3>
        </div>
        <div class="widget-user-image">
            <img class="img-circle" src="<?php echo $ubicacion_imagen; ?>" alt="User Avatar">
        </div>
        <div class="box-footer">
            <div class="box-body no-padding">
                <ul class="users-list clearfix">
                    <?php
                    do
                    {
                        $id_lugar           = $row_lugar['id_lugar'];
                        $el_lugar           = $row_lugar['lugar'];
                        $el_lugar_imagen    = $row_lugar['lugar_imagen'];

                        if ($el_lugar_imagen == "")
                        {
                            $lugar_imagen = "images/exclamacion.png";
                        }
                        else
                        {
                            $lugar_imagen = "images/" . $el_lugar_imagen;
                        }

                        $los_lugares = "<li>
                                            <a id='ellugar' data-id='" . $id_lugar . "' class='users-list-name' href='#'>
                                                <img class='img-circle' src='" . $lugar_imagen . "' alt='" . $el_lugar . "'>
                                            </a>
                                            <span>" . $el_lugar . "</span>
                                        </li>";

                        print($los_lugares);

                    }
                    while ($row_lugar = mysql_fetch_assoc($lugar));
                    ?>

                </ul>
            </div>
        </div>
    </div>
