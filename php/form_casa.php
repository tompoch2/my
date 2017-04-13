<?php
    require_once('conexion.php');

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

    mysql_select_db($database_conexion, $conexion);
    $query_casa = "SELECT * FROM casa ";
    $casa = mysql_query($query_casa, $conexion) or die(mysql_error());
    $row_casa = mysql_fetch_assoc($casa);
    $totalRows_casa = mysql_num_rows($casa);

    $nombre         = $row_casa['nombre'];
    $direccion      = $row_casa['direccion'];
    $nombre_mqtt    = $row_casa['nombre_mqtt'];
    $imagen_casa    = $row_casa['imagen_casa'];
?>

<div id="form_casa" class="col-md-6" hidden="true">
    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Datos de <?php echo $nombre; ?></h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form id="frm_casa" role="form">
            <div class="box-body">
                <div class="form-group">
                    <label>Nombre de la Casa</label>
                    <input type="text" class="form-control" id="casa_nombre" placeholder="Ingrese el Nombre de la Casa" value="<?php echo $nombre ; ?>">
                </div>
                <div class="form-group">
                    <label>Dirección de la Casa</label>
                    <input type="text" class="form-control" id="casa_direccion" placeholder="Ingrese la Dirección de la Casa" value="<?php echo $direccion; ?>">
                </div>
                <div class="form-group">
                    <label>Nombre descriptivo MQTT</label>
                    <input type="text" class="form-control" id="casa_mqtt" placeholder="Ingrese el Nombre Corto MQTT de la Casa" value="<?php echo $nombre_mqtt; ?>">
                </div>
                <div class="form-group">
                    <label >Imagen Actual (<?php echo $imagen_casa; ?>)</label>
                    <input type="file" id="casa_imagen">
                </div>
            </div>

            <div class="box-footer">
                <input id="submit" onclick="actualiza_casa()" type="button" value="Guardar" class="btn btn-success">
                <button id="btn_cerrar_form_casa" type="submit" class="btn btn-warning">Cerrar</button>
            </div>
        </form>
    </div>
</div>
