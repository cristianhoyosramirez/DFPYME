
function actualizarVistaFactura(resultado) {
    let button = document.querySelector("#btn_pagar");
    button.disabled = false;

    limpiar_todo();

    var mesas = document.getElementById("todas_las_mesas");
    if (mesas) {
        mesas.style.display = "block";
    }

    $('#finalizar_venta').modal('hide');
    $('#todas_las_mesas').html(resultado.mesas);
    $('#mesa_productos').html(resultado.productos);

    if ($('#lista_categorias').length) {
        $('#lista_categorias').html(resultado.categorias);
    }

    $('#valor_pedido').html(resultado.valor_pedio);
    $('#subtotal_pedido').val(resultado.valor_pedio);
    $('#id_mesa_pedido').val(resultado.id_mesa);
    $('#pedido_mesa').html("Pedido: ");
    $('#mesa_pedido').html("Mesa: ");
    $('#nombre_mesero').html("Mesero: ");
    $('#documentos_factura').html(resultado.documentos);
    $('#tipo_pago').val(1);

    let lista_categorias = document.getElementById("lista_categorias");

    if (lista_categorias) {
        lista_categorias.style.display = "none";
    }
}


function mostrarInformacionPago(resultado, respuesta, url, estadoLicencia) {
    Swal.fire({
        title: 'Información de pago',
        showDenyButton: true,
        confirmButtonText: 'Trasmitir',
        denyButtonText: 'Nueva factura',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCancelButton: true,
        cancelButtonText: 'Imprimir prefactura',

        html: '<hr>' + resultado.mensaje +
            '<div class="container">' +
            '<div class="row">' +
            '<div class="col-md-6 text-right-custom h1">Total :</div>' +
            '<div class="col-md-6 text-right-custom h1">' + resultado.total + '</div>' +
            '</div>' +
            '<hr class="custom-hr">' +
            '<div class="row">' +
            '<div class="col-md-6 text-right-custom h1">Valor pagado :</div>' +
            '<div class="col-md-6 text-right-custom h1">' + resultado.valor_pago + '</div>' +
            '</div>' +
            '<hr class="custom-hr">' +
            '<div class="row">' +
            '<div class="col-md-6 text-right-custom h1">Cambio : </div>' +
            '<div class="col-md-6 text-right-custom h1">' + resultado.cambio + '</div>' +
            '</div>' +
            '<hr class="custom-hr">' +
            '</div>',

        confirmButtonColor: '#58C269',
        denyButtonColor: '#6782EF',

        didOpen: () => {
            if (respuesta === 'false') {
                Swal.getConfirmButton().disabled = true;
                Swal.getConfirmButton().style.opacity = '0.5';
                Swal.getConfirmButton().style.cursor = 'not-allowed';
            }
        }
    }).then((result) => {

        let id_factura = resultado.id_factura;

        if (result.isConfirmed) {
            sendInvoice(id_factura);

        } else if (result.isDenied) {

            // Comportamiento original para licencia vacía y En Mora.
            if (estadoLicencia === "" || estadoLicencia === "En Mora") {
                location.reload();

                $.ajax({
                    data: {
                        id_factura
                    },
                    url: url + "/" + "factura_pos/modulo_facturacion",
                    type: "get",
                    success: function (resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            let button = document.querySelector("#btn_pagar");
                            button.disabled = false;

                            let mesas = document.getElementById("todas_las_mesas");
                            if (mesas) {
                                mesas.style.display = "block";
                            }

                            let lista_categorias = document.getElementById("lista_categorias");

                            if (lista_categorias) {
                                lista_categorias.style.display = "none";
                            }

                            sweet_alert('success', 'Se ha finalizado la venta ');
                        }
                    }
                });

            } else if (estadoLicencia === "Al día") {

                // Se conserva el comportamiento especial original.
                if (resultado.tipo_pago == 0) {
                    $('#todas_las_mesas').html(resultado.mesas);
                    $('#id_mesa_pedido').val(resultado.id_mesa.fk_mesa);
                    $('#pedido_mesa').html("Pedido: " + resultado.pedido);
                    $('#mesa_pedido').html("Mesa: " + resultado.nombre_mesa);
                    $('#documentos_factura').html(resultado.documentos);
                    $('#tipo_pago').val(1);
                }
            }

        } else if (result.dismiss === Swal.DismissReason.cancel) {

            // Imprimir prefactura.
            var url = document.getElementById("url").value;

            $.ajax({
                data: {
                    id_factura
                },
                url: url + "/" + "pedidos/impresion_factura_electronica",
                type: "POST",
                success: function (resultado) {
                    var resultado = JSON.parse(resultado);

                    if (resultado.resultado == 1) {
                        sweet_alert_start('success', 'Impresion correcta');
                    }
                }
            });
        }
    });
}


function procesarResultadoPrefactura(resultado, respuesta, url, estadoLicencia) {
    var resultado = JSON.parse(resultado);

    if (resultado.resultado == 0) {
        Swal.fire({
            icon: "error",
            title: "El almacenamiento de la información fallo, comuniquese con el área de soporte técnico.    ",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D"
        });
    }

    if (resultado.resultado == 1) {
        actualizarVistaFactura(resultado);
        mostrarInformacionPago(resultado, respuesta, url, estadoLicencia);
    } else if (resultado.resultado == 2) {
        sweet_alert_centrado(
            'warning',
            'No es posible emitir facturacion electónica por falta de pago en folios Dian '
        );
    }
}



function enviarPrefactura(
    id_usuario, nit_cliente, id_mesa, tipo_pago, valor_venta,
    efectivo, transaccion, estado, pago_total, propina_format,
    medio_de_pago, formaPago, fechaLimite, clasePago,
    valor_descuento, concepto_descuento, url, respuesta, estadoLicencia
) {

    /*  console.table({
         id_usuario,
         nit_cliente,
         id_mesa,
         tipo_pago,
         valor_venta,
         efectivo,
         transaccion,
         estado,
         pago_total,
         propina_format,
         medio_de_pago,
         formaPago,
         fechaLimite,
         clasePago,
         valor_descuento,
         concepto_descuento,
         url,
         respuesta,
         estadoLicencia
     });
 
     return; */
    $.ajax({
        data: {
            id_usuario,
            nit_cliente,
            id_mesa,
            tipo_pago,
            valor_venta,
            efectivo,
            transaccion,
            estado,
            pago_total,
            propina_format,
            medio_de_pago,
            formaPago,
            fechaLimite,
            clasePago,
            valor_descuento,
            concepto_descuento
        },
        url: url + "/" + "factura_electronica/pre_factura",
        type: "POST",
        success: function (resultado) {
            procesarResultadoPrefactura(
                resultado,
                respuesta,
                url,
                estadoLicencia
            );
        }
    });
}


function factura_electronica(
    id_mesa, estado, nit_cliente, id_usuario, url, pago_total, valor_venta,
    tipo_pago, efectivo, transaccion, id_usuario_2, propina_format, medio_de_pago,
    formaPago, fechaLimite, clasePago, valor_descuento, concepto_descuento, respuesta
) {
    $.ajax({
        url: url + '/administracion_impresora/estadoConsumo',
        method: 'GET',
        dataType: 'json',
        contentType: 'application/json'
    }).then(function (data) {

        let estadoLicencia = data.response;
        console.log("Estado de licencia:", estadoLicencia);

        if (estadoLicencia === "") {
            if (pago_total >= parseInt(valor_venta)) {
                enviarPrefactura(
                    id_usuario, nit_cliente, id_mesa, tipo_pago, valor_venta,
                    efectivo, transaccion, estado, pago_total, propina_format,
                    medio_de_pago, formaPago, fechaLimite, clasePago,
                    valor_descuento, concepto_descuento, url, respuesta, estadoLicencia
                );
            } else if (pago_total < parseInt(valor_venta)) {
                $('#valor_pago_error').html('¡ Pago insuficiente !');
            }
        }

        if (estadoLicencia === "Suspendido" || estadoLicencia === "Bloqueado") {
            Swal.fire({
                icon: 'error',
                title: 'Estado de consumo: \nEstado: ' + estadoLicencia,
                text: data.mensaje
            });
            return;
        }

        if (estadoLicencia === "En Mora") {
            Swal.fire({
                icon: 'warning',
                title: 'Estado de consumo: \nEstado: ' + estadoLicencia,
                text: data.mensaje,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#2AA13D',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {

                if (result.isConfirmed) {
                    if (pago_total >= parseInt(valor_venta)) {
                        enviarPrefactura(
                            id_usuario, nit_cliente, id_mesa, tipo_pago, valor_venta,
                            efectivo, transaccion, estado, pago_total, propina_format,
                            medio_de_pago, formaPago, fechaLimite, clasePago,
                            valor_descuento, concepto_descuento, url, respuesta, estadoLicencia
                        );
                    } else {
                        $('#valor_pago_error').html('¡ Pago insuficiente !');
                    }
                }
            });
        }

        if (estadoLicencia === "Al día") {

            if (pago_total >= parseInt(valor_venta - valor_descuento)) {
                enviarPrefactura(
                    id_usuario, nit_cliente, id_mesa, tipo_pago, valor_venta,
                    efectivo, transaccion, estado, pago_total, propina_format,
                    medio_de_pago, formaPago, fechaLimite, clasePago,
                    valor_descuento, concepto_descuento, url, respuesta, estadoLicencia
                );
            } else if (pago_total < parseInt(valor_venta)) {
                $('#valor_pago_error').html('¡ Pago insuficiente 3!');
            }
        }

        console.log("Continuando con la facturación...");

    }).catch(function (jqXHR, textStatus, errorThrown) {
        console.error("Error en la petición:", textStatus, errorThrown);
        Swal.fire("Ocurrió un error en la validación.");
    });
}


let invoice = { id: 0, dian_status: "", order_reference: "" }
let erroresp = { errors: [] };
let Error = { error: "" };



async function sendInvoice(iddoc) {
    invoice.id = iddoc;
    $("#id_de_factura").val(iddoc);
    $("#barra_progreso").modal("show");
    $("#barra_de_progreso").show();
    $("#respuesta_de_dian").html('Esperando respuesta DIAN');
    $("#texto_dian").html('')
    $("#opciones_dian").hide();
    let ip = document.getElementById('ip').value;
    //let url = new URL("http://localhost:5000/api/Invoice/id");
    let url = new URL("http://" + ip + ":5000/api/Invoice/id");
    //let url = new URL("http://localhost:3000/api2");
    url.search = new URLSearchParams({ id: iddoc });
    const response = await fetch(url, { method: "GET" });
    const data = await response.json();
    //console.log(invoice.id)

    if (response.status === 200) {
        invoice = JSON.parse(JSON.stringify(data, null, 2));

        //alert('Fact No ' + invoice.id + ' ' + invoice.order_reference + ' ' + invoice.dian_status);
        //console.log('Fact No ' + invoice.order_reference + ' ' + invoice.dian_status);

        // $("#barra_progreso").modal("hide");
        //$("#id_factura").val(invoice.id);
        $("#id_factura").val(iddoc);
        $("#barra_de_progreso").hide();
        $("#opciones_dian").hide();
        $("#error_dian").hide();
        $("#respuesta_dian").show();
        $("#opciones_dian").show();
        $("#texto_dian").html(invoice.order_reference + ' ' + invoice.dian_status);


        /*  table = $('#consulta_ventas').DataTable();
         if (table) {
             table.draw();
         } */

    }
    else if (response.status === 400) {   // Advertencia
        erroresp = JSON.parse(JSON.stringify(data, null, 2));
        //console.log(erroresp.errors[0].error);
        //alert(erroresp.errors[0].error);
        $("#barra_de_progreso").hide();
        $("#respuesta_dian").show();
        $("#error_dian").show();
        $("#texto_dian").html(erroresp.errors[0].error);
        //$("#id_factura").val(invoice.id);
        $("#id_factura").val(iddoc);
    }
    else {
        Error = JSON.parse(JSON.stringify(data, null, 2));  //Error Api 
        //alert(Error.error);
        $("#barra_de_progreso").hide();
        $("#respuesta_dian").show();
        $("#error_dian").show();
        $("#texto_dian").html('Respuesta DIAN: ' + erroresp.errors[0].error);
        //$("#id_factura").val(invoice.id);
        $("#id_factura").val(iddoc);
    }

}
