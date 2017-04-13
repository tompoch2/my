<?php
    require_once('php/conexion.php');

    session_start();

    if (!isset($_SESSION["username"]))
    {
        header('Location: index.php');
    }

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900))
    {
        session_unset();
        session_destroy();
        header('Location: index3.php');
    }
    $_SESSION['LAST_ACTIVITY'] = time();

  mysql_select_db($database_conexion, $conexion);
    $query_camaras_act = "SELECT * FROM camaras WHERE id_estado_general = '1' ORDER BY nombre ASC";
    $camaras_act = mysql_query($query_camaras_act, $conexion) or die(mysql_error());
    $row_camaras_act = mysql_fetch_assoc($camaras_act);
    $totalRows_camaras_act = mysql_num_rows($camaras_act);

  mysql_select_db($database_conexion, $conexion);
    $query_camaras_inact = "SELECT * FROM camaras WHERE id_estado_general = '0' ORDER BY nombre ASC";
    $camaras_inact = mysql_query($query_camaras_inact, $conexion) or die(mysql_error());
    $row_camaras_inact = mysql_fetch_assoc($camaras_inact);
    $totalRows_camaras_inact = mysql_num_rows($camaras_inact);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My<?php echo ($_SESSION["la_casa"]);?> | Panel de Control</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href='css/rotating-card.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/sweetalert.css">

    <script type="text/javascript" src="paho/js/mqttws31.js"></script>
    <script src="paho/js/jquery-1.11.2.min.js"></script>
    <script src="paho/js/iphone-style-checkboxes.js"></script>
    <link rel="stylesheet" href="paho/css/style.css">

    <link rel="stylesheet" href="css/jquery.dataTables.min.css"/>

    <link rel="stylesheet" type="text/css" href="css/jquery.jdigiclock.css" />
    
    <link href="css/bootstrap-wizard.css" rel="stylesheet" />
    <link href="chosen/chosen.css" rel="stylesheet" />

    <style type="text/css">
      .wizard-modal p 
      {
        margin: 0 0 10px;
        padding: 0;
      }

      #wizard-ns-detail-servers, .wizard-additional-servers 
      {
        font-size: 12px;
        margin-top: 10px;
        margin-left: 15px;
      }
      #wizard-ns-detail-servers > li, .wizard-additional-servers li 
      {
        line-height: 20px;
        list-style-type: none;
      }
      #wizard-ns-detail-servers > li > img 
      {
        padding-right: 5px;
      }
      
      .wizard-modal .chzn-container .chzn-results 
      {
        max-height: 150px;
      }
      .wizard-addl-subsection 
      {
        margin-bottom: 40px;
      }
      .create-server-agent-key 
      {
        margin-left: 15px; 
        width: 90%;
      }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


  </head>
  <body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">

        <header class="main-header">

        <!-- Logo -->
        <a href="index_Administrador.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b><img src="images/home_60.png" class="user-image" alt="User Image"></b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><img src="images/home_60.png" class="user-image" alt="User Image"></b><?php echo $_SESSION["la_casa"]; ?></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">          
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-cloud"></i>
                  <!--<span class="label label-success">4</span>-->
                </a>                
                <ul class="dropdown-menu">
                  <li class="header">Información Climatológica</li>
                  <li>                   
                    <ul class="menu">                      
                      <div>
                       <center>
                          <div id="digiclock"></div>
                        </center>
                      </div>                                                                
                    </ul>
                  </li>                  
                </ul>
              </li>
<!--
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">4</span>
                </a>                
                <ul class="dropdown-menu">
                  <li class="header">You have 6 messages</li>
                  <li>
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            AdminLTE Design Team
                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Developers
                            <small><i class="fa fa-clock-o"></i> Today</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Sales Department
                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Reviewers
                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>
-->
<!--              
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">10</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 10 notifications</li>
                  <li>
                    
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-red"></i> 5 new members joined
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-user text-red"></i> You changed your username
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">View all</a></li>
                </ul>
              </li>
-->     
<!--         
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                  <span class="label label-danger">9</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 9 tasks</li>
                  <li>
                    
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <h3>
                            Design some buttons
                            <small class="pull-right">20%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">20% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <h3>
                            Create a nice theme
                            <small class="pull-right">40%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">40% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <h3>
                            Some task I need to do
                            <small class="pull-right">60%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">60% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <h3>
                            Make beautiful transitions
                            <small class="pull-right">80%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">80% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all tasks</a>
                  </li>
                </ul>
              </li>
-->

              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="images/yo.png" class="user-image" alt="User Image">
                    <span class="hidden-xs"><small><?php echo($_SESSION['nc']);?></small></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="images/yo.png" class="img-circle" alt="User Image">
                    <p><?php echo ($_SESSION['nc'] . "-" . $_SESSION['el_perfil']);?>
                      <small></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="row">
                      <div class="col-xs-2  text-center">
                        <a href="#">Amigos</a>
                      </div>
                    </div><!-- /.row -->
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Perf&iacute;l</a>
                    </div>
                    <div class="pull-right">
                      <a href="logout.php" class="btn btn-default btn-flat">Cerrar Sesion</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>

        <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="images/yo.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><small><?php echo ($_SESSION['nc']);?></small></p>
              <a href="#"><i class="fa fa-circle text-success"></i> En Linea</a>
            </div>
          </div>
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MENU</li>
              <li>
                  <a id="link_pc" href="">
                      <i class="fa fa-dashboard"></i> <span>Panel de Control</span>
                  </a>
              </li>
            <li>
                <!--<a href="" onclick="actualiza_camaras()" data-toggle="modal" data-target="#Modal_Descubrir">-->
              <a href=""  data-toggle="modal" data-target="#Modal_Descubrir">
                <i class="fa fa-eye"></i> <span>Descubrir</span>
              </a>
            </li>
            <li>
              <a href="" onclick="actualiza_camaras()" data-toggle="modal" data-target="#Modal_Camaras">
                <i class="fa fa-video-camera"></i> <span>Camaras</span>
                    <span>
                        <small class="label pull-right bg-red"><?php echo ($totalRows_camaras_inact);?></small>
                        <small class="label pull-right bg-green"><?php echo ($totalRows_camaras_act);?></small>
                    </span>
              </a>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-map-marker"></i>
                <span>Ubicaciones</span>
<?php
  mysql_select_db($database_conexion, $conexion);
    $query_ubicacion = "SELECT * FROM ubicacion ORDER BY ubicacion ASC";
    $ubicacion = mysql_query($query_ubicacion, $conexion) or die(mysql_error());
    $row_ubicacion = mysql_fetch_assoc($ubicacion);
    $totalRows_ubicacion = mysql_num_rows($ubicacion);
?>
                <span class="label label-primary pull-right"><?php echo ($totalRows_ubicacion); ?></span>
            </a>
            <ul class="treeview-menu">
<?php
    do
    {
        $id_ubicacion           = $row_ubicacion['id_ubicacion'];
        $la_ubicacion           = $row_ubicacion['ubicacion'];
?>
        <li>
            <a href="#" id="la_ubicacion_seleccionada" data-target="#modal_lugares" data-id="<?php echo ($id_ubicacion); ?>" data-toggle="modal" ><i class="fa fa-circle-o"></i><?php echo($la_ubicacion); ?></a>

        </li>
<?php
    }
    while ($row_ubicacion = mysql_fetch_assoc($ubicacion));
?>
              </ul>
            </li>
              <li>
                  <a id="link_configuracion" href="">
                      <i class="fa fa-cogs"></i> <span>Configuración</span>
                  </a>
              </li>

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

        <div class="modal fade" id="Modal_Camaras" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Camaras conectadas a <?php echo ($_SESSION["la_casa"]);?></h4>
                </div>
                <div class="modal-body">

                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title"></h3>
                                <div class="box-tools pull-right">
                                    <span class="label label-success"><?php echo ($totalRows_camaras_act);?> C&aacute;maras Activas</span>
                                    <span class="label label-danger"><?php echo ($totalRows_camaras_inact);?> C&aacute;maras Inactivas</span>
                                </div>
                            </div><!-- /.box-header -->

                            <div id="las_camaras"></div>

<!--//aqui//-->
                        </div><!--/.box -->
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="actualiza_camaras()" class="btn btn-default"><i class="fa fa-refresh"></i> Actualizar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="Modal_Descubrir" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Descubrir Red Local en <?php echo ($_SESSION["la_casa"]);?></h4>
                </div>
                <div class="modal-body">
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title"></h3>
                                <div class="box-tools pull-right">
<!--                                    <span class="label label-success"><?php //echo ($totalRows_camaras_act);?> C&aacute;maras Activas</span>
                                    <span class="label label-danger"><?php //echo ($totalRows_camaras_inact);?> C&aacute;maras Inactivas</span>
-->
                                </div>
                            </div><!-- /.box-header -->

                            <div id="la_red">
                                <img src="images/ajax-loader2.gif" id="loading-indicator" style="display:none" />
                            </div>
                        </div><!--/.box -->
                </div>
                <div class="modal-footer">
                  <button type="button" onclick="descubriendo_red()" class="btn btn-default"><i class="fa fa-refresh"></i> Descubrir</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

        <div class="modal fade" id="editar_descubrir" style="display: none;" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edici&oacute;n de Dispositivos en <?php echo ($_SESSION["la_casa"]);?></h4>
                </div>
                <div class="modal-body">
                    <div class="box box-alert-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-tools pull-right">
                                editarrrrr
                            </div>
                        </div>
                        editar
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" data-toggle='modal' href='#Modal_Descubrir'>Cerrar</button>
<!--                    <a data-dismiss='modal' data-toggle='modal' href='#editar_descubrir'>Click2</a>-->
                </div>
            </div>
        </div>
    </div>

        <div id="modal_lugares" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header" hidden="true">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">
                            <i class="fa fa-map-marker"></i> Lugares
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div id="modal-loader" style="display: none; text-align: center;">
                            <img src="images/ajax-loader.gif">
                        </div>
                        <div id="dynamic-content"></div>
                    </div>
                    <div class="modal-footer">
                        <button id="btn_atras" type="button" onclick="atraselvalordeidubicacion(document.getElementById('elvalordeidubicacion').value);" class="btn btn-default">Atras</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>



        <div id="modal_dispositivos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" >
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">
                            <i class="fa fa-map-marker"></i> Dispositivo
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div id="modal-loader" style="display: none; text-align: center;">
                            <img src="images/ajax-loader.gif">
                        </div>
                        <div id="dynamic-dispositivo"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>




        <div id="configg" class="content-wrapper">
        <section class="content-header">
            <h1>
                Configuracion
                <small>Panel de Configuraciones</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li class="active">Configuracion</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary expanded-box">
                                <div class="box-header with-border">
                                    <i class="fa fa-cogs"></i>
                                    <h3 class="box-title">Configuracion</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>

                                <div id="config_menu" class="box-body no-padding">
                                    <ul class="users-list clearfix">
                                        <li>
                                            <a id="menu_config_general" class="users-list-name"  href="#">
                                                <img class="img-circle" src="images/home.png" alt="">
                                            </a>
                                            <span>General</span>
                                        </li>
                                        <li>
                                            <a id="menu_config_dispositivos" class="users-list-name"  href="#">
                                                <img class="img-circle" src="images/nodemcu128.jpg" alt="">
                                            </a>
                                            <span>Dispositivos</span>
                                        </li>
                                        <li>
                                            <a id="menu_ubicaciones_lugares" class="users-list-name"  href="#">
                                                <img class="img-circle" src="images/ubicacion.png" alt="">
                                            </a>
                                            <span>Ubicaciones/Lugares</span>
                                        </li>
                                        <li>
                                            <a class="users-list-name"  href="#">
                                                <img class="img-circle" src="images/user.png" alt="">
                                            </a>
                                            <span>Usuarios</span>
                                        </li>
                                        <li>
                                            <a id="menu_sistema" class="users-list-name"  href="#">
                                                <img class="img-circle" src="images/system.png" alt="">
                                            </a>
                                            <span>Sistema</span>
                                        </li>
                                    </ul>
                                </div>

                                <div id="config_general" class="box-body no-padding" hidden="true">
                                    <div class="col-md-4">
                                        <!-- Widget: user widget style 1 -->
                                        <div class="box box-widget widget-user-2">
                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                            <div class="widget-user-header bg-yellow">
                                                <div class="widget-user-image">
                                                    <img class="img-circle" src="images/home.png" alt="User Avatar">
                                                </div><!-- /.widget-user-image -->
                                                <h3 class="widget-user-username"><?php echo $_SESSION["la_casa"]; ?></h3>
                                                <h5 class="widget-user-desc">Nombre descriptivo de la Casa</h5>
                                            </div>
                                            <div class="box-footer no-padding">
                                                <ul class="nav nav-stacked">
                                                    <li>
                                                        <a href="#">Usuarios activos <span class="pull-right badge bg-red">2</span></a>
                                                    </li>
                                                    <li>
                                                        <a id="link_form_casa" href="#">Cambiar Información</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!-- /.widget-user -->
                                        <div class="box-footer text-center">
                                            <a id="atras_config" href="" class="uppercase">Atras</a>
                                        </div>
                                    </div>
                                    <div id="formulario-casa"></div>
                                </div>

                                <div id="config_dispositivos" class="box-body no-padding" hidden="true">
                                    <div class="col-md-4">
                                        <!-- Widget: user widget style 1 -->
                                        <div class="box box-widget widget-user-2">
                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                            <div class="widget-user-header bg-yellow">
                                                <div class="widget-user-image">
                                                    <img class="img-circle" src="images/home.png" alt="User Avatar">
                                                </div><!-- /.widget-user-image -->
                                                <h3 class="widget-user-username"><?php echo $_SESSION["la_casa"]; ?></h3>
                                                <h5 class="widget-user-desc">Dispositivos en <?php echo $_SESSION["la_casa"]; ?></h5>
                                            </div>
                                            <div class="box-footer no-padding">
                                                <ul class="nav nav-stacked">
                                                    <li>
                                                        <a href="#">Dispositivos Configurados <span class="pull-right badge bg-blue">31</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Dispositivos Conectados <span class="pull-right badge bg-aqua">25</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Dispositivos desconectados <span class="pull-right badge bg-green">6</span></a>
                                                    </li>
                                                    <li>
                                                        <a id="link_list_dispo" href="#">Configurar Dispositivo <span class="pull-right badge bg-orange"><i class="fa fa-cogs"></i></span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!-- /.widget-user -->
                                        <div class="box-footer text-center">
                                            <a id="atras_dispositivos" href="" class="uppercase">Atras</a>
                                        </div>
                                    </div>
                                    <div id="formulario-dispo"></div>
                                </div>

                                <div id="config_sistema" class="box-body no-padding" hidden="true">
                                    <div class="col-md-12">
                                        <div id="datos_ubicacion" class='box box-widget widget-user'>
                                            <div class="box-footer">
                                                <div class="box-body no-padding">
                                                    <div class="box-body no-padding">
                                                        <div class="col-md-4 col-sm-6">
                                                                    <div class="card-container manual-flip">
                                                                        <div class="card">
                                                                            <div class="front">
                                                                                <div class="cover bg-green">
                                                                                </div>
                                                                                <div class="user">
                                                                                    <img class="img-circle" src="images/raspberrypi.png"/>
                                                                                </div>
                                                                                <div class="content">
                                                                                    <div class="main">
                                                                                        <h3 class="name">Reiniciar Todo</h3>
                                                                                        <p class="profession"></p>
                                                                                        <p class="text-center">Reinicia completamente el Sistema<br>"My <?php echo $_SESSION["la_casa"]; ?> "</p>
                                                                                    </div>
                                                                                    <div class="footer">
                                                                                        <input id="submit" type="button" value="Acceder" onclick="rotateCard(this)" class="btn btn-success">
                                                                                    </div>
                                                                                </div>
                                                                            </div> <!-- end front panel -->
                                                                            <div class="back">
                                                                                <div class="header">
                                                                                    <h5 class="motto">"Sistema"</h5>
                                                                                </div>
                                                                                <div class="content">
                                                                                    <div class="main">
                                                                                        <h4 class="text-center">Información</h4>
                                                                                        <p class="text-center">¿Esta segur@ de reiniciar todo el sistema?</p>

                                                                                        <div class="stats-container">
                                                                                            <div class="stats">
                                                                                                <input id="submit" type="button" value="Reiniciar" class="btn btn-success">
                                                                                            </div>
                                                                                            <div class="stats">
                                                                                                <h4></h4>
                                                                                                <p>

                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="stats">
                                                                                                <button id="btn_cerrar_form_casa" type="submit" onclick="rotateCard(this)" class="btn btn-warning">Cancelar</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        <div class="col-md-4 col-sm-6">
                                                                    <div class="card-container manual-flip">
                                                                        <div class="card">
                                                                            <div class="front">
                                                                                <div class="cover bg-red">

                                                                                </div>
                                                                                <div class="user">
                                                                                    <img class="img-circle" src="images/apache_log.png"/>
                                                                                </div>
                                                                                <div class="content">
                                                                                    <div class="main">
                                                                                        <h3 class="name">Reiniciar Apache</h3>
                                                                                        <p class="profession"></p>
                                                                                        <p class="text-center">Reinicia el servidor Web<br>"APACHE"</p>
                                                                                    </div>
                                                                                    <div class="footer">
                                                                                        <input id="submit" type="button" value="Acceder" onclick="rotateCard(this)" class="btn btn-success">
                                                                                    </div>
                                                                                </div>
                                                                            </div> <!-- end front panel -->
                                                                            <div class="back">
                                                                                <div class="header">
                                                                                    <h5 class="motto">"Servidor Web"</h5>
                                                                                </div>
                                                                                <div class="content">
                                                                                    <div class="main">
                                                                                        <h4 class="text-center">Información</h4>
                                                                                        <p class="text-center">¿Esta segur@ de reiniciar APACHE?</p>

                                                                                        <div class="stats-container">
                                                                                            <div class="stats">
                                                                                                <input id="submit" type="button" value="Reiniciar" class="btn btn-success">
                                                                                            </div>
                                                                                            <div class="stats">
                                                                                                <h4></h4>
                                                                                                <p>

                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="stats">
                                                                                                <button id="btn_cerrar_form_casa" type="submit" onclick="rotateCard(this)" class="btn btn-warning">Cancelar</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div> <!-- end back panel -->
                                                                    </div> <!-- end card -->
                                                                </div>
                                                        <div class="col-md-4 col-sm-6">
                                                                    <div class="card-container manual-flip">
                                                                        <div class="card">
                                                                            <div class="front">
                                                                                <div class="cover bg-blue">

                                                                                </div>
                                                                                <div class="user">
                                                                                    <img class="img-circle" src="images/mysql_128_px.png"/>
                                                                                </div>
                                                                                <div class="content">
                                                                                    <div class="main">
                                                                                        <h3 class="name">Reiniciar MySQL</h3>
                                                                                        <p class="profession"></p>
                                                                                        <p class="text-center">Reinicia el motor de Bases de Datos<br>"MySQL"</p>
                                                                                    </div>
                                                                                    <div class="footer">
                                                                                        <input id="submit" type="button" value="Acceder" onclick="rotateCard(this)" class="btn btn-success">
                                                                                    </div>
                                                                                </div>
                                                                            </div> <!-- end front panel -->
                                                                            <div class="back">
                                                                                <div class="header">
                                                                                    <h5 class="motto">"Servidor MySQL"</h5>
                                                                                </div>
                                                                                <div class="content">
                                                                                    <div class="main">
                                                                                        <h4 class="text-center">Información</h4>
                                                                                        <p class="text-center">¿Esta segur@ de reiniciar MySQL?</p>

                                                                                        <div class="stats-container">
                                                                                            <div class="stats">
                                                                                                <input id="submit" type="button" value="Reiniciar" class="btn btn-success">
                                                                                            </div>
                                                                                            <div class="stats">
                                                                                                <h4></h4>
                                                                                                <p>

                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="stats">
                                                                                                <button id="btn_cerrar_form_casa" type="submit" onclick="rotateCard(this)" class="btn btn-warning">Cancelar</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div> <!-- end back panel -->
                                                                    </div> <!-- end card -->
                                                                </div>
                                                        <div class="col-md-4 col-sm-6">
                                                            <div class="card-container manual-flip">
                                                                <div class="card">
                                                                    <div class="front">
                                                                        <div class="cover bg-yellow">

                                                                        </div>
                                                                        <div class="user">
                                                                            <img class="img-circle" src="images/MOSQUITO-128.png"/>
                                                                        </div>
                                                                        <div class="content">
                                                                            <div class="main">
                                                                                <h3 class="name">Reiniciar MQTT Broker</h3>
                                                                                <p class="profession"></p>
                                                                                <p class="text-center">Reinicia el Brocker Mosquitto<br>"MQTT"</p>
                                                                            </div>
                                                                            <div class="footer">
                                                                                <input id="submit" type="button" value="Acceder" onclick="rotateCard(this)" class="btn btn-success">
                                                                            </div>
                                                                        </div>
                                                                    </div> <!-- end front panel -->
                                                                    <div class="back">
                                                                        <div class="header">
                                                                            <h5 class="motto">"Brocker Mosquitto MQTT"</h5>
                                                                        </div>
                                                                        <div class="content">
                                                                            <div class="main">
                                                                                <h4 class="text-center">Información</h4>
                                                                                <p class="text-center">¿Esta segur@ de reiniciar Mosquitto MQTT?</p>

                                                                                <div class="stats-container">
                                                                                    <div class="stats">
                                                                                        <input id="submit" type="button" value="Reiniciar" class="btn btn-success">
                                                                                    </div>
                                                                                    <div class="stats">
                                                                                        <h4></h4>
                                                                                        <p>

                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="stats">
                                                                                        <button id="btn_cerrar_form_casa" type="submit" onclick="rotateCard(this)" class="btn btn-warning">Cancelar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- end back panel -->
                                                            </div> <!-- end card -->
                                                        </div>
                                                        <div class="col-md-4 col-sm-6">
                                                            <div class="card-container manual-flip">
                                                                <div class="card">
                                                                    <div class="front">
                                                                        <div class="cover bg-black">

                                                                        </div>
                                                                        <div class="user">
                                                                            <img class="img-circle" src="images/voicecommand.png"/>
                                                                        </div>
                                                                        <div class="content">
                                                                            <div class="main">
                                                                                <h3 class="name">Reiniciar VoiceCommand</h3>
                                                                                <p class="profession"></p>
                                                                                <p class="text-center">Reinicia el Reconocimiento de Voz<br>"VoiceComand"</p>
                                                                            </div>
                                                                            <div class="footer">
                                                                                <input id="submit" type="button" value="Acceder" onclick="rotateCard(this)" class="btn btn-success">
                                                                            </div>
                                                                        </div>
                                                                    </div> <!-- end front panel -->
                                                                    <div class="back">
                                                                        <div class="header">
                                                                            <h5 class="motto">"Reconocimiento de Vox VoiceCommand"</h5>
                                                                        </div>
                                                                        <div class="content">
                                                                            <div class="main">
                                                                                <h4 class="text-center">Información</h4>
                                                                                <p class="text-center">¿Esta segur@ de reiniciar VoiceCommand?</p>

                                                                                <div class="stats-container">
                                                                                    <div class="stats">
                                                                                        <input id="submit" type="button" value="Reiniciar" class="btn btn-success">
                                                                                    </div>
                                                                                    <div class="stats">
                                                                                        <h4></h4>
                                                                                        <p>

                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="stats">
                                                                                        <button id="btn_cerrar_form_casa" type="submit" onclick="rotateCard(this)" class="btn btn-warning">Cancelar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- end back panel -->
                                                            </div> <!-- end card -->
                                                        </div>
                                                        <div class="col-md-4 col-sm-6">
                                                            <div class="card-container manual-flip">
                                                                <div class="card">
                                                                    <div class="front">
                                                                        <div class="cover bg-blue">

                                                                        </div>
                                                                        <div class="user">
                                                                            <img class="img-circle" src="images/WhatSapp.jpeg"/>
                                                                        </div>
                                                                        <div class="content">
                                                                            <div class="main">
                                                                                <h3 class="name">Reiniciar WhatSapp</h3>
                                                                                <p class="profession"></p>
                                                                                <p class="text-center">Reinicia el servicio de mensajeria<br>"WhatSapp"</p>
                                                                            </div>
                                                                            <div class="footer">
                                                                                <input id="submit" type="button" value="Acceder" onclick="rotateCard(this)" class="btn btn-success">
                                                                            </div>
                                                                        </div>
                                                                    </div> <!-- end front panel -->
                                                                    <div class="back">
                                                                        <div class="header">
                                                                            <h5 class="motto">"Sistema de Mensajeria WhatSapp"</h5>
                                                                        </div>
                                                                        <div class="content">
                                                                            <div class="main">
                                                                                <h4 class="text-center">Información</h4>
                                                                                <p class="text-center">¿Esta segur@ de reiniciar WhatSapp?</p>

                                                                                <div class="stats-container">
                                                                                    <div class="stats">
                                                                                        <input id="submit" type="button" value="Reiniciar" class="btn btn-success">
                                                                                    </div>
                                                                                    <div class="stats">
                                                                                        <h4></h4>
                                                                                        <p>

                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="stats">
                                                                                        <button id="btn_cerrar_form_casa" type="submit" onclick="rotateCard(this)" class="btn btn-warning">Cancelar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- end back panel -->
                                                            </div> <!-- end card -->
                                                        </div>
                                                    </div>
                                                    <div class="box-footer text-center">
                                            <a id="atras_sistema" href="" class="uppercase">Atras</a>
                                        </div>
                                                </div>
                                                <div id="formulario-dispo"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>















    </div>

        <div id="pc" class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Panel de Control
            <small>Panel de Control</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Panel de Control</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Main row -->
              <div class="row">              
                <section class="col-md-6 connectedSortable">
<!-- ---------  INICIO UBICACIONES --------- -->
                    <div class="box box-primary expanded-box">
                        <div class="box-header with-border">
                            <i class="fa fa-map-marker"></i>
                            <h3 class="box-title">Ubicaciones</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <br>
                        <div class="box-body no-padding">
                            <ul class="users-list clearfix">
<?php
    mysql_select_db($database_conexion, $conexion);
    $query_ubicacion2 = "SELECT * FROM ubicacion ORDER BY ubicacion ASC";
    $ubicacion2 = mysql_query($query_ubicacion2, $conexion) or die(mysql_error());
    $row_ubicacion2 = mysql_fetch_assoc($ubicacion2);
    $totalRows_ubicacion2 = mysql_num_rows($ubicacion2);
?>

<?php
    do
    {
        $id_ubicacion2          = $row_ubicacion2['id_ubicacion'];
        $la_ubicacion2          = $row_ubicacion2['ubicacion'];
        $el_ubicacion2_imagen   = $row_ubicacion2['ubicacion_imagen'];

        if ($el_ubicacion2_imagen == "")
        {
            $ubicacion2_imagen = "images/exclamacion.jpg";
        }
        else
        {
            $ubicacion2_imagen = "images/" . $el_ubicacion2_imagen;
        }

        $las_ubicaciones = "
        <li>
            <a class='users-list-name'  href='#' id='la_ubicacion_seleccionada2' data-target='#modal_lugares' data-id='" . $id_ubicacion2 . "' data-toggle='modal' >
                <img class='img-circle' src='" . $ubicacion2_imagen . "' alt='" . $la_ubicacion2 . "'>
            </a>
             <span>" . $la_ubicacion2 . "</span>
        </li>";

        print($las_ubicaciones);

    }
    while ($row_ubicacion2 = mysql_fetch_assoc($ubicacion2));
?>

                            </ul>
                        </div>
                        <div class="box-footer text-center" hidden="false">
                            <a href="javascript::" class="uppercase"></a>
                        </div>
                        <br><br><br>
                    </div>

<!-- ---------  FIN UBICACIONES --------- -->


                </section>
                <section class="col-md-6 connectedSortable">
                  <!-- DIRECT CHAT -->
                    <div class="box box-warning direct-chat direct-chat-warning expanded-box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Chat</h3>
                      <div class="box-tools pull-right">
                        <span data-toggle="tooltip" title="3 New Messages" class="badge bg-yellow">3</span>
                          <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <!-- Conversations are loaded here -->
                      <div class="direct-chat-messages">
                        <!-- Message. Default to the left -->
                        <div class="direct-chat-msg">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">Alexander Pierce</span>
                            <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                          </div><!-- /.direct-chat-info -->
                          <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image"><!-- /.direct-chat-img -->
                          <div class="direct-chat-text">
                            Is this template really for free? That's unbelievable!
                          </div><!-- /.direct-chat-text -->
                        </div><!-- /.direct-chat-msg -->

                        <!-- Message to the right -->
                        <div class="direct-chat-msg right">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-right">Sarah Bullock</span>
                            <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>
                          </div><!-- /.direct-chat-info -->
                          <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image"><!-- /.direct-chat-img -->
                          <div class="direct-chat-text">
                            You better believe it!
                          </div><!-- /.direct-chat-text -->
                        </div><!-- /.direct-chat-msg -->

                        <!-- Message. Default to the left -->
                        <div class="direct-chat-msg">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">Alexander Pierce</span>
                            <span class="direct-chat-timestamp pull-right">23 Jan 5:37 pm</span>
                          </div><!-- /.direct-chat-info -->
                          <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image"><!-- /.direct-chat-img -->
                          <div class="direct-chat-text">
                            Working with AdminLTE on a great new app! Wanna join?
                          </div><!-- /.direct-chat-text -->
                        </div><!-- /.direct-chat-msg -->

                        <!-- Message to the right -->
                        <div class="direct-chat-msg right">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-right">Sarah Bullock</span>
                            <span class="direct-chat-timestamp pull-left">23 Jan 6:10 pm</span>
                          </div><!-- /.direct-chat-info -->
                          <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image"><!-- /.direct-chat-img -->
                          <div class="direct-chat-text">
                            I would love to.
                          </div><!-- /.direct-chat-text -->
                        </div><!-- /.direct-chat-msg -->

                      </div><!--/.direct-chat-messages-->

                      <!-- Contacts are loaded here -->
                      <div class="direct-chat-contacts">
                        <ul class="contacts-list">
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user1-128x128.jpg">
                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Count Dracula
                                  <small class="contacts-list-date pull-right">2/28/2015</small>
                                </span>
                                <span class="contacts-list-msg">How have you been? I was...</span>
                              </div><!-- /.contacts-list-info -->
                            </a>
                          </li><!-- End Contact Item -->
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user7-128x128.jpg">
                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Sarah Doe
                                  <small class="contacts-list-date pull-right">2/23/2015</small>
                                </span>
                                <span class="contacts-list-msg">I will be waiting for...</span>
                              </div><!-- /.contacts-list-info -->
                            </a>
                          </li><!-- End Contact Item -->
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user3-128x128.jpg">
                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Nadia Jolie
                                  <small class="contacts-list-date pull-right">2/20/2015</small>
                                </span>
                                <span class="contacts-list-msg">I'll call you back at...</span>
                              </div><!-- /.contacts-list-info -->
                            </a>
                          </li><!-- End Contact Item -->
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user5-128x128.jpg">
                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Nora S. Vans
                                  <small class="contacts-list-date pull-right">2/10/2015</small>
                                </span>
                                <span class="contacts-list-msg">Where is your new...</span>
                              </div><!-- /.contacts-list-info -->
                            </a>
                          </li><!-- End Contact Item -->
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user6-128x128.jpg">
                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  John K.
                                  <small class="contacts-list-date pull-right">1/27/2015</small>
                                </span>
                                <span class="contacts-list-msg">Can I take a look at...</span>
                              </div><!-- /.contacts-list-info -->
                            </a>
                          </li><!-- End Contact Item -->
                          <li>
                            <a href="#">
                              <img class="contacts-list-img" src="dist/img/user8-128x128.jpg">
                              <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Kenneth M.
                                  <small class="contacts-list-date pull-right">1/4/2015</small>
                                </span>
                                <span class="contacts-list-msg">Never mind I found...</span>
                              </div><!-- /.contacts-list-info -->
                            </a>
                          </li><!-- End Contact Item -->
                        </ul><!-- /.contatcts-list -->
                      </div><!-- /.direct-chat-pane -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                      <form action="#" method="post">
                        <div class="input-group">
                          <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                          <span class="input-group-btn">
                            <button type="button" type="button" class="btn btn-warning btn-flat">Send</button>
                          </span>
                        </div>
                      </form>
                    </div><!-- /.box-footer-->
                  </div><!--/.direct-chat -->
                </section><!-- /.col -->

                <section class="col-md-6 connectedSortable">
                  <!-- USERS LIST -->
                    <div class="box box-danger expanded-box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Usuarios</h3>
                      <div class="box-tools pull-right">
                        <span class="label label-danger">8 New Members</span>
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                      <ul class="users-list clearfix">
                        <li>
                          <img src="dist/img/user1-128x128.jpg" alt="User Image">
                          <a class="users-list-name" href="#">Alexander Pierce</a>
                          <span class="users-list-date">Today</span>
                        </li>
                        <li>
                          <img src="dist/img/user8-128x128.jpg" alt="User Image">
                          <a class="users-list-name" href="#">Norman</a>
                          <span class="users-list-date">Yesterday</span>
                        </li>
                        <li>
                          <img src="dist/img/user7-128x128.jpg" alt="User Image">
                          <a class="users-list-name" href="#">Jane</a>
                          <span class="users-list-date">12 Jan</span>
                        </li>
                        <li>
                          <img src="dist/img/user6-128x128.jpg" alt="User Image">
                          <a class="users-list-name" href="#">John</a>
                          <span class="users-list-date">12 Jan</span>
                        </li>
                        <li>
                          <img src="dist/img/user2-160x160.jpg" alt="User Image">
                          <a class="users-list-name" href="#">Alexander</a>
                          <span class="users-list-date">13 Jan</span>
                        </li>
                        <li>
                          <img src="dist/img/user5-128x128.jpg" alt="User Image">
                          <a class="users-list-name" href="#">Sarah</a>
                          <span class="users-list-date">14 Jan</span>
                        </li>
                        <li>
                          <img src="dist/img/user4-128x128.jpg" alt="User Image">
                          <a class="users-list-name" href="#">Nora</a>
                          <span class="users-list-date">15 Jan</span>
                        </li>
                        <li>
                          <img src="dist/img/user3-128x128.jpg" alt="User Image">
                          <a class="users-list-name" href="#">Nadia</a>
                          <span class="users-list-date">15 Jan</span>
                        </li>
                      </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->
                    <div class="box-footer text-center">
                      <a href="javascript::" class="uppercase">View All Users</a>
                    </div><!-- /.box-footer -->
                  </div><!--/.box -->
                </section><!-- /.col -->
              </div><!-- /.row -->



        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

        <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2017 <a href="http://www.fontech.cl">Fontech</a>.</strong>
      </footer>

        <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
          <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <!-- Home tab content -->
          <div class="tab-pane" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading">Actividad reciente</h3>
            <ul class="control-sidebar-menu">
              <li>
                <a href="javascript::;">
                  <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                    <p>Will be 23 on April 24th</p>
                  </div>
                </a>
              </li>
              <li>
                <a href="javascript::;">
                  <i class="menu-icon fa fa-user bg-yellow"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
                    <p>New phone +1(800)555-1234</p>
                  </div>
                </a>
              </li>
              <li>
                <a href="javascript::;">
                  <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
                    <p>nora@example.com</p>
                  </div>
                </a>
              </li>
              <li>
                <a href="javascript::;">
                  <i class="menu-icon fa fa-file-code-o bg-green"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
                    <p>Execution time 5 seconds</p>
                  </div>
                </a>
              </li>
            </ul><!-- /.control-sidebar-menu -->

            <h3 class="control-sidebar-heading">Tareas Progreso</h3>
            <ul class="control-sidebar-menu">
              <li>
                <a href="javascript::;">
                  <h4 class="control-sidebar-subheading">
                    Custom Template Design
                    <span class="label label-danger pull-right">70%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                  </div>
                </a>
              </li>
              <li>
                <a href="javascript::;">
                  <h4 class="control-sidebar-subheading">
                    Update Resume
                    <span class="label label-success pull-right">95%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                  </div>
                </a>
              </li>
              <li>
                <a href="javascript::;">
                  <h4 class="control-sidebar-subheading">
                    Laravel Integration
                    <span class="label label-warning pull-right">50%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                  </div>
                </a>
              </li>
              <li>
                <a href="javascript::;">
                  <h4 class="control-sidebar-subheading">
                    Back End Framework
                    <span class="label label-primary pull-right">68%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                  </div>
                </a>
              </li>
            </ul><!-- /.control-sidebar-menu -->

          </div><!-- /.tab-pane -->

          <!-- Settings tab content -->
          <div class="tab-pane" id="control-sidebar-settings-tab">
            <form method="post">
              <h3 class="control-sidebar-heading">Configuraci&oacute;n General</h3>
              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Informe de uso del panel
                  <input type="checkbox" class="pull-right" checked>
                </label>
                <p>
                  Parte de la informaci&oacute;n acerca de esta opci&oacute;n de configuraci&oacute;n generales
                </p>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Permitir redirecci&oacute;n de correo
                  <input type="checkbox" class="pull-right" checked>
                </label>
                <p>
                  Otros grupos de opciones est&aacute;n disponibles
                </p>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Exponer el nombre del autor en los post
                  <input type="checkbox" class="pull-right" checked>
                </label>
                <p>
                  Permitir al usuario mostrar su nombre en las entradas del blog
                </p>
              </div><!-- /.form-group -->

              <h3 class="control-sidebar-heading">Configuraciones de chat</h3>

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Mu&eacute;strame como en l&iacute;nea
                  <input type="checkbox" class="pull-right" checked>
                </label>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Desactivar las notificaciones
                  <input type="checkbox" class="pull-right">
                </label>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Eliminar el historial de chat
                  <a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                </label>
              </div><!-- /.form-group -->
            </form>
          </div><!-- /.tab-pane -->
        </div>
      </aside><!-- /.control-sidebar -->

        <div class="control-sidebar-bg"></div>

    </div>

    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/chartjs/Chart.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/jquery.jdigiclock.js"></script>
<!--
    <script src="js/jquery.bootstrap.wizard.js" type="text/javascript"></script>
    <script src="js/paper-bootstrap-wizard.js" type="text/javascript"></script>
    <script src="js/jquery.validate.min.js" type="text/javascript"></script>
-->
    

    <script>
          $('#digiclock').jdigiclock({
              imagesPath : 'images/', // Base path to image files. Clock and Weather images are located in subdirectories below this
              lang: 'en', // Language of date : fr or en
              am_pm : false, // Specifies the AM/PM option.
              weatherLocationCode : '349885', // Weather location code (see lookup: woeid.rosselliot.co.nz).
              weatherMetric : 'C', // Specifies the weather metric mode: c or f.
              weatherUpdate : '60', // Weather update in minutes.
              svrOffset: 0  // Server offset in milliseconds.
          });
    </script>

    <script type="text/javascript">
    function rotateCard(btn)
    {
        var $card = $(btn).closest('.card-container');
        console.log($card);
        if($card.hasClass('hover'))
        {
            $card.removeClass('hover');
        }
        else
        {
            $card.addClass('hover');
        }
    }
    </script>


    <script src="js/funciones.js"></script>

    <script src="chosen/chosen.jquery.js"></script>
    <script src="js/prettify.js" type="text/javascript"></script>
    <script src="js/bootstrap-wizard.js" type="text/javascript"></script>


  </body>
</html>

<?php
    if ($ubicacion){mysql_free_result($ubicacion);}
?>
