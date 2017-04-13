<?php
require_once('conexion.php');

header('Content-Type: text/html; charset=ISO-8859-1');

session_start();

if (!isset($_SESSION["username"]))
{
    header('Location: ../index3.php');
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900))
{
    session_unset();
    session_destroy();
    header('Location: index3.php');
}

$_SESSION['LAST_ACTIVITY'] = time();

$lasalida = "";

mysql_select_db($database_conexion, $conexion);
$query_controladores = "SELECT * FROM vista_controladores ORDER BY id_controlador ASC";
$controladores = mysql_query($query_controladores, $conexion) or die(mysql_error());
$row_controladores = mysql_fetch_assoc($controladores);
$totalRows_controladores = mysql_num_rows($controladores);

?>

<div id="list_dispo" class="col-md-8" hidden="true">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Dispositivos de <?php echo $_SESSION["la_casa"]; ?></h3>
            <div class="box-tools pull-right">
<!--
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
-->
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table no-margin">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Lugar</th>
                            <th>Ubicacion</th>
                            <th>Tipo</th>
                            <th>Sub Tipo</th>
                            <th>Visible</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    do
    {
        $id_controlador      = $row_controladores['id_controlador'];
        $nombre              = $row_controladores['nombre'];
        $lugar               = $row_controladores['lugar'];
        $ubicacion           = $row_controladores['ubicacion'];
        $tipo                = $row_controladores['tipo'];
        $tipo_sub            = $row_controladores['tipo_sub'];
        $visible             = $row_controladores['visible'];

        if ($visible == "1")
        {
            $sino = "<center><span class='label label-success'>SI</span></center>";
        }
        else if ($visible == "0")
        {
            $sino = "<center><span class='label label-warning'>NO</span></center>";
        }

        $lasalida .=
    "<tr>
        <td>" . $id_controlador . "</td>
        <td><a href='#' id='eldispositivo' data-id='" . $id_controlador . "' data-toggle='modal' >" . $nombre . "</a></td>
        <td>" . $lugar . "</td>
        <td>" . $ubicacion . "</td>
        <td>" . $tipo . "</td>
        <td>" . $tipo_sub . "</td>
        <td>" . $sino . "</td>
    </tr>";
/*                        <td><div class='sparkbar' data-color='#00a65a' data-height='20'>90,80,90,-70,61,-83,63</div></td> */
    }
    while ($row_controladores = mysql_fetch_assoc($controladores));

    print($lasalida);
?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-footer clearfix">
            <button id="btn_cerrar_list_dispo" type="submit" class="btn btn-warning pull-right">Cerrar</button>
        </div>
    </div>
</div>

