<?php

$user_conexion = "user_computem";
$password_conexion = "FsNMNFTnhZyVOrlV";
$base_datos = "store_computem";

try {
  $conexion = new PDO("mysql:host=localhost;dbname=$base_datos", $user_conexion, $password_conexion);
  $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
  echo "Ocurrió un error con la base de datos: " . $e->getMessage();
}
