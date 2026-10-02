function pago_efectivo() {

    var tipo_pago = document.getElementById("tipo_pago").value;

    // Obtener el valor original
    let valor_total = document.getElementById("valor_total_a_pagar").value;

    console.log('valor_total_a_pagar:', valor_total);

    // Convertir a número
    let total_venta = Number(valor_total) || 0;

    // Redondear a pesos enteros
    total_venta = Math.round(total_venta);

    // Formatear pesos colombianos SIN decimales
    let total_formateado = total_venta.toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    console.log('total redondeado:', total_venta);
    console.log('total formateado:', total_formateado);


    if (tipo_pago == 1) {

        $('#efectivo').val(total_formateado);

        $('#pago').html(
            'Valor pago: ' + total_formateado
        );

        $('#faltante').html(
            'Faltante: 0'
        );

        $('#cambio').html(
            'Cambio: 0'
        );

        $('#transaccion').val(0);
    }


    if (tipo_pago == 0) {

        let total = total_venta;

        let total_formateado_0 = total.toLocaleString('es-CO', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        $('#efectivo').val(total_formateado_0);

        $('#pago').html(
            'Valor pago: ' + total_formateado_0
        );

        $('#faltante').html(
            'Faltante: 0'
        );

        $('#cambio').html(
            'Cambio: 0'
        );

        $('#transaccion').val(0);
    }
}