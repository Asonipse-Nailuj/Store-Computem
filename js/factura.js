$(document).ready(start);

function start() {
    $("#documento").keyup(buscarCliente);
    $("#cod_producto").keyup(buscarProducto);
    $("#agregar_producto").click(agregarProducto);
    $("#generar_factura").click(generarFactura);

    cargarTmps();
}

function cargarTmps() {
    var datos = {
        'user': $("#usuario").val(),
        'peticion': 'cargarTmp'
    };

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: datos,
        url: 'controller_factura.php',
        success: function (resultado) {
            if (resultado != "") {
                var lista = "";
                for (let i = 0; i < resultado.length; i++) {

                    lista += "<tr>";
                    lista += "<td class='td_id'>" + resultado[i].producto + "</td>";
                    lista += "<td class='td_nom'>" + resultado[i].nombre + "</td>";
                    lista += "<td class='td_precio'>" + resultado[i].precio + "</td>";
                    lista += "<td class='td_cant'>" + resultado[i].cantidad + "</td>";
                    lista += "<td class='td_subtotal'>" + resultado[i].subtotal + "</td>";
                    lista += "<td class='last'><button type='button' class='btn btn-danger quitar_producto'>Quitar</button></td>"
                    lista += "</tr>";

                    $("#lista_productos").html(lista);
                    $(".quitar_producto").click(quitarProducto);

                    calcularTotal();
                }
            }
        }
    });
}

function buscarCliente(e) {
    if (e.which == 13) {

        $("#documento").attr("readonly", "true");

    } else {

        var doc = $("#documento").val();

        var datos = {
            'doc_cliente': doc,
            'peticion': 'cliente'
        };

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: datos,
            url: 'controller_factura.php',
            success: function (resultado) {
                if (resultado != "") {
                    $("#nombre_cliente").val(resultado[0].nombre + " " + resultado[0].apellido);
                } else {
                    $("#nombre_cliente").val("");
                }
            }
        });
    }
}

function buscarProducto() {
    var cod = $("#cod_producto").val();

    var datos = {
        'cod_producto': cod,
        'peticion': 'producto'
    };

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: datos,
        url: 'controller_factura.php',
        success: function (resultado) {
            if (resultado != "") {
                $("#nombre_producto").text(resultado[0].nombre_producto);
                $("#precio_producto").text(resultado[0].valor_unitario);

                var cantidad = "<select id='cant_producto'>";
                for (let i = 1; i <= resultado[0].cantidad; i++) {
                    cantidad += "<option value='" + i + "'>" + i + "</option>";
                }
                cantidad += "</select>";
                $("#cantidad_producto").html(cantidad);
                $("#cant_producto").change(calcularSubtotal);
                $("#subtotal").text(resultado[0].valor_unitario);
            } else {
                $("#nombre_producto").text("");
                $("#precio_producto").text("");
                $("#cantidad_producto").html("");
                $("#subtotal").text("");
            }
        }
    });
}

function calcularSubtotal() {
    var subtotal = $("#precio_producto").text() * $("#cant_producto").val();
    $("#subtotal").text(subtotal + ".00");
}

function agregarProducto() {
    if ($("#nombre_producto").text() != "") {
        var cod = $("#cod_producto").val();
        var nom = $("#nombre_producto").text();
        var precio = $("#precio_producto").text();
        var cant = $("#cant_producto").val();
        var subtotal = $("#subtotal").text();

        var datos = {
            'producto': cod,
            'user': $("#usuario").val(),
            'nom': nom,
            'cant': cant,
            'precio': precio,
            'subtotal': subtotal,
            'peticion': 'agregarTmp'
        };

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: datos,
            url: 'controller_factura.php',
            success: function (resultado) {
                if (resultado.res == "true") {
                    cargarTmps();

                    $("#cod_producto").val("");
                    $("#nombre_producto").text("");
                    $("#precio_producto").text("");
                    $("#cantidad_producto").html("");
                    $("#subtotal").text("");
                }
            }
        });
    }
}

function calcularTotal() {
    var subtotales = $(".td_subtotal");
    var subtotal = 0;

    $.each(subtotales, function (i, valor) {
        subtotal += parseFloat(valor.innerHTML);
    });

    var iva = subtotal * 0.16;
    var total = subtotal + iva;

    $("#lista_subtotal").text(subtotal + ".00");
    $("#lista_iva").text(iva + ".00");
    $("#lista_total").text(total + ".00");
}

function quitarProducto() {
    var fila = $(this).parents("tr");
    var cod = $(this).parents("tr").find(".td_id").text();
    
    var datos = {
        'producto': cod,
        'user': $("#usuario").val(),
        'peticion': 'quitarTmp'
    };

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: datos,
        url: 'controller_factura.php',
        success: function (resultado) {
            if (resultado.res == "true") {
                fila.remove()
            }
        }
    });
}

function generarFactura() {
    if (parseFloat($("#lista_total").text()) > 0 && $("#documento").val() != "") {

        var vendedor = $("#usuario").val();
        var cliente = $("#documento").val();
        var total = $("#lista_total").text();

        var datos = {
            'user_vendedor': vendedor,
            'doc_cliente': cliente,
            'total': total,
            'peticion': 'generarFactura'
        };

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: datos,
            url: 'controller_factura.php',
            success: function (resultado) {
                if (resultado.res == "true") {
                    $(location).attr('href', "facturacion.php?res=true");
                } else {
                    $(location).attr('href', "facturacion.php?res=false");
                }
            }
        });
    } else {
        alert("Primero selecciona un cliente y agrega productos para generar la factura")
    }
}