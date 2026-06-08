function habilitarBotonPago() {

    let select = document.getElementById("documento").value;
    let nombre_cliente = document.getElementById("nombre_cliente").value;
    let nit_cliente = document.getElementById("nit_cliente").value;

    let formaPago = document.getElementById("formaPago");

    if (select == 6) {

        // Asignar valor al select
        formaPago.value = 1;

        // Deshabilitar
        formaPago.disabled = true;

    } else {

        // Habilitar nuevamente
        formaPago.disabled = false;
    }

    if (select == 2) {
        sweet_alert('warning', 'Venta crédito');
        $('#efectivo').val(0);
        $('#transaccion').val(0);
    }

}