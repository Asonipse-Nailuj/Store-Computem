<?php
session_start();

if (empty($_SESSION["user"])) {
  header("Location: login.php");
}

include_once "conexion.php";
include_once "funciones.php";

if (!permiso($_SESSION["user"], "2", $conexion)) {
  header("Location: acceso_negado.php");
}

if (isset($_POST["editar"])) {
  $sentencia = $conexion->prepare("UPDATE inventario SET nombre_producto = ?, descripcion = ?, valor_unitario = ?, cantidad = ? WHERE id = ?;");
  $sentencia->execute([$_POST["edit_nombre"], $_POST["edit_descripcion"], $_POST["edit_valor"], $_POST["edit_cantidad"], $_POST["edit_id"]]);

  $editado = true;
}

if (isset($_GET["estado"]) && isset($_GET["cod"])) {
  $estado = ($_GET["estado"] == "s") ? "n" : "s";

  $sentencia = $conexion->prepare("UPDATE inventario SET estado = ? WHERE id = ?;");
  $sentencia->execute([$estado, $_GET["cod"]]);

  $cambio_estado = true;
}
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

  <title>Store Computem | Mostrar Inventario</title>

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

  <!-- PNotify -->
  <link href="vendors/pnotify/dist/pnotify.css" rel="stylesheet">
  <link href="vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
  <link href="vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

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
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Mostrar Inventario</h3>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
            <?php
            if (!empty($editado)) {
              echo '<div class="alert alert-info alert-dismissible " role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                      <strong>DATOS ACTUALIZADOS!</strong> El producto ha sido actualizado en el sistema.
                    </div>';
            } elseif (!empty($cambio_estado)) {
              echo '<div class="alert alert-warning alert-dismissible " role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                      <strong>ESTADO CAMBIADO!</strong> El estado del producto ha sido modificado en el sistema.
                    </div>';
            }
            ?>
            <div class="col-md-12 col-sm-12 ">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Listado de Inventario</h2>
                  <ul class="nav navbar-right panel_toolbox">
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card-box table-responsive">
                        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                              <th>Id</th>
                              <th>Nombre</th>
                              <th>Descripción</th>
                              <th>Valor Unitario</th>
                              <th>Cantidad</th>
                              <th>Estado</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $registros = $conexion->query("SELECT * FROM inventario") or die($conexion->error);
                            $inventario = $registros->fetchAll(PDO::FETCH_OBJ);

                            foreach ($inventario as $producto) {
                              echo "<tr>";
                              echo "<td>", $producto->id, "</td>";
                              echo "<td>", $producto->nombre_producto, "</td>";
                              echo "<td>", $producto->descripcion, "</td>";
                              echo "<td>", $producto->valor_unitario, "</td>";
                              echo "<td>", $producto->cantidad, "</td>";
                              $estado = $producto->estado;
                              if ($estado == "s") {
                                $color = "danger";
                                $icono = "fa-ban";
                                echo "<td class='text-success'>Activo</td>";
                              } else {
                                $color = "success";
                                $icono = "fa-check";
                                echo "<td class='text-danger'>Inactivo</td>";
                              }
                              echo "<td><button class='btn btn-info' data-toggle='modal' data-target='#editar_inventario" . $producto->id . "'><i class='fa fa-edit'></i></button>  
                              <a class='btn btn-" . $color . "' href='listar_inventario.php?estado=" . $estado . "&cod=" . $producto->id . "'><i class='fa " . $icono . "'></i></a></td>";
                              echo "</tr>";
                            ?>
                              <div class="modal fade" id="editar_inventario<?php echo $producto->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                  <div class="modal-content">

                                    <div class="modal-header">
                                      <h4 class="modal-title" id="myModalLabel2">Editar Inventario</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <form action="" method="POST">
                                        <div>
                                          <input readonly type="text" name="edit_id" class="form-control" placeholder="ID" value="<?php echo $producto->id; ?>" required="" />
                                        </div>
                                        <br>
                                        <div>
                                          <input type="text" name="edit_nombre" class="form-control" placeholder="Nombre Producto" value="<?php echo $producto->nombre_producto; ?>" required="" />
                                        </div>
                                        <br>
                                        <div>
                                          <textarea type="text" name="edit_descripcion" class="form-control" placeholder="Descripcion" required=""><?php echo $producto->descripcion; ?></textarea>
                                        </div>
                                        <br>
                                        <div>
                                          <input type="number" name="edit_valor" class="form-control" placeholder="Valor Unitario" value="<?php echo $producto->valor_unitario; ?>" required="" />
                                        </div>
                                        <br>
                                        <div>
                                          <input type="number" name="edit_cantidad" class="form-control" placeholder="Cantidad" value="<?php echo $producto->cantidad; ?>" required="" />
                                        </div>
                                        <br>
                                        <div class="modal-footer">
                                          <button type="submit" class="btn btn-info" name="editar">Guardar Cambios</button>
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
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
  <!-- iCheck -->
  <script src="vendors/iCheck/icheck.min.js"></script>
  <!-- PNotify -->
  <script src="vendors/pnotify/dist/pnotify.js"></script>
  <script src="vendors/pnotify/dist/pnotify.buttons.js"></script>
  <script src="vendors/pnotify/dist/pnotify.nonblock.js"></script>
  <!-- Datatables -->
  <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
  <script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
  <script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
  <script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
  <script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
  <script src="vendors/jszip/dist/jszip.min.js"></script>
  <script src="vendors/pdfmake/build/pdfmake.min.js"></script>
  <script src="vendors/pdfmake/build/vfs_fonts.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="build/js/custom.min.js"></script>

</body>

</html>