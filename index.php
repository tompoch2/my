<?php
    session_start();

    require_once('php/conexion.php');

    header('Content-Type: text/html; charset=ISO-8859-1');

    mysql_select_db($database_conexion, $conexion);
    $query_casa = "SELECT * FROM casa";
    $casa = mysql_query($query_casa, $conexion) or die(mysql_error());
    $row_casa = mysql_fetch_assoc($casa);

    $la_casa = $row_casa['nombre'];

    $_SESSION["la_casa"] = $la_casa;

    if ($casa)
    {
        mysql_free_result($casa);
    }

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My<?php echo ($la_casa);?> | Inicio de Sesion</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="err" id="add_err"></div>
    <div class="login-box">
      <div class="login-logo">
        <a href="php/login.php"><b><img src="images/home.png" class="user-image" alt="User Image"></b><?php echo($la_casa)?></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Iniciar Sesi&oacute;n</p>
        <form action="php/login.php"  method="post">
          <div class="form-group has-feedback">
            <input name="username" id="username" type="text" class="form-control" placeholder="Usuario">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
              <input name="password" id="password" type="password" class="form-control" placeholder="Contrase&ntilde;a">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8"></div><!-- /.col -->
            <div class="col-xs-4">
              <button name="ingresar" id="ingresar" type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
            </div><!-- /.col -->
          </div>
        </form>
        <!-- /.social-auth-links -->Fontech 2017<br>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
  </body>
</html>
