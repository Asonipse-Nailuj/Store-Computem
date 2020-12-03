<?php
include_once "conexion.php";

$peticion = $_POST["peticion"];

if ($peticion == "cliente") {

    $consulta = $conexion->prepare("SELECT nombre, apellido FROM cliente WHERE documento = :doc") or die($conexion->error);
    $consulta->bindParam(":doc", $_POST["doc_cliente"]);
    $consulta->execute();

    $num = $consulta->rowCount();
    if ($num >= 1) {
        while ($fila = $consulta->fetch()) {
            $cliente[] = $fila;
        }
    } else {
        $cliente = "";
    }

    echo json_encode($cliente);
} elseif ($peticion == "producto") {
    $consulta = $conexion->prepare("SELECT * FROM inventario WHERE id = :cod") or die($conexion->error);
    $consulta->bindParam(":cod", $_POST["cod_producto"]);
    $consulta->execute();

    $num = $consulta->rowCount();
    if ($num >= 1) {
        while ($fila = $consulta->fetch()) {
            $producto[] = $fila;
        }
    } else {
        $producto = "";
    }

    echo json_encode($producto);
} elseif ($peticion == "cargarTmp") {
    $user = $_POST['user'];

    $consulta = $conexion->prepare("SELECT * FROM detalle_tmp WHERE user = :user") or die($conexion->error);
    $consulta->bindParam(":user", $user);
    $consulta->execute();

    $num = $consulta->rowCount();
    if ($num >= 1) {
        while ($fila = $consulta->fetch()) {
            $detalles[] = $fila;
        }
    } else {
        $detalles = "";
    }

    echo json_encode($detalles);
} elseif ($peticion == "quitarTmp") {
    $producto = $_POST['producto'];
    $user = $_POST['user'];

    $consulta = $conexion->prepare("DELETE FROM detalle_tmp WHERE producto = ? AND user = ?;");
    $consulta->execute([$producto, $user]);

    $respuesta = array("res" => "true");

    echo json_encode($respuesta);
} elseif ($peticion == "agregarTmp") {
    $producto = $_POST['producto'];
    $user = $_POST['user'];
    $nom = $_POST['nom'];
    $cant = $_POST['cant'];
    $precio = $_POST['precio'];
    $subtotal = $_POST['subtotal'];

    $consulta = $conexion->prepare("SELECT * FROM detalle_tmp WHERE producto = ?");
    $consulta->execute([$producto]);

    $num = $consulta->rowCount();

    if ($num >= 1) {
        $registro = $consulta->fetchAll(PDO::FETCH_OBJ);
        foreach ($registro as $row) {
            $cantidad = $row->cantidad;
        }

        $cant = $cant + $cantidad;

        $sentencia = $conexion->prepare("UPDATE detalle_tmp SET cantidad = ? WHERE producto = ?;");
        $sentencia->execute([$cant, $producto]);
    } else {
        $sentencia = $conexion->prepare("INSERT INTO detalle_tmp (producto, user, nombre, cantidad, precio, subtotal) VALUES(?, ?, ?, ?, ?, ?);");
        $sentencia->execute([$producto, $user, $nom, $cant, $precio, $subtotal]);
    }

    $respuesta = array("res" => "true");

    echo json_encode($respuesta);
} elseif ($peticion == "generarFactura") {
    $user_vendedor = $_POST["user_vendedor"];
    $doc_cliente = $_POST["doc_cliente"];
    $total = $_POST["total"];

    $sentencia = $conexion->prepare("INSERT INTO item_venta (fecha, user_vendedor, doc_cliente, total) VALUES(CURRENT_TIMESTAMP, ?, ?, ?);");
    $sentencia->execute([$user_vendedor, $doc_cliente, $total]);

    $respuesta = array("res" => "true");

    echo json_encode($respuesta);
} else {
    header("Location: index.php");
}
