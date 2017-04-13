<?php require_once('conexion.php'); ?>

<?php
    $username = $_POST["username"];
    $password = $_POST["password"];

    session_start();
//    $username = "Admin";
//    $password = "CaRmReGf2";

    mysql_select_db($database_conexion, $conexion);
    $query_usuario = "SELECT * FROM usuario WHERE usuario = '$username' AND password = '$password'";
    $usuario = mysql_query($query_usuario, $conexion) or die(mysql_error());
    $row_usuario = mysql_fetch_assoc($usuario);
    $totalRows_usuario = mysql_num_rows($usuario);

    if ($totalRows_usuario)
    {
        $id_usuario = $row_usuario['id_usuario'];
        $nombres    = $row_usuario['nombres'];
        $apellidos  = $row_usuario['apellidos'];

        $nc = $nombres . " " . $apellidos;

        mysql_select_db($database_conexion, $conexion);
        $query_perfil = "SELECT * FROM usuarios_perfiles WHERE id_usuario = '$id_usuario'";
        $perfil = mysql_query($query_perfil, $conexion) or die(mysql_error());
        $row_perfil = mysql_fetch_assoc($perfil);
        $totalRows_perfil = mysql_num_rows($perfil);

        if ($totalRows_perfil > 1)
        {
            $mi_perfil = "MAS DE UNO";
        }
        else
        {
            $id_perfil = $row_perfil['id_perfil'];

            mysql_select_db($database_conexion, $conexion);
            $query_perfil2 = "SELECT perfil FROM perfil WHERE id_perfil = '$id_perfil'";
            $perfil2 = mysql_query($query_perfil2, $conexion) or die(mysql_error());
            $row_perfil2 = mysql_fetch_assoc($perfil2);

            $el_perfil = $row_perfil2['perfil'];
        }

        $_SESSION['username'] = $username;
        $_SESSION['nc'] = $nc;
        $_SESSION['el_perfil'] = $el_perfil;

        $ruta = "index_" . $el_perfil . ".php";

        header('Location:/my/' . $ruta);
    }
    else
    {
        header('Location:/my/index2.php');
    }

    if ($usuario){mysql_free_result($usuario);}
    if ($perfil){mysql_free_result($perfil);}
    if ($perfil2){mysql_free_result($perfil2);}

?>
