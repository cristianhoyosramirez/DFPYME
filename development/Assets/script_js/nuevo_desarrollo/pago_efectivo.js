
function pago_efectivo() {

    var tipo_pago = document.getElementById("tipo_pago").value;

    // Obtener el descuento y quitar puntos y comas
    var valor_descuento = String(
        document.getElementById("valor_descuento").value ?? ''
    ).replace(/[.,]/g, '');

    valor_descuento = parseFloat(valor_descuento) || 0;

    // Obtener el valor original y quitar puntos y comas
    let valor_total = String(
        document.getElementById("valor_total_a_pagar").value ?? ''
    ).replace(/[.,]/g, '');

    console.log('valor_total_a_pagar:', valor_total);

    // Convertir a número y restar el descuento
    let total_venta = (parseFloat(valor_total) || 0) - valor_descuento;

    // Evitar valores negativos y redondear a pesos enteros
    total_venta = Math.max(0, Math.round(total_venta));

    // Formatear pesos colombianos sin decimales
    let total_formateado = total_venta.toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    console.log('total redondeado:', total_venta);
    console.log('total formateado:', total_formateado);

    if (tipo_pago == 1 || tipo_pago == 0) {

        $('#efectivo').val(total_formateado);

        $('#pago').html(
            'Valor pago: ' + total_formateado
        );

        $('#faltante').html('Faltante: 0');

        $('#cambio').html('Cambio: 0');

        $('#transaccion').val(0);
    }
}
