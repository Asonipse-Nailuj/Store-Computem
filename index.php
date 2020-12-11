<?php
session_start();

if (empty($_SESSION["user"])) {
  header("Location: login.php");
}

include_once "conexion.php";
include_once "funciones.php";
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

  <title>Store Computem | Inicio</title>

  <!-- Bootstrap -->
  <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

  <!-- NProgress -->
  <!-- <link href="vendors/nprogress/nprogress.css" rel="stylesheet"> -->

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
        <div class="row" style="display: inline-block;">
          <div class="tile_count">
            <?php
            $registro = $conexion->query("SELECT SUM(total) AS 'total_dia' FROM item_venta WHERE estado = 's' AND fecha = CURDATE()") or die($conexion->error);
            $dia = $registro->fetchAll(PDO::FETCH_OBJ);

            foreach ($dia as $row) {
              $ganancia = $row->total_dia;
            }

            $valor_dia = (empty($ganancia)) ? 0 : $ganancia;
            ?>
            <div class="col-sm-6  tile_stats_count">
              <span class="count_top"><i class="fa fa-dollar"></i> Ganancias del dia</span>
              <div class="count">$<?php echo $valor_dia; ?></div>
            </div>

            <?php
            $registro = $conexion->query("SELECT SUM(cantidad) AS 'cant_dia' FROM item_detalle_venta JOIN item_venta ON item_detalle_venta.factura = item_venta.id WHERE estado = 's' AND fecha = CURDATE()") or die($conexion->error);
            $dia = $registro->fetchAll(PDO::FETCH_OBJ);

            foreach ($dia as $row) {
              $cantidad = $row->cant_dia;
            }

            $cant_dia = (empty($cantidad)) ? 0 : $cantidad;
            ?>
            <div class="col-sm-6  tile_stats_count">
              <span class="count_top"><i class="fa fa-bar-chart"></i> Cantidad de productos</span>
              <div class="count"><?php echo $cant_dia; ?></div>
              <span class="count_bottom"> Ventas del dia</span>
            </div>

            <?php
            $registro = $conexion->query("SELECT SUM(total) AS 'total_mes' FROM item_venta WHERE estado = 's' AND MONTH(fecha) = MONTH(NOW())") or die($conexion->error);
            $mes = $registro->fetchAll(PDO::FETCH_OBJ);

            foreach ($mes as $row) {
              $ganancia = $row->total_mes;
            }

            $valor_mes = (empty($ganancia)) ? 0 : $ganancia;
            ?>
            <div class="col-sm-6  tile_stats_count">
              <span class="count_top"><i class="fa fa-dollar"></i> Ganancias del mes</span>
              <div class="count">$<?php echo $valor_mes; ?></div>
            </div>

            <?php
            $registro = $conexion->query("SELECT SUM(cantidad) AS 'cant_mes' FROM item_detalle_venta JOIN item_venta ON item_detalle_venta.factura = item_venta.id WHERE estado = 's' AND MONTH(fecha) = MONTH(NOW())") or die($conexion->error);
            $mes = $registro->fetchAll(PDO::FETCH_OBJ);

            foreach ($mes as $row) {
              $cantidad = $row->cant_mes;
            }

            $cant_mes = (empty($cantidad)) ? 0 : $cantidad;
            ?>
            <div class="col-sm-6  tile_stats_count">
              <span class="count_top"><i class="fa fa-bar-chart"></i> Cantidad de productos</span>
              <div class="count"><?php echo $cant_mes; ?></div>
              <span class="count_bottom"> Ventas del mes</span>
            </div>

            <?php
            $registro = $conexion->query("SELECT SUM(total) AS 'total_anio' FROM item_venta WHERE estado = 's' AND YEAR(fecha) = YEAR(NOW())") or die($conexion->error);
            $dia = $registro->fetchAll(PDO::FETCH_OBJ);

            foreach ($dia as $row) {
              $ganancia = $row->total_anio;
            }

            $valor_anio = (empty($ganancia)) ? 0 : $ganancia;
            ?>
            <div class="col-sm-6  tile_stats_count">
              <span class="count_top"><i class="fa fa-dollar"></i> Ganancias del año</span>
              <div class="count">$<?php echo $valor_anio; ?></div>
            </div>

            <?php
            $registro = $conexion->query("SELECT SUM(cantidad) AS 'cant_anio' FROM item_detalle_venta JOIN item_venta ON item_detalle_venta.factura = item_venta.id WHERE estado = 's' AND YEAR(fecha) = YEAR(NOW())") or die($conexion->error);
            $anio = $registro->fetchAll(PDO::FETCH_OBJ);

            foreach ($anio as $row) {
              $cantidad = $row->cant_anio;
            }

            $cant_anio = (empty($cantidad)) ? 0 : $cantidad;
            ?>
            <div class="col-sm-6  tile_stats_count">
              <span class="count_top"><i class="fa fa-bar-chart"></i> Cantidad de productos</span>
              <div class="count"><?php echo $cant_anio; ?></div>
              <span class="count_bottom"> Ventas del año</span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 widget widget_tally_box">
            <div class="x_panel fixed_height_390">
              <div class="x_title">
                <h2>Más vendidos del dia</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div style="text-align: center; margin-bottom: 17px">
                  <ul class="verticle_bars list-inline" style="display: flex;">
                    <?php
                    $consulta = $conexion->query("SELECT SUM(cantidad) AS 'cant' FROM item_detalle_venta JOIN item_venta ON item_detalle_venta.factura = item_venta.id WHERE fecha = CURDATE()") or die($conexion->error);

                    $cantidad = $consulta->fetchAll(PDO::FETCH_OBJ);

                    foreach ($cantidad as $row) {
                      $cant_total = $row->cant;
                    }

                    $registro = $conexion->query("SELECT nombre_producto, SUM(item_detalle_venta.cantidad) AS 'cant_venta' FROM item_detalle_venta JOIN inventario ON item_detalle_venta.producto = inventario.id JOIN item_venta ON item_detalle_venta.factura = item_venta.id WHERE fecha = CURDATE() GROUP BY producto ORDER BY item_detalle_venta.cantidad DESC LIMIT 5") or die($conexion->error);

                    $productos = $registro->fetchAll(PDO::FETCH_OBJ);

                    $color_barra = array("success", "danger", "dark", "info", "gray");

                    $porcentajes = array();
                    $nombres = array();
                    $cantidades = array();
                    foreach ($productos as $row) {
                      $valor = (100 * $row->cant_venta) / $cant_total;

                      array_push($porcentajes, $valor);
                      array_push($nombres, $row->nombre_producto);
                      array_push($cantidades, $row->cant_venta);
                    }

                    for ($i = 0; $i < count($porcentajes); $i++) {
                    ?>
                      <li>
                        <div class="progress vertical progress_wide bottom">
                          <div class="progress-bar progress-bar-<?php echo $color_barra[$i]; ?>" role="progressbar" data-transitiongoal="<?php echo $porcentajes[$i]; ?>" aria-valuenow="<?php echo $porcentajes[$i]; ?>" style="height: 100%;"><?php echo $cantidades[$i]; ?></div>
                        </div>
                      </li>
                    <?php
                    }
                    ?>
                  </ul>
                </div>
                <div class="divider"></div>
                <ul class="legend list-unstyled">
                  <?php
                  $color_icono = array("green", "red", "dark", "blue", "gray");

                  for ($i = 0; $i < count($nombres); $i++) {
                  ?>
                    <li>
                      <p>
                        <span class="icon"><i class="fa fa-square <?php echo $color_icono[$i]; ?>"></i></span> <span class="name"><?php echo $nombres[$i]; ?></span>
                      </p>
                    </li>
                  <?php
                  }
                  ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4 widget widget_tally_box">
            <div class="x_panel fixed_height_390">
              <div class="x_title">
                <h2>Más vendidos del mes</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div style="text-align: center; margin-bottom: 17px">
                  <ul class="verticle_bars list-inline" style="display: flex;">
                    <?php
                    $consulta = $conexion->query("SELECT SUM(cantidad) AS 'cant' FROM item_detalle_venta JOIN item_venta ON item_detalle_venta.factura = item_venta.id WHERE MONTH(fecha) = MONTH(NOW())") or die($conexion->error);

                    $cantidad = $consulta->fetchAll(PDO::FETCH_OBJ);

                    foreach ($cantidad as $row) {
                      $cant_total = $row->cant;
                    }

                    $registro = $conexion->query("SELECT nombre_producto, SUM(item_detalle_venta.cantidad) AS 'cant_venta' FROM item_detalle_venta JOIN inventario ON item_detalle_venta.producto = inventario.id JOIN item_venta ON item_detalle_venta.factura = item_venta.id WHERE MONTH(fecha) = MONTH(NOW()) GROUP BY producto ORDER BY item_detalle_venta.cantidad DESC LIMIT 5") or die($conexion->error);

                    $productos = $registro->fetchAll(PDO::FETCH_OBJ);

                    $color_barra = array("success", "danger", "dark", "info", "gray");

                    $porcentajes = array();
                    $nombres = array();
                    $cantidades = array();
                    foreach ($productos as $row) {
                      $valor = (100 * $row->cant_venta) / $cant_total;

                      array_push($porcentajes, $valor);
                      array_push($nombres, $row->nombre_producto);
                      array_push($cantidades, $row->cant_venta);
                    }

                    for ($i = 0; $i < count($porcentajes); $i++) {
                    ?>
                      <li>
                        <div class="progress vertical progress_wide bottom">
                          <div class="progress-bar progress-bar-<?php echo $color_barra[$i]; ?>" role="progressbar" data-transitiongoal="<?php echo $porcentajes[$i]; ?>" aria-valuenow="<?php echo $porcentajes[$i]; ?>" style="height: 100%;"><?php echo $cantidades[$i]; ?></div>
                        </div>
                      </li>
                    <?php
                    }
                    ?>
                  </ul>
                </div>
                <div class="divider"></div>
                <ul class="legend list-unstyled">
                  <?php
                  $color_icono = array("green", "red", "dark", "blue", "gray");

                  for ($i = 0; $i < count($nombres); $i++) {
                  ?>
                    <li>
                      <p>
                        <span class="icon"><i class="fa fa-square <?php echo $color_icono[$i]; ?>"></i></span> <span class="name"><?php echo $nombres[$i]; ?></span>
                      </p>
                    </li>
                  <?php
                  }
                  ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4 widget widget_tally_box">
            <div class="x_panel fixed_height_390">
              <div class="x_title">
                <h2>Más vendidos del año</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <div style="text-align: center; margin-bottom: 17px">
                  <ul class="verticle_bars list-inline" style="display: flex;">
                    <?php
                    $consulta = $conexion->query("SELECT SUM(cantidad) AS 'cant' FROM item_detalle_venta JOIN item_venta ON item_detalle_venta.factura = item_venta.id WHERE YEAR(fecha) = YEAR(NOW())") or die($conexion->error);

                    $cantidad = $consulta->fetchAll(PDO::FETCH_OBJ);

                    foreach ($cantidad as $row) {
                      $cant_total = $row->cant;
                    }

                    $registro = $conexion->query("SELECT nombre_producto, SUM(item_detalle_venta.cantidad) AS 'cant_venta' FROM item_detalle_venta JOIN inventario ON item_detalle_venta.producto = inventario.id JOIN item_venta ON item_detalle_venta.factura = item_venta.id WHERE YEAR(fecha) = YEAR(NOW()) GROUP BY producto ORDER BY item_detalle_venta.cantidad DESC LIMIT 5") or die($conexion->error);

                    $productos = $registro->fetchAll(PDO::FETCH_OBJ);

                    $color_barra = array("success", "danger", "dark", "info", "gray");

                    $porcentajes = array();
                    $nombres = array();
                    $cantidades = array();
                    foreach ($productos as $row) {
                      $valor = (100 * $row->cant_venta) / $cant_total;

                      array_push($porcentajes, $valor);
                      array_push($nombres, $row->nombre_producto);
                      array_push($cantidades, $row->cant_venta);
                    }

                    for ($i = 0; $i < count($porcentajes); $i++) {
                    ?>
                      <li>
                        <div class="progress vertical progress_wide bottom">
                          <div class="progress-bar progress-bar-<?php echo $color_barra[$i]; ?>" role="progressbar" data-transitiongoal="<?php echo $porcentajes[$i]; ?>" aria-valuenow="<?php echo $porcentajes[$i]; ?>" style="height: 100%;"><?php echo $cantidades[$i]; ?></div>
                        </div>
                      </li>
                    <?php
                    }
                    ?>
                  </ul>
                </div>
                <div class="divider"></div>
                <ul class="legend list-unstyled">
                  <?php
                  $color_icono = array("green", "red", "dark", "blue", "gray");

                  for ($i = 0; $i < count($nombres); $i++) {
                  ?>
                    <li>
                      <p>
                        <span class="icon"><i class="fa fa-square <?php echo $color_icono[$i]; ?>"></i></span> <span class="name"><?php echo $nombres[$i]; ?></span>
                      </p>
                    </li>
                  <?php
                  }
                  ?>
                </ul>
              </div>
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
  <!-- Chart.js -->
  <script src="vendors/Chart.js/dist/Chart.min.js"></script>
  <!-- gauge.js -->
  <script src="vendors/gauge.js/dist/gauge.min.js"></script>
  <!-- bootstrap-progressbar -->
  <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- iCheck -->
  <script src="vendors/iCheck/icheck.min.js"></script>
  <!-- Skycons -->
  <script src="vendors/skycons/skycons.js"></script>
  <!-- Flot -->
  <script src="vendors/Flot/jquery.flot.js"></script>
  <script src="vendors/Flot/jquery.flot.pie.js"></script>
  <script src="vendors/Flot/jquery.flot.time.js"></script>
  <script src="vendors/Flot/jquery.flot.stack.js"></script>
  <script src="vendors/Flot/jquery.flot.resize.js"></script>
  <!-- Flot plugins -->
  <script src="vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
  <script src="vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
  <script src="vendors/flot.curvedlines/curvedLines.js"></script>
  <!-- DateJS -->
  <script src="vendors/DateJS/build/date.js"></script>
  <!-- JQVMap -->
  <script src="vendors/jqvmap/dist/jquery.vmap.js"></script>
  <script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
  <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="vendors/moment/min/moment.min.js"></script>
  <script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="build/js/custom.min.js"></script>

</body>

</html>