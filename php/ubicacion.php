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

//    $ubicacion = $_POST['ubicacion'];
    $ubicacion = $_REQUEST['ubicacion'];
//    $ubicacion = "SALA";

    $salida =  $ubicacion;

    echo ($salida);
?>
