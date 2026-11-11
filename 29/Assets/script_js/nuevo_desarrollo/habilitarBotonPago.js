function habilitarBotonPago() {

    let select = document.getElementById("documento").value;
    let nombre_cliente = document.getElementById("nombre_cliente").value;
    let nit_cliente = document.getElementById("nit_cliente").value;

    let formaPago = document.getElementById("formaPago");
    let efectivo = document.getElementById("efectivo");
    let transaccion = document.getElementById("transaccion");

    if (select == 6) {

        // Deshabilitar efectivo y transacción
        efectivo.disabled = true;
        transaccion.disabled = true;

        // Asignar Contado
        formaPago.value = 1;

        // Deshabilitar forma de pago
        formaPago.disabled = true;

        // Eliminar propina
        borrar_propina();

    } else {

        // Habilitar efectivo y transacción
        efectivo.disabled = false;
        transaccion.disabled = false;

        // Habilitar forma de pago
        formaPago.disabled = false;
    }

    if (select == 2) {

        sweet_alert('warning', 'Venta crédito');

        $('#efectivo').val(0);
        $('#transaccion').val(0);
    }
}