function pago_transaccion() {

    var tipo_pago = document.getElementById("tipo_pago").value;

    // Obtener el valor total
    let valor_total = document.getElementById("valor_total_a_pagar").value;

    // Convertir a número
    let total_venta = Number(valor_total) || 0;

    // Redondear a pesos enteros, sin decimales
    total_venta = Math.round(total_venta);

    // Formatear en pesos colombianos sin decimales
    let total_formateado = total_venta.toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    console.log('Valor original:', valor_total);
    console.log('Total redondeado:', total_venta);
    console.log('Total formateado:', total_formateado);


    if (tipo_pago == 1) {

        $('#transaccion').val(total_formateado);

        $('#efectivo').val(0);

        $('#pago').html(
            'Valor pago: ' + total_formateado
        );

        $('#faltante').html(
            'Faltante: 0'
        );

        $('#cambio').html(
            'Cambio: 0'
        );
    }


    if (tipo_pago == 0) {

        let total = total_venta;

        let total_formateado_0 = total.toLocaleString('es-CO', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        $('#transaccion').val(total_formateado_0);

        $('#pago').html(
            'Valor pago: ' + total_formateado_0
        );

        $('#faltante').html(
            'Faltante: 0'
        );

        $('#cambio').html(
            'Cambio: 0'
        );

        $('#efectivo').val(0);
    }
}