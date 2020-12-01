$(document).ready(start);

function start() {
    $("#documento").keyup(buscarCliente);
    $("#cod_producto").keyup(buscarProducto);
    $("#agregar_producto").click(agregarProducto);
    $("#generar_factura").click(generarFactura);
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

        var datos = $("#lista_productos").html();

        datos += "<tr>";
        datos += "<td class='td_id'>" + cod + "</td>";
        datos += "<td class='td_nom'>" + nom + "</td>";
        datos += "<td class='td_precio'>" + precio + "</td>";
        datos += "<td class='td_cant'>" + cant + "</td>";
        datos += "<td class='td_subtotal'>" + subtotal + "</td>";
        datos += "</tr>";

        $("#lista_productos").html(datos);

        $("#cod_producto").val("");
        $("#nombre_producto").text("");
        $("#precio_producto").text("");
        $("#cantidad_producto").html("");
        $("#subtotal").text("");

        calcularTotal();
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

function generarFactura() {
    if (parseFloat($("#lista_total").text()) > 0) {
        var cods = [];
        $.each($(".td_id"), function (i, valor) {
            var item = valor.innerHTML;

            cods.push(item);
        });

        var pre = [];
        $.each($(".td_precio"), function (i, valor) {
            var item = parseFloat(valor.innerHTML);

            pre.push(item);
        });

        var cants = [];
        $.each($(".td_cant"), function (i, valor) {
            var item = valor.innerHTML;

            cants.push(item);
        });

        var subs = [];
        $.each($(".td_subtotal"), function (i, valor) {
            var item = parseFloat(valor.innerHTML);

            subs.push(item);
        });

        var vendedor = $("#usuario").val();
        var cliente = $("#documento").val();
        var total = $("#lista_total").text();

        var datos = {
            'user_vendedor': vendedor,
            'doc_cliente': cliente,
            'total': total,
            'cods_productos': cods,
            'precios_productos': pre,
            'cants_productos': cants,
            'subs_productos': subs,
            'peticion': 'generarFactura'
        };

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: datos,
            url: 'controller_factura.php',
            success: function (resultado) {
                if (resultado != "") {
                    $(location).attr('href',"facturacion.php?res=true");
                } else {
                    $(location).attr('href',"facturacion.php?res=false");
                }
            }
        });
    }
}