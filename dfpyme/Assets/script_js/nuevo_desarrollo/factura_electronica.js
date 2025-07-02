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
    let url = new URL("http://localhost:5000/api/Invoice/id");
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


 function factura_electronica(
    id_mesa, estado, nit_cliente, id_usuario, url, pago_total, valor_venta,
    tipo_pago, efectivo, transaccion, id_usuario_2, propina_format, medio_de_pago,
    formaPago, fechaLimite, clasePago
) {
    $.ajax({
        url: url + '/administracion_impresora/estadoConsumo',
        method: 'GET',
        dataType: 'json',
        contentType: 'application/json',
    })
        .then(function (data) {
            let estadoLicencia = data.response;
            console.log("Estado de licencia:", estadoLicencia);

            if (estadoLicencia === "Suspendido" || estadoLicencia === "Bloqueado") {
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso Bloqueado',
                    text: 'La licencia está ' + estadoLicencia + '. Comuníquese con soporte.',
                });
                return;
            }

            if (estadoLicencia === "En Mora") {

                Swal.fire({
                    icon: 'warning',
                    title: 'Estado de trasmisión de documentos electrónicos',
                    text: estadoLicencia,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#2AA13D',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Validar si el pago es suficiente
                        if (pago_total >= parseInt(valor_venta)) {
                            // Ejecutar el AJAX para generar la prefactura
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
                                    clasePago
                                },
                                url: url + "/" + "factura_electronica/pre_factura",
                                type: "POST",
                                success: function (resultado) {
                                    var resultado = JSON.parse(resultado);
                                    if (resultado.resultado == 0) {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Error en la cantidad",
                                            confirmButtonText: "Aceptar",
                                            confirmButtonColor: "#2AA13D",
                                        });
                                    }
                                    if (resultado.resultado == 1) {
                                        let button = document.querySelector("#btn_pagar");
                                        button.disabled = false; // Habilitar el botón



                                        limpiar_todo();
                                        var mesas = document.getElementById("todas_las_mesas");
                                        if (mesas) {
                                            mesas.style.display = "block";
                                        }

                                        $('#finalizar_venta').modal('hide');

                                        $('#todas_las_mesas').html(resultado.mesas)
                                        $('#mesa_productos').html(resultado.productos)
                                        //$('#lista_categorias').html(resultado.categorias)
                                        if ($('#lista_categorias').length) {
                                            // Si existe, actualizar su contenido con resultado.categorias
                                            $('#lista_categorias').html(resultado.categorias);
                                        }
                                        $('#valor_pedido').html(resultado.valor_pedio)
                                        $('#subtotal_pedido').val(resultado.valor_pedio)

                                        $('#id_mesa_pedido').val(resultado.id_mesa)
                                        $('#pedido_mesa').html("Pedido: ")
                                        $('#mesa_pedido').html("Mesa: ")
                                        $('#nombre_mesero').html("Mesero: ")
                                        $('#documentos_factura').html(resultado.documentos)
                                        $('#tipo_pago').val(1)



                                        let lista_categorias = document.getElementById("lista_categorias");

                                        if (lista_categorias) {
                                            lista_categorias.style.display = "none";
                                        }

                                        Swal.fire({
                                            title: 'Información de pago',
                                            showDenyButton: true,
                                            confirmButtonText: 'Trasmitir', // Se intercambia con denyButtonText
                                            denyButtonText: 'Nueva factura', // Se intercambia con confirmButtonText
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
                                                '<hr class="custom-hr">' + // Línea de separación personalizada
                                                '<div class="row">' +
                                                '<div class="col-md-6 text-right-custom h1">Valor pagado :</div>' +
                                                '<div class="col-md-6 text-right-custom h1">' + resultado.valor_pago + '</div>' +
                                                '</div>' +
                                                '<hr class="custom-hr">' + // Línea de separación personalizada
                                                '<div class="row">' +
                                                '<div class="col-md-6 text-right-custom h1">Cambio : </div>' +
                                                '<div class="col-md-6 text-right-custom h1">' + resultado.cambio + '</div>' +
                                                '</div>' +
                                                '<hr class="custom-hr">' + // Línea de separación personalizada
                                                '</div>',
                                            confirmButtonColor: '#58C269', // Se intercambia con denyButtonColor
                                            denyButtonColor: '#6782EF', // Se intercambia con confirmButtonColor
                                        }).then((result) => {
                                           
                                            if (result.isConfirmed) {


                                                let id_factura = resultado.id_factura

                                                sendInvoice(id_factura);

                                            } else if (result.isDenied) {
                                                let id_factura = resultado.id_factura
                                                location.reload();
                                                $.ajax({
                                                    data: {
                                                        id_factura,
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
                                                                mesas.style.display = "block"
                                                            }


                                                            let lista_categorias = document.getElementById("lista_categorias");

                                                            if (lista_categorias) {
                                                                lista_categorias.style.display = "none";
                                                            }
                                                            
                                                            sweet_alert('success', 'Se ha finalizado la venta ');


                                                        }
                                                    },
                                                });
                                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                                // Acción para el botón "Imprimir prefactura"
                                                let id_factura = resultado.id_factura
                                                // Puedes agregar aquí la lógica que desees cuando se presiona este botón.
                                                var url = document.getElementById("url").value;


                                                $.ajax({
                                                    data: {
                                                        id_factura
                                                    },
                                                    url: url +
                                                        "/" +
                                                        "pedidos/impresion_factura_electronica",
                                                    type: "POST",
                                                    success: function (resultado) {
                                                        var resultado = JSON.parse(resultado);
                                                        if (resultado.resultado == 1) {


                                                            sweet_alert_start('success', 'Impresion correcta')

                                                        }
                                                    },
                                                });
                                            }
                                        })
                                    } else if (resultado.resultado == 2) {
                                        sweet_alert_centrado('warning', 'No es posible emitir facturacion electónica por falta de pago en folios Dian ')
                                    }
                                }
                            });
                        } else {
                            $('#valor_pago_error').html('¡ Pago insuficiente !');
                        }
                    }
                });




            }
            if (estadoLicencia === "Al día") {

                if (pago_total >= parseInt(valor_venta)) {
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
                            clasePago
                        },
                        url: url + "/" + "factura_electronica/pre_factura",
                        type: "POST",
                        success: function (resultado) {
                            var resultado = JSON.parse(resultado);
                            if (resultado.resultado == 0) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error en la cantidad",
                                    confirmButtonText: "Aceptar",
                                    confirmButtonColor: "#2AA13D",
                                });
                            }
                            if (resultado.resultado == 1) {
                                let button = document.querySelector("#btn_pagar");
                                button.disabled = false; // Habilitar el botón



                                limpiar_todo();
                                var mesas = document.getElementById("todas_las_mesas");
                                if (mesas) {
                                    mesas.style.display = "block";
                                }

                                $('#finalizar_venta').modal('hide');

                                $('#todas_las_mesas').html(resultado.mesas)
                                $('#mesa_productos').html(resultado.productos)
                                //$('#lista_categorias').html(resultado.categorias)
                                if ($('#lista_categorias').length) {
                                    // Si existe, actualizar su contenido con resultado.categorias
                                    $('#lista_categorias').html(resultado.categorias);
                                }
                                $('#valor_pedido').html(resultado.valor_pedio)
                                $('#subtotal_pedido').val(resultado.valor_pedio)

                                $('#id_mesa_pedido').val(resultado.id_mesa)
                                $('#pedido_mesa').html("Pedido: ")
                                $('#mesa_pedido').html("Mesa: ")
                                $('#nombre_mesero').html("Mesero: ")
                                $('#documentos_factura').html(resultado.documentos)
                                $('#tipo_pago').val(1)



                                let lista_categorias = document.getElementById("lista_categorias");

                                if (lista_categorias) {
                                    lista_categorias.style.display = "none";
                                }

                                Swal.fire({
                                    title: 'Información de pago',
                                    showDenyButton: true,
                                    confirmButtonText: 'Trasmitir', // Se intercambia con denyButtonText
                                    denyButtonText: 'Nueva factura', // Se intercambia con confirmButtonText
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
                                        '<hr class="custom-hr">' + // Línea de separación personalizada
                                        '<div class="row">' +
                                        '<div class="col-md-6 text-right-custom h1">Valor pagado :</div>' +
                                        '<div class="col-md-6 text-right-custom h1">' + resultado.valor_pago + '</div>' +
                                        '</div>' +
                                        '<hr class="custom-hr">' + // Línea de separación personalizada
                                        '<div class="row">' +
                                        '<div class="col-md-6 text-right-custom h1">Cambio : </div>' +
                                        '<div class="col-md-6 text-right-custom h1">' + resultado.cambio + '</div>' +
                                        '</div>' +
                                        '<hr class="custom-hr">' + // Línea de separación personalizada
                                        '</div>',
                                    confirmButtonColor: '#58C269', // Se intercambia con denyButtonColor
                                    denyButtonColor: '#6782EF', // Se intercambia con confirmButtonColor
                                }).then((result) => {

                                    if (result.isConfirmed) {


                                        let id_factura = resultado.id_factura

                                        sendInvoice(id_factura);

                                    } else if (result.isDenied) {
                                        let id_factura = resultado.id_factura
                                        location.reload();
                                        $.ajax({
                                            data: {
                                                id_factura,
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
                                                        mesas.style.display = "block"
                                                    }


                                                    let lista_categorias = document.getElementById("lista_categorias");

                                                    if (lista_categorias) {
                                                        lista_categorias.style.display = "none";
                                                    }


                                                    sweet_alert('success', 'Se ha finalizado la venta ');


                                                }
                                            },
                                        });
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        // Acción para el botón "Imprimir prefactura"
                                        let id_factura = resultado.id_factura
                                        // Puedes agregar aquí la lógica que desees cuando se presiona este botón.
                                        var url = document.getElementById("url").value;


                                        $.ajax({
                                            data: {
                                                id_factura
                                            },
                                            url: url +
                                                "/" +
                                                "pedidos/impresion_factura_electronica",
                                            type: "POST",
                                            success: function (resultado) {
                                                var resultado = JSON.parse(resultado);
                                                if (resultado.resultado == 1) {


                                                    sweet_alert_start('success', 'Impresion correcta')

                                                }
                                            },
                                        });
                                    }
                                })
                            } else if (resultado.resultado == 2) {
                                sweet_alert_centrado('warning', 'No es posible emitir facturacion electónica por falta de pago en folios Dian ')
                            }
                        },
                    });
                } else if (pago_total < parseInt(valor_venta)) {
                    $('#valor_pago_error').html('¡ Pago insuficiente !')
                }
            }

            // Continuar con la lógica de facturación si está Activa o En Mora
            console.log("Continuando con la facturación...");
            // Aquí iría toda tu lógica para facturar, por ejemplo:
            // enviarFactura(id_mesa, estado, nit_cliente, etc...)

        })
        .catch(function (jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición:", textStatus, errorThrown);
            Swal.fire("Ocurrió un error en la validación.");
        });
} 

/* function factura_electronica(
    id_mesa, estado, nit_cliente, id_usuario, url, pago_total, valor_venta,
    tipo_pago, efectivo, transaccion, id_usuario_2, propina_format, medio_de_pago,
    formaPago, fechaLimite, clasePago
) {


    const mostrarError = (icon, title, text) => {
        Swal.fire({ icon, title, text });
    };

    const mostrarAdvertencia = (text, callback) => {
        Swal.fire({
            icon: 'warning',
            title: 'Licencia en mora',
            text,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#2AA13D',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(callback);
    };

    const mostrarModalPago = (resultado) => {
        Swal.fire({
            title: 'Información de pago',
            showDenyButton: true,
            confirmButtonText: 'Trasmitir',
            denyButtonText: 'Nueva factura',
            showCancelButton: true,
            cancelButtonText: 'Imprimir prefactura',
            allowOutsideClick: false,
            allowEscapeKey: false,
            html: `
                <hr>
                ${resultado.mensaje}
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 text-right-custom h1">Total :</div>
                        <div class="col-md-6 text-right-custom h1">${resultado.total}</div>
                    </div>
                    <hr class="custom-hr">
                    <div class="row">
                        <div class="col-md-6 text-right-custom h1">Valor pagado :</div>
                        <div class="col-md-6 text-right-custom h1">${resultado.valor_pago}</div>
                    </div>
                    <hr class="custom-hr">
                    <div class="row">
                        <div class="col-md-6 text-right-custom h1">Cambio :</div>
                        <div class="col-md-6 text-right-custom h1">${resultado.cambio}</div>
                    </div>
                    <hr class="custom-hr">
                </div>`,
            confirmButtonColor: '#58C269',
            denyButtonColor: '#6782EF'
        }).then((result) => {
            let id_factura = resultado.id_factura;
            if (result.isConfirmed) {
                sendInvoice(id_factura);
            } else if (result.isDenied) {
                location.reload();
                $.get(url + "/factura_pos/modulo_facturacion", { id_factura }, (res) => {
                    let data = JSON.parse(res);
                    if (data.resultado == 1) {
                        document.querySelector("#btn_pagar").disabled = false;
                        document.getElementById("todas_las_mesas")?.style?.display = "block";
                        document.getElementById("lista_categorias")?.style?.display = "none";
                        sweet_alert('success', 'Se ha finalizado la venta');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                $.post(url + "/pedidos/impresion_factura_electronica", { id_factura }, (res) => {
                    let data = JSON.parse(res);
                    if (data.resultado == 1) {
                        sweet_alert_start('success', 'Impresión correcta');
                    }
                });
            }
        });
    };

    const procesarFactura = () => {
        $.post(url + "/factura_electronica/pre_factura", {
            id_usuario, nit_cliente, id_mesa, tipo_pago, valor_venta, efectivo,
            transaccion, estado, pago_total, propina_format, medio_de_pago,
            formaPago, fechaLimite, clasePago
        }, function (response) {
            let resultado = JSON.parse(response);
            if (resultado.resultado == 0) {
                mostrarError("error", "Error en la cantidad", "");
                return;
            }
            if (resultado.resultado == 1) {
                document.querySelector("#btn_pagar").disabled = false;
                limpiar_todo();

                $("#finalizar_venta").modal('hide');
                $("#todas_las_mesas").html(resultado.mesas).show();
                $("#mesa_productos").html(resultado.productos);
                $("#lista_categorias").html(resultado.categorias || "").hide();
                $("#valor_pedido").html(resultado.valor_pedio);
                $("#subtotal_pedido").val(resultado.valor_pedio);
                $("#id_mesa_pedido").val(resultado.id_mesa);
                $("#pedido_mesa, #mesa_pedido, #nombre_mesero").html("");
                $("#documentos_factura").html(resultado.documentos);
                $("#tipo_pago").val(1);

                mostrarModalPago(resultado);
            } else if (resultado.resultado == 2) {
                sweet_alert_centrado('warning', 'No es posible emitir facturación electrónica por falta de pago en folios DIAN');
            }
        });
    };

    $.ajax({
        url: `${url}/administracion_impresora/estadoConsumo`,
        method: 'GET',
        dataType: 'json'
    }).then(({ response: estadoLicencia }) => {
        if (["Suspendido", "Bloqueado"].includes(estadoLicencia)) {
            mostrarError('error', 'Acceso Bloqueado', `La licencia está ${estadoLicencia}. Comuníquese con soporte.`);
            return;
        }

        if (estadoLicencia === "En Mora") {
            mostrarAdvertencia('Su licencia está en mora. Puede continuar, pero contacte soporte.', (result) => {
                if (result.isConfirmed) {
                    pago_total >= parseInt(valor_venta) ? procesarFactura() : $('#valor_pago_error').html('¡ Pago insuficiente !');
                }
            });
        } else if (estadoLicencia === "Al día") {
            pago_total >= parseInt(valor_venta) ? procesarFactura() : $('#valor_pago_error').html('¡ Pago insuficiente !');
        }
    }).catch((jqXHR, textStatus, errorThrown) => {
        console.error("Error en la petición:", textStatus, errorThrown);
        Swal.fire("Ocurrió un error en la validación.");
    });
} */






