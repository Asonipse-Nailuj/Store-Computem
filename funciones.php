<?php

function encrypt($string, $key)
{
	$result = '';
	$key = $key . '2013';
	for ($i = 0; $i < strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key)) - 1, 1);
		$char = chr(ord($char) + ord($keychar));
		$result .= $char;
	}
	return base64_encode($result);
}
#####CONTRASEÑA DE-ENCRIPTAR
function decrypt($string, $key)
{
	$result = '';
	$key = $key . '2013';
	$string = base64_decode($string);
	for ($i = 0; $i < strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key)) - 1, 1);
		$char = chr(ord($char) - ord($keychar));
		$result .= $char;
	}
	return $result;
}

function permisos_tmp($usuario, $tipo, $conexion)
{
	$registros = $conexion->query("SELECT id FROM permiso_tmp") or die($conexion->error);
	$tmp = $registros->fetchAll(PDO::FETCH_OBJ);

	if ($tipo == "admin") {
		foreach ($tmp as $row) {
			$permiso = $row->id;

			if ($permiso == "1" || $permiso == "7") {
				$sentencia = $conexion->prepare("INSERT INTO permiso (permiso, user, estado) VALUES(?, ?, ?);");
				$sentencia->execute([$permiso, $usuario, "n"]);
			} else {
				$sentencia = $conexion->prepare("INSERT INTO permiso (permiso, user, estado) VALUES(?, ?, ?);");
				$sentencia->execute([$permiso, $usuario, "s"]);
			}
		}
	} elseif ($tipo == "vendedor") {
		foreach ($tmp as $row) {
			$permiso = $row->id;

			if ($permiso == "1" || $permiso == "2" || $permiso == "3" || $permiso == "7") {
				$sentencia = $conexion->prepare("INSERT INTO permiso (permiso, user, estado) VALUES(?, ?, ?);");
				$sentencia->execute([$permiso, $usuario, "s"]);
			} else {
				$sentencia = $conexion->prepare("INSERT INTO permiso (permiso, user, estado) VALUES(?, ?, ?);");
				$sentencia->execute([$permiso, $usuario, "n"]);
			}
		}
	}
}

function permiso($user, $id, $conexion)
{
	$sentencia = $conexion->prepare("SELECT * FROM permiso WHERE user='$user' AND permiso=$id AND estado='s'") or die($conexion->error);
	$sentencia->execute();
	$num = $sentencia->rowCount();

	if ($num >= 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}
