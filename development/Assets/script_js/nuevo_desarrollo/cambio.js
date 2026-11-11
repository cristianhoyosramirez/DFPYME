
function cambio(valor) {
    // Limpia el mensaje de error
    $('#valor_pago_error').html('');

    // Convierte el efectivo a número
    const efectivo = parseInt(String(valor ?? '').replace(/[.,]/g, ''), 10) || 0;

    // Obtiene el valor de la venta
    const valorVenta = parseInt(
        String(document.getElementById("valor_total_a_pagar").value ?? '').replace(/[.,]/g, ''),
        10
    ) || 0;

    // Obtiene el descuento; si está vacío, será cero
    const elementoDescuento = document.getElementById("valor_descuento");

    const valorDescuento = parseInt(
        String(elementoDescuento?.value ?? '').replace(/[.,]/g, ''),
        10
    ) || 0;

    // Obtiene el valor de la transacción bancaria
    const valorTransaccion = parseInt(
        String(document.getElementById("transaccion").value ?? '').replace(/[.,]/g, ''),
        10
    ) || 0;

    // Calcula el total después del descuento
    const totalAPagar = Math.max(0, valorVenta - valorDescuento);

    // Calcula el subtotal recibido y el cambio
    const subTotal = efectivo + valorTransaccion;
    const cambio = subTotal - totalAPagar;

    // Muestra el valor recibido
    $('#pago').html(`Valor pago: $${subTotal.toLocaleString('es-CO')}`);

    // Muestra el cambio
    if (cambio > 0) {
        $('#cambio').html(`Cambio: $${cambio.toLocaleString('es-CO')}`);
    } else {
        $('#cambio').html('Cambio: $0');
    }

    // Calcula y muestra el faltante
    const faltante = totalAPagar - subTotal;

    if (faltante > 0) {
        $('#faltante').html(`Faltante: $${faltante.toLocaleString('es-CO')}`);
    } else {
        $('#faltante').html('Faltante: $0');
    }
}
