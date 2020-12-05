<?php
session_start();

if (empty($_SESSION["user"])) {
  header("Location: login.php");
}

include_once "conexion.php";
include_once "funciones.php";

if (!permiso($_SESSION["user"], "6", $conexion)) {
  header("Location: acceso_negado.php");
}

if (isset($_POST["editar"])) {
  $sentencia = $conexion->prepare("UPDATE permiso SET estado = ? WHERE id = ?;");
  $sentencia->execute([$_POST["edit_estado"], $_POST["edit_id"]]);

  $editado = true;
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

  <title>Store Computem | Permisos</title>

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

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Permisos</h3>
            </div>

          </div>
          <div class="clearfix"></div>

          <div class="col-md-6 col-sm-6  ">
            <?php
            if (!empty($editado)) {
              echo '<div class="alert alert-info alert-dismissible " role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                      <strong>ESTADO ACTUALIZADO!</strong> El estado del permiso para el usuario ha sido actualizado en el sistema.
                    </div>';
            }
            ?>
            <div class="x_panel">
              <div class="x_title">
                <h2>Permisos del Sistema</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <table class="table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre del permiso</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $registros = $conexion->query("SELECT * FROM permiso_tmp") or die($conexion->error);

                    $permisos_tmp = $registros->fetchAll(PDO::FETCH_OBJ);

                    foreach ($permisos_tmp as $tmp) {

                      echo "<tr>";
                      echo "<td>", $tmp->id, "</td>";
                      echo "<td>", $tmp->nombre, "</td>";
                      echo "</tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
              <div class="x_title">
                <h2>Permisos Asignados</h2>
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
                            <th>Codigo</th>
                            <th>ID Permiso</th>
                            <th>Nombre Usuario</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php

                          $registros = $conexion->query("SELECT * FROM permiso") or die($conexion->error);

                          $permisos = $registros->fetchAll(PDO::FETCH_OBJ);

                          foreach ($permisos as $reg) {
                            echo "<tr>";
                            echo "<td>", $reg->id, "</td>";
                            echo "<td>", $reg->permiso, "</td>";
                            echo "<td>", $reg->user, "</td>";
                            $estado = $reg->estado;
                            if ($estado == "s") {
                              echo "<td class='text-success'>Activo</td>";
                            } else {
                              echo "<td class='text-danger'>Inactivo</td>";
                            }
                            echo "<td><button class='btn btn-info' data-toggle='modal' data-target='#editar_permiso", $reg->id, "'><i class='fa fa-edit'></i></button></td>";
                            echo "</tr>";
                          ?>
                            <div class="modal fade" id="editar_permiso<?php echo $reg->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                              <div class="modal-dialog modal-mb">
                                <div class="modal-content">

                                  <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel2">Editar Permiso</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form action="" method="POST">
                                      <div>
                                        <input readonly type="text" name="edit_id" class="form-control" placeholder="ID" value="<?php echo $reg->id; ?>" required="" />
                                      </div>
                                      <br>
                                      <label for="">Estado:</label>
                                      <div class="radio">
                                        <label>
                                          <input type="radio" <?php if ($estado == "s") echo "checked"; ?> value="s" id="optionsRadios1" name="edit_estado"> Activo
                                        </label>
                                      </div>
                                      <div class="radio">
                                        <label>
                                          <input type="radio" <?php if ($estado == "n") echo "checked"; ?> value="n" id="optionsRadios2" name="edit_estado"> Inactivo
                                        </label>
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
    <!-- /page content -->

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