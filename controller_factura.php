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
} elseif ($peticion == "generarFactura") {
    $user_vendedor = $_POST["user_vendedor"];
    $doc_cliente = $_POST["doc_cliente"];
    $total = $_POST["total"];
    $cods_productos = array($_POST['cods_productos']);
    $precios_productos = array($_POST['precios_productos']);
    $cants_productos = array($_POST['cants_productos']);
    $subs_productos = array($_POST['subs_productos']);

    $sentencia = $conexion->prepare("INSERT INTO item_venta (fecha, user_vendedor, doc_cliente, total) VALUES(CURRENT_TIMESTAMP, ?, ?, ?);");
    $sentencia->execute([$user_vendedor, $doc_cliente, $total]);

    $num = count($cods_productos);

    for ($i = 0; $i < $num; ++$i) {
        $sentencia = $conexion->prepare("INSERT INTO item_detalle_venta (producto, cantidad, precio, subtotal) VALUES(?, ?, ?, ?);");
        $sentencia->execute([$cods_productos[$i], $cants_productos[$i], $precios_productos[$i], $subs_productos[$i]]);
    }

    $respuesta = array("res"=>"true");

    echo json_encode($respuesta);
} else {
    header("Location: index.php");
}
