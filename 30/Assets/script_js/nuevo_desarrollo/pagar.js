
function pagar() {


    const nit_cliente = document.getElementById("nit_cliente").value.trim();
    const forma_pago = parseInt(document.getElementById("formaPago").value);
    const valor_factura = parseFloat(document.getElementById("valor_total_a_pagar").value) || 0;


    if (forma_pago === 2 && nit_cliente === "222222222222") {

        Swal.fire({
            icon: 'warning',
            title: 'Cliente no válido',
            text: 'Debe seleccionar un cliente válido para realizar una venta a crédito',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });

        return false;
    }

    if (forma_pago === 2 && valor_factura <= 0) {

        Swal.fire({
            icon: 'warning',
            title: 'Valor inválido',
            text: 'El valor de la factura debe ser mayor a cero',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });

        return false;
    }


    // =========================================================
    // VALIDAR RESOLUCIÓN
    // =========================================================

    validarResolucion().then(data => {

        // -----------------------------------------------------
        // Resolución con alerta
        // -----------------------------------------------------

        if (data.response === 'alerta' || data.response === 'false') {

            Swal.fire({
                icon: 'warning',
                title: data.message,
                timer: 5000,
                showConfirmButton: false,
                timerProgressBar: true
            });

            procesarPagoVenta(data);
        }


        // -----------------------------------------------------
        // Resolución correcta
        // -----------------------------------------------------

        if (data.response === 'success') {
            procesarPagoVenta(data);
        }


        // -----------------------------------------------------
        // Error
        // -----------------------------------------------------

        if (data.response === 'error') {
            alert(data.message);
        }

    });
}


/* ============================================================
 * OBTENER DATOS DEL PAGO
 * ============================================================ */

function obtenerDatosPago() {

    const valor_efectivo = document.getElementById("efectivo").value;
    const efectivo_format = valor_efectivo.replace(/[.]/g, "");
    const efectivo = efectivo_format === "" ? 0 : parseInt(efectivo_format);
    const valor_transaccion = document.getElementById("transaccion").value;
    const transaccion_format = valor_transaccion.replace(/[.]/g, "");
    const transaccion = transaccion_format === "" ? 0 : parseInt(transaccion_format);
    const id_mesa = document.getElementById("id_mesa_pedido").value;
    const url = document.getElementById("url").value;
    const id_usuario = document.getElementById("id_usuario").value;
    const nit_cliente = document.getElementById("nit_cliente").value;
    const requiere_factura_electronica = document.getElementById("requiere_factura_electronica").value;
    const estado = document.getElementById("documento").value;
    const tipo_pago = document.getElementById("tipo_pago").value;
    const medio_de_pago = document.getElementById("medio_de_pago").value;
    const forma_pago = document.getElementById("formaPago").value;
    const fechaLimite = document.getElementById("fechaLimite").value;
    const clase_pago = document.getElementById("clase_pago").value;
    // const valor_descuento = document.getElementById("valor_descuento").value;
    const valor_descuento = document.getElementById("valor_descuento").value.replace(/[.,]/g, '') || '0';
    const concepto_descuento = document.getElementById("concepto_descuento").value;


    return {
        requiere_factura_electronica, estado, efectivo, transaccion, id_mesa, url, id_usuario, nit_cliente, tipo_pago, medio_de_pago, forma_pago, fechaLimite, clase_pago, valor_descuento,
        concepto_descuento, pago_total: efectivo + transaccion
    };
}


/* ============================================================
 * PREPARAR VALORES DE LA VENTA
 * ============================================================ */

function prepararValoresVenta(datos) {

    let propina = "";
    let valor_venta = "";


    // ---------------------------------------------------------
    // Validar banco / clase de pago
    // ---------------------------------------------------------

    if (datos.transaccion > 0 && !datos.clase_pago) {

        document.getElementById("errorClasePago").innerHTML = "Debe seleccionar un banco";

        return null;
    }

    // ---------------------------------------------------------
    // Pago completo
    // ---------------------------------------------------------

    if (datos.tipo_pago == 1) {

        propina = document.getElementById("total_propina").value;

        propina = propina.replace(/[\$,]/g, '');

        const val_venta = document.getElementById("valor_total_a_pagar").value;

        valor_venta = parseInt(val_venta);
    }


    // ---------------------------------------------------------
    // Pago parcial
    // ---------------------------------------------------------

    if (datos.tipo_pago == 0) {

        propina =
            document.getElementById("total_propina").value;

        const subtotal_venta =
            document.getElementById("valor_total_a_pagar").value;

        valor_venta = subtotal_venta;
    }


    const propina_Format =
        propina.replace(/[.]/g, "");


    return {
        valor_venta,
        propina_Format
    };
}


/* ============================================================
 * PROCESAR PAGO
 * ============================================================ */

function procesarPagoVenta(data) {

    const datos = obtenerDatosPago();

    const valores = prepararValoresVenta(datos);

    // Si la validación del banco falla, detener proceso.
    if (!valores) {
        return;
    }


    // ---------------------------------------------------------
    // Factura electrónica requerida
    // ---------------------------------------------------------

    if (datos.requiere_factura_electronica === "si") {

        procesarFacturaElectronica(
            datos,
            valores,
            data.response
        );

        return;
    }


    // ---------------------------------------------------------
    // No requiere factura electrónica
    // ---------------------------------------------------------

    if (datos.requiere_factura_electronica === "no") {

        procesarVentaSinFactura(
            datos,
            valores,
            data.response
        );
    }
}


/* ============================================================
 * FACTURA ELECTRÓNICA
 * ============================================================ */

function procesarFacturaElectronica(datos, valores, respuesta) {

    /*
     * Estados que corresponden a factura electrónica.
     *
     * Se conserva la validación original:
     * estado 8 u 11.
     */

    if (datos.estado == 8 || datos.estado == 11) {

        factura_electronica(
            datos.id_mesa,
            datos.estado,
            datos.nit_cliente,
            datos.id_usuario,
            datos.url,
            datos.pago_total,
            valores.valor_venta,
            datos.tipo_pago,
            datos.efectivo,
            datos.transaccion,
            datos.id_usuario,
            valores.propina_Format,
            datos.medio_de_pago,
            datos.forma_pago,
            datos.fechaLimite,
            datos.clase_pago,
            datos.valor_descuento,
            datos.concepto_descuento,
            respuesta
        );

        return;
    }


    /*
     * Corrección de la condición original:
     *
     * Original:
     * estado != 8 || estado != 11
     *
     * Esa expresión siempre resulta verdadera.
     */

    $('#error_documento').html(
        '! Para continuar por favor seleccione Factura electrónica !'
    );
}


/* ============================================================
 * VENTA SIN FACTURA ELECTRÓNICA
 * ============================================================ */

function procesarVentaSinFactura(datos, valores, respuesta) {

    // ---------------------------------------------------------
    // Estado 8
    // ---------------------------------------------------------

    if (datos.estado == 8) {

        if (datos.pago_total >= parseInt(valores.valor_venta - datos.valor_descuento)) {

            factura_electronica(
                datos.id_mesa,
                datos.estado,
                datos.nit_cliente,
                datos.id_usuario,
                datos.url,
                datos.pago_total,
                valores.valor_venta,
                datos.tipo_pago,
                datos.efectivo,
                datos.transaccion,
                datos.id_usuario,
                valores.propina_Format,
                datos.medio_de_pago,
                datos.forma_pago,
                datos.fechaLimite,
                datos.clase_pago,
                datos.valor_descuento,
                datos.concepto_descuento,
                respuesta
            );
        }



        if (datos.pago_total < parseInt(valores.valor_venta - datos.valor_descuento)) {

            $('#valor_pago_error').html(
                '¡ Pago insuficiente !'
            );
        }

        return;
    }


    // ---------------------------------------------------------
    // Estados normales
    // ---------------------------------------------------------

    if (
        datos.estado == 1 ||
        datos.estado == 7 ||
        datos.estado == 6
    ) {

        procesarCierreVenta(
            datos,
            valores,
            true
        );

        return;
    }


    // ---------------------------------------------------------
    // Estado 2
    // ---------------------------------------------------------

    if (datos.estado == 2) {

        procesarCierreVenta(
            datos,
            valores,
            false
        );
    }
}


/* ============================================================
 * CERRAR VENTA
 * ============================================================ */

function procesarCierreVenta(datos, valores, validarPago) {

    /*
     * Los estados 1, 6 y 7 validan que el dinero recibido
     * sea suficiente.
     *
     * El estado 2 conserva el comportamiento original:
     * realiza el cierre sin esta validación.
     */

    if (
        validarPago &&
        datos.pago_total < parseInt(valores.valor_venta - datos.valor_descuento)

    ) {

        $('#valor_pago_error').html(
            '¡ Pago insuficiente  !'
        );

        return;
    }


    // ---------------------------------------------------------
    // Estado normal: deshabilitar botón
    // ---------------------------------------------------------

    if (validarPago) {

        const button =
            document.querySelector("#btn_pagar");

        if (button) {
            button.disabled = true;
        }
    }


    // ---------------------------------------------------------
    // Datos para cerrar venta
    // ---------------------------------------------------------

    const datosVenta = {
        id_mesa: datos.id_mesa,
        efectivo: datos.efectivo,
        transaccion: datos.transaccion,
        estado: datos.estado,
        nit_cliente: datos.nit_cliente,
        valor_venta: valores.valor_venta,
        id_usuario: datos.id_usuario,
        propina_Format: valores.propina_Format,
        tipo_pago: datos.tipo_pago,
        clase_pago: datos.clase_pago,
        forma_pago: datos.forma_pago
    };


    $.ajax({

        data: datosVenta,

        url:
            datos.url +
            "/" +
            "pedidos/cerrar_venta",

        type: "POST",

        success: function (resultado) {

            resultado = JSON.parse(resultado);

            manejarResultadoCierre(
                resultado,
                datos,
                validarPago
            );
        }
    });
}


/* ============================================================
 * RESULTADO DEL CIERRE
 * ============================================================ */

function manejarResultadoCierre(
    resultado,
    datos,
    flujoNormal
) {

    // ---------------------------------------------------------
    // Venta procesada correctamente
    // ---------------------------------------------------------

    if (resultado.resultado == 1) {

        if (flujoNormal) {

            /*
             * En los estados 1, 6 y 7 se habilita nuevamente
             * el botón de pagar.
             */

            $("#btn_pagar").prop(
                "disabled",
                false
            );
        }


        actualizarInterfazVenta(
            resultado,
            flujoNormal
        );


        if (flujoNormal) {
            header_pedido();
        }


        mostrarResumenVenta(
            resultado,
            datos.url
        );

        return;
    }


    // ---------------------------------------------------------
    // Error
    // ---------------------------------------------------------

    if (resultado.resultado == 0) {

        $('#btn_pagar').prop(
            'disabled',
            false
        );

        Swal.fire({
            title: resultado.mensaje,
            icon: "warning",
            confirmButtonColor: "#ff0000",
            confirmButtonText: "Aceptar"
        });
    }
}


/* ============================================================
 * ACTUALIZAR INTERFAZ
 * ============================================================ */

function actualizarInterfazVenta(
    resultado,
    flujoNormal
) {

    // ---------------------------------------------------------
    // Elementos comunes
    // ---------------------------------------------------------

    $('#finalizar_venta').modal('hide');

    $('#todas_las_mesas').html(
        resultado.mesas
    );

    $('#lista_completa_mesas').html(
        resultado.mesas
    );

    $('#efectivo').val(0);

    $('#transaccion').val(0);

    $('#propina_pesos_final').val(0);

    $('#total_propina').val(0);

    $('#tipo_pago').val(1);

    $('#clase_pago')
        .val('')
        .trigger('change');


    // ---------------------------------------------------------
    // Estado normal
    // ---------------------------------------------------------

    if (flujoNormal) {

        $('#documentos_factura').html(
            resultado.documentos
        );
    }


    // ---------------------------------------------------------
    // Estado 2
    // ---------------------------------------------------------

    if (!flujoNormal) {

        /*
         * Se conserva el comportamiento original:
         * únicamente se reinicia formPago.
         */

        $('#formPago')
            .val('')
            .trigger('change');

        const formaPago =
            document.getElementById("formaPago");

        const efectivo =
            document.getElementById("efectivo");

        const transaccion =
            document.getElementById("transaccion");

        const forma_pago =
            document.getElementById("forma_pago");


        if (efectivo) {
            efectivo.disabled = false;
        }

        if (transaccion) {
            transaccion.disabled = false;
        }

        if (forma_pago) {
            forma_pago.disabled = false;
        }
    }


    // ---------------------------------------------------------
    // Pedido vacío
    // ---------------------------------------------------------

    if (resultado.valor_pedio == '$ 0') {

        $('#mesa_pedido').html('');

        $('#pedido_mesa').html(
            'Pedido'
        );
    }


    // ---------------------------------------------------------
    // Pago completo
    // ---------------------------------------------------------

    if (resultado.tipo_pago == 1) {

        limpiar_todo();
    }


    // ---------------------------------------------------------
    // Pago parcial
    // ---------------------------------------------------------

    if (resultado.tipo_pago == 0) {

        $('#mesa_productos').html(
            resultado.productos
        );

        $('#valor_pedido').html(
            resultado.valor_pedio
        );

        $('#subtotal_pedido').val(
            resultado.valor_pedio
        );

        $('#productos_categoria').html('');

        $('#pago').html(
            'Valor pago: 0'
        );

        $('#cambio').html(
            'Cambio: 0'
        );

        $('#propina_pesos').val(0);

        $('#propina_pesos_final').val(0);

        $('#total_propina').val(0);

        /*
         * El flujo normal vuelve a habilitar el botón.
         */

        if (flujoNormal) {

            $("#btn_pagar").prop(
                "disabled",
                false
            );
        }

        $('#clase_pago')
            .val('')
            .trigger('change');
    }


    // ---------------------------------------------------------
    // Mostrar mesas
    // ---------------------------------------------------------

    const mesas =
        document.getElementById(
            "todas_las_mesas"
        );

    if (mesas) {
        mesas.style.display = "block";
    }
}


/* ============================================================
 * RESUMEN DE LA VENTA
 * ============================================================ */

function mostrarResumenVenta(
    resultado,
    url
) {

    Swal.fire({

        title: 'Resumen',

        showDenyButton: true,

        confirmButtonText:
            'Imprimir factura',

        denyButtonText:
            'Facturar',

        allowOutsideClick: false,

        allowEscapeKey: false,

        html:
            '<hr>' +

            resultado.mensaje +

            '<div class="container">' +

            '<div class="row">' +

            '<div class="col-md-6 text-right-custom h1">' +
            'Total :' +
            '</div>' +

            '<div class="col-md-6 text-right-custom h1">' +
            resultado.total +
            '</div>' +

            '</div>' +

            '<hr class="custom-hr">' +

            '<div class="row">' +

            '<div class="col-md-6 text-right-custom h1">' +
            'Valor pagado :' +
            '</div>' +

            '<div class="col-md-6 text-right-custom h1">' +
            resultado.valor_pago +
            '</div>' +

            '</div>' +

            '<hr class="custom-hr">' +

            '<div class="row">' +

            '<div class="col-md-6 text-right-custom h1">' +
            'Cambio : ' +
            '</div>' +

            '<div class="col-md-6 text-right-custom h1">' +
            resultado.cambio +
            '</div>' +

            '</div>' +

            '<hr class="custom-hr">' +

            '</div>',

        confirmButtonColor:
            '#58C269',

        denyButtonColor:
            '#6782EF'

    }).then(function (resultSwal) {

        // -----------------------------------------------------
        // Imprimir factura
        // -----------------------------------------------------

        if (resultSwal.isConfirmed) {

            const categorias =
                document.getElementById(
                    "lista_categorias"
                );

            if (categorias) {
                categorias.style.display =
                    "block";
            }


            imprimirFactura(
                resultado.id_factura,
                url
            );

            return;
        }


        // -----------------------------------------------------
        // Facturar
        // -----------------------------------------------------

        if (resultSwal.isDenied) {

            finalizarFacturacion(
                resultado.id_factura,
                url
            );
        }
    });
}


/* ============================================================
 * IMPRIMIR FACTURA
 * ============================================================ */

function imprimirFactura(
    numero_de_factura,
    url
) {

    $.ajax({

        data: {
            numero_de_factura
        },

        url:
            url +
            "/" +
            "pedidos/imprimir_factura",

        type: "POST",

        success: function (resultado) {

            resultado =
                JSON.parse(resultado);


            if (resultado.resultado == 1) {

                // -------------------------------------------------
                // Mostrar mesas
                // -------------------------------------------------

                const mesas =
                    document.getElementById(
                        "todas_las_mesas"
                    );

                if (mesas) {

                    mesas.style.display =
                        "block";
                }


                // -------------------------------------------------
                // Ocultar categorías
                // -------------------------------------------------

                const lista_categorias =
                    document.getElementById(
                        "lista_categorias"
                    );

                if (lista_categorias) {

                    lista_categorias.style.display =
                        "none";
                }


                sweet_alert(
                    'success',
                    'Impresión de factura correcto'
                );
            }
        }
    });
}


/* ============================================================
 * FINALIZAR FACTURACIÓN
 * ============================================================ */

function finalizarFacturacion(
    id_factura,
    url
) {

    $.ajax({

        data: {
            id_factura
        },

        url:
            url +
            "/" +
            "factura_pos/modulo_facturacion",

        type: "get",

        success: function (resultado) {

            resultado =
                JSON.parse(resultado);


            if (resultado.resultado == 1) {

                // -------------------------------------------------
                // Mostrar mesas
                // -------------------------------------------------

                const mesas =
                    document.getElementById(
                        "todas_las_mesas"
                    );

                if (mesas) {

                    mesas.style.display =
                        "block";
                }


                // -------------------------------------------------
                // Ocultar categorías
                // -------------------------------------------------

                const lista_categorias =
                    document.getElementById(
                        "lista_categorias"
                    );

                if (lista_categorias) {

                    lista_categorias.style.display =
                        "none";
                }


                sweet_alert(
                    'success',
                    'Se ha finalizado la venta'
                );
            }
        }
    });
}