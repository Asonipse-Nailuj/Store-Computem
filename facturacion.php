<?php
session_start();

if (empty($_SESSION["user"])) {
  header("Location: login.php");
}

include_once "conexion.php";
include_once "funciones.php";

if (!permiso($_SESSION["user"], "1", $conexion)) {
  header("Location: acceso_negado.php");
}

date_default_timezone_set('America/Bogota');
$fechaActual = date('d / m / Y');

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/laptop.ico" type="image/ico" />

  <title>Store Computem | Facturación</title>

  <!-- Bootstrap -->
  <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">

  <!-- bootstrap-progressbar -->
  <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- JQVMap -->
  <link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
  <!-- bootstrap-daterangepicker -->
  <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="index.php" class="site_title"><i class="fa fa-laptop"></i> <span>Store Computem</span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <div class="profile clearfix">
            <div class="profile_pic">
              <img src="images/user.png" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Bienvenido,</span>
              <h2><?php echo $_SESSION["user_name"]; ?></h2>
            </div>
          </div>
          <!-- /menu profile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <h3>General</h3>
              <ul class="nav side-menu">
                <li><a href="index.php"><i class="fa fa-home"></i> Inicio</a></li>

                <?php if (permiso($_SESSION["user"], "2", $conexion)) { ?>
                  <li><a><i class="fa fa-list-alt"></i> Inventario <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="nuevo_inventario.php">Crear</a></li>
                      <li><a href="listar_inventario.php">Mostrar</a></li>
                    </ul>
                  </li>
                <?php } ?>

                <?php if (permiso($_SESSION["user"], "1", $conexion)) { ?>
                  <li><a href="facturacion.php"><i class="fa fa-edit"></i> Facturación</a></li>
                <?php } ?>

                <?php if (permiso($_SESSION["user"], "3", $conexion)) { ?>
                  <li><a><i class="fa fa-group"></i> Clientes <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="nuevo_cliente.php">Crear</a></li>
                      <li><a href="listar_cliente.php">Mostrar</a></li>
                    </ul>
                  </li>
                <?php } ?>

                <?php if (permiso($_SESSION["user"], "4", $conexion)) { ?>
                  <li><a><i class="fa fa-user"></i> Usuarios <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="nuevo_usuario.php">Crear</a></li>
                      <li><a href="listar_usuario.php">Mostrar</a></li>
                    </ul>
                  </li>
                <?php } ?>

                <?php if (permiso($_SESSION["user"], "5", $conexion)) { ?>
                  <li><a href="reporte_ventas.php"><i class="fa fa-bar-chart"></i> Reporte de Ventas</a></li>
                <?php } ?>

                <?php if (permiso($_SESSION["user"], "6", $conexion)) { ?>
                  <li><a href="permisos.php"><i class="fa fa-unlock"></i> Permisos</a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
          <!-- /sidebar menu -->
        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav">
        <div class="nav_menu">
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>
          <nav class="nav navbar-nav">
            <ul class=" navbar-right">
              <li class="nav-item dropdown open" style="padding-left: 15px;">
                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                  <img src="images/user.png" alt=""><?php echo $_SESSION["user"]; ?>
                </a>
                <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="perfil.php"> Perfil</a>
                  <a class="dropdown-item" href="cerrar_sesion.php"><i class="fa fa-sign-out pull-right"></i> Cerrar Sesion</a>
                </div>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- /top navigation -->

      <div class="right_col" role="main">
        <div>
          <div class="page-title">
            <div class="title_left">
              <h3>Nueva Factura</h3>
            </div>

            <div class="title_right">
              <div class="col-md-5 col-sm-5  form-group pull-right top_search">
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div id="mensaje"></div>
            <?php
            if (isset($_GET["res"])) {
              if ($_GET["res"] == "true") {
                echo '<div class="alert alert-success alert-dismissible " role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>TODO CORRECTO!</strong> La factura ha sido registrada en el sistema.
                      </div>';
              } else {
                echo '<div class="alert alert-danger alert-dismissible " role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>HA OCURRIDO UN PROBLEMA!</strong> La factura no se ha podido generar, vuelve a intentarlo. Si el problema persiste contacta a un administrador.
                      </div>';
              }
            }
            ?>
            <div class="col-md-12 ">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Datos de Factura</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <form class="form-label-left input_mask">
                    <div class="col-md-12 col-sm-12 orm-group has-feedback">
                      <h3>Cliente</h3>
                      <span>(Presiona la tecla ENTER para confirmar el cliente)</span>
                    </div>

                    <div class="col-md-6 col-sm-6  form-group has-feedback">
                      <input type="text" class="form-control has-feedback-left" id="documento" placeholder="Documento">
                      <span class="fa fa-list-alt form-control-feedback left" aria-hidden="true"></span>
                    </div>

                    <div class="col-md-6 col-sm-6  form-group has-feedback">
                      <input type="text" class="form-control" id="nombre_cliente" placeholder="Nombre Cliente" readonly>
                      <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                    </div>

                    <div class="col-md-12 col-sm-12 orm-group has-feedback">
                      <h3>Vendedor</h3>
                    </div>
                    <div class="col-md-6 col-sm-6  form-group has-feedback">
                      <input type="email" class="form-control has-feedback-left" id="usuario" placeholder="Usuario" value="<?php echo $_SESSION['user']; ?>" readonly>
                      <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                    </div>

                    <div class="col-md-6 col-sm-6  form-group has-feedback">
                      <input type="tel" class="form-control" id="nombre_vendedor" placeholder="Nombre Vendedor" value="<?php echo $_SESSION['user_name']; ?>" readonly>
                      <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                    </div>

                    <div class="col-md-12 col-sm-12 form-group has-feedback">
                      <h3>Fecha</h3>
                      <span>(dia / mes / año)</span>
                    </div>
                    <div class="col-md-6 col-sm-6  form-group has-feedback">
                      <input type="text" class="form-control has-feedback-left" id="fecha" placeholder="Fecha" value="<?php echo $fechaActual; ?>" readonly>
                      <span class="fa fa-calendar form-control-feedback left" aria-hidden="true"></span>
                      <br>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>

        <div class="col-md-12 col-sm-12  ">
          <div class="x_panel">
            <div class="x_title">
              <h2>Buscar Producto</h2>
              <ul class="nav navbar-right panel_toolbox">
              </ul>
              <div class="clearfix"></div>
            </div>

            <div class="x_content">

              <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                  <thead>
                    <tr class="headings">
                      <th class="column-title">Id Producto </th>
                      <th class="column-title">Nombre </th>
                      <th class="column-title">Precio </th>
                      <th class="column-title">Cantidad </th>
                      <th class="column-title">Subtotal </th>
                      <th class="column-title"> </th>

                    </tr>
                  </thead>

                  <tbody>
                    <tr class="even pointer">
                      <td><input type="text" id="cod_producto" placeholder="Codigo Producto"></td>
                      <td id="nombre_producto"></td>
                      <td id="precio_producto"></td>
                      <td id="cantidad_producto"></td>
                      <td id="subtotal"></td>
                      <td class="last"><button type="button" id="agregar_producto" class="btn btn-success">Agregar</button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-sm-12  ">
          <div class="x_panel">
            <div class="x_title">
              <h2>Lista de Productos</h2>
              <ul class="nav navbar-right panel_toolbox">
              </ul>
              <div class="clearfix"></div>
            </div>

            <div class="x_content">

              <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                  <thead>
                    <tr class="headings">
                      <th class="column-title">Id Producto </th>
                      <td class="column-title">Nombre </th>
                      <th class="column-title">Precio </th>
                      <th class="column-title">Cantidad </th>
                      <th class="column-title">Subtotal </th>
                      <th class="column-title">Acciones </th>
                    </tr>
                  </thead>

                  <tbody id="lista_productos">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-8 col-sm-8"></div>
        <div class="col-md-4 col-sm-4  pull-right">
          <div class="x_panel">
            <div class="x_content">
              <table class="table">
                <thead>
                </thead>
                <tbody>
                  <tr>
                    <td class="badge badge-dark">SUBTOTAL</td>
                    <td id="lista_subtotal" class="text-right">00.00</td>
                  </tr>
                  <tr>
                    <td class="badge badge-warning">IVA (16%)</td>
                    <td id="lista_iva" class="text-right">00.00</td>
                  </tr>
                  <tr>
                    <td class="badge badge-info">PRECIO TOTAL</td>
                    <td id="lista_total" class="text-right">00.00</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><button class="btn btn-secondary" id="generar_factura">Generar Factura</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- footer content -->
      <footer>
        <div class="pull-right">
          Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>

  <!-- jQuery -->

  <script src="vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- FastClick -->
  <script src="vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="vendors/nprogress/nprogress.js"></script>
  <!-- bootstrap-progressbar -->
  <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- iCheck -->
  <script src="vendors/iCheck/icheck.min.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="vendors/moment/min/moment.min.js"></script>
  <script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap-wysiwyg -->
  <script src="vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
  <script src="vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
  <script src="vendors/google-code-prettify/src/prettify.js"></script>
  <!-- jQuery Tags Input -->
  <script src="vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
  <!-- Switchery -->
  <script src="vendors/switchery/dist/switchery.min.js"></script>
  <!-- Select2 -->
  <script src="vendors/select2/dist/js/select2.full.min.js"></script>
  <!-- Parsley -->
  <script src="vendors/parsleyjs/dist/parsley.min.js"></script>
  <!-- Autosize -->
  <script src="vendors/autosize/dist/autosize.min.js"></script>
  <!-- jQuery autocomplete -->
  <script src="vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
  <!-- starrr -->
  <script src="vendors/starrr/dist/starrr.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="build/js/custom.min.js"></script>

  <script src="js/factura.js"></script>

</body>

</html>