<?php
session_start();

if (!empty($_SESSION["user"])) {
  header("Location: index.php");
}

include_once("funciones.php");
include_once("conexion.php");
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

  <title>Store Computem | </title>

  <!-- Bootstrap -->
  <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="build/css/custom.min.css" rel="stylesheet">
</head>

<body class="login">
  <div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
          <form action="" method="POST">
            <h1>Iniciar Sesión</h1>

            <?php
            if (!empty($_POST["input_user"]) and !empty($_POST["input_pass"])) {
              $usuario = $_POST['input_user'];
              $password = $_POST['input_pass'];
              $password = encrypt($password, $usuario);

              $registros = $conexion->query("SELECT * FROM usuario WHERE user='$usuario' AND password='$password'") or die($conexion->error);
              $cuenta = $registros->fetchAll(PDO::FETCH_OBJ);

              $mensaje = "";

              foreach ($cuenta as $row) {
                $nombre = $row->nombre;
                //$nombre = explode(" ", $nombre);
                //$nombre = $nombre[0];

                $tipo = $row->tipo;

                if ($row->estado == "s") {

                  $_SESSION['user_name'] = $nombre;
                  $_SESSION['user_type'] = $tipo;
                  $_SESSION['user'] = $usuario;

                  $mensaje = '<div class="alert alert-success alert-dismissible " role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                              </button>
                              <strong>ACCESO CONCEDIDO!</strong><br>Bienvenido a Store Computem, ' . $nombre . '.
                            </div>';
                  $mensaje .= '<meta http-equiv="refresh" content="2;url=index.php">';
                  echo $mensaje;

                } else {
                  echo '<div class="alert alert-warning alert-dismissible " role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>USUARIO INACTIVO!</strong><br>El usuario actualmente se encuentra inactivado, contacta con un administrador para solucionar el problema.
                      </div>';

                  $inactivo = true;
                }
              }

              if (empty($mensaje) && empty($inactivo)) {
                echo '<div class="alert alert-danger alert-dismissible " role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>ACCESO NEGADO!</strong><br>No se encontro el usuario o la contraseña.
                      </div>';
              }
            } else if (isset($_POST["registrar"])) {
              if (!empty($_POST["insert_user"]) && !empty($_POST["insert_nombre"]) && !empty($_POST["insert_pass"]) && !empty($_POST["insert_email"])) {
                $usuario = $_POST["insert_user"];
                $nombre = $_POST["insert_nombre"];
                $password = $_POST["insert_pass"];
                $email = $_POST["insert_email"];
                $tipo = "vendedor";

                $password = encrypt($password, $usuario);

                $sentencia = $conexion->prepare("INSERT INTO usuario VALUES(?, ?, ?, ?, ?, ?);");
                $sentencia->execute([$usuario, $password, "s", $nombre, $email, $tipo]);

                permisos_tmp($usuario, $tipo, $conexion);

                echo '<div class="alert alert-success alert-dismissible " role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>REGISTRADO!</strong><br>El usuario ha sido registrado en el sistema.
                      </div>';
              }
            }

            if (empty($mensaje)) {
            ?>

              <div>
                <input type="text" name="input_user" class="form-control" placeholder="Nombre de Usuario" required="" />
              </div>
              <div>
                <input type="password" name="input_pass" class="form-control" placeholder="Contraseña" required="" />
              </div>
              <div>
                <button type="submit" class="btn btn-default submit">Entrar</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">No tienes una cuenta?
                  <a href="#signup" class="to_register"> Registrarse </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-laptop"></i> Store Computem</h1>
                  <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                </div>
              </div>
          </form>
        </section>
      </div>

      <div id="register" class="animate form registration_form">
        <section class="login_content">
          <form action="" method="POST">
            <h1>Registrarse</h1>
            <div>
              <input type="text" name="insert_nombre" class="form-control" placeholder="Nombre Completo" required="" />
            </div>
            <div>
              <input type="text" name="insert_user" class="form-control" placeholder="Nombre de Usuario" required="" />
            </div>
            <div>
              <input type="email" name="insert_email" class="form-control" placeholder="Email" required="" />
            </div>
            <div>
              <input type="password" name="insert_pass" class="form-control" placeholder="Contraseña" required="" />
            </div>
            <div>
              <button type="submit" class="btn btn-default submit" name="registrar">Registrar</button>
            </div>

            <div class="clearfix"></div>

            <div class="separator">
              <p class="change_link">Ya eres un miembro?
                <a href="#signin" class="to_register"> Iniciar Sesión </a>
              </p>

              <div class="clearfix"></div>
              <br />

              <div>
                <h1><i class="fa fa-laptop"></i> Store Computem</h1>
                <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
              </div>
            </div>
          </form>
        <?php } ?>
        </section>
      </div>
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
  <!-- PNotify -->
  <script src="vendors/pnotify/dist/pnotify.js"></script>
  <script src="vendors/pnotify/dist/pnotify.buttons.js"></script>
  <script src="vendors/pnotify/dist/pnotify.nonblock.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="build/js/custom.min.js"></script>
</body>

</html>