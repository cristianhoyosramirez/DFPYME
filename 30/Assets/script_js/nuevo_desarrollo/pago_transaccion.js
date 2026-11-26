
function pago_transaccion() {

    var tipo_pago = document.getElementById("tipo_pago").value;

    // Obtener el valor total y quitar puntos y comas
    let valor_total = String(
        document.getElementById("valor_total_a_pagar").value ?? ''
    ).replace(/[.,]/g, '');

    valor_total = parseFloat(valor_total) || 0;

    // Obtener el descuento y quitar puntos y comas
    var valor_descuento = String(
        document.getElementById("valor_descuento").value ?? ''
    ).replace(/[.,]/g, '');

    valor_descuento = parseFloat(valor_descuento) || 0;

    // Calcular el total después del descuento
    let total_venta = Math.max(0, valor_total - valor_descuento);

    // Redondear a pesos enteros
    total_venta = Math.round(total_venta);

    // Formatear en pesos colombianos sin decimales
    let total_formateado = total_venta.toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });

    console.log('Valor original:', valor_total);
    console.log('Descuento:', valor_descuento);
    console.log('Total redondeado:', total_venta);
    console.log('Total formateado:', total_formateado);

    if (tipo_pago == 1 || tipo_pago == 0) {

        $('#transaccion').val(total_formateado);
        $('#efectivo').val(0);

        $('#pago').html('Valor pago: ' + total_formateado);
        $('#faltante').html('Faltante: 0');
        $('#cambio').html('Cambio: 0');
    }
}
