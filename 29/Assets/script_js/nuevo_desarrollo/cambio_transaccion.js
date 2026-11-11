
function cambio_transaccion(valor) { // Se recibe un valor desde el formulario de pagos
    var res = 0;
    let pago = 0;

    transaccionFormat = String(valor ?? '').replace(/[.]/g, '');

    let valorAsignado = transaccionFormat === '' ? 0 : parseInt(transaccionFormat, 10);

    // Valor de la venta
    let valor_venta = parseInt(
        String(document.getElementById("valor_total_a_pagar").value ?? '').replace(/[.,]/g, ''),
        10
    ) || 0;

    // Si el descuento está vacío, será cero
    let descuento_elemento = document.getElementById("valor_descuento");

    let valor_descuento = parseInt(
        String(descuento_elemento?.value ?? '').replace(/[.,]/g, ''),
        10
    ) || 0;

    // Valor de la transacción bancaria
    var transaccion = valorAsignado || 0;

    // Valor en efectivo
    let valor_efectivo = document.getElementById("efectivo").value;
    let efectivoFormat = String(valor_efectivo ?? '').replace(/[.]/g, '');
    let valor_e = efectivoFormat;

    let efectivo = valor_e === "" ? 0 : parseInt(valor_e, 10) || 0;

    // Total recibido
    let sub_total = efectivo + transaccion;

    // Total de la venta después del descuento
    let total_a_pagar = valor_venta - valor_descuento;

    console.log(total_a_pagar)

    // Calcular cambio
    res = sub_total - total_a_pagar;

    let resultado = res.toLocaleString('es-CO');

    if (res > 0) {
        $('#cambio').html('Cambio: $' + resultado);
    } else {
        $('#cambio').html('Cambio: $ 0');
    }

    // Mostrar valor total recibido
    $('#pago').html('Valor pago: $' + sub_total.toLocaleString('es-CO'));

    // Calcular y mostrar el faltante
    const faltante = total_a_pagar - sub_total;

    if (faltante > 0) {
        $('#faltante').html('Faltante: $' + faltante.toLocaleString('es-CO'));
    } else {
        $('#faltante').html('Faltante: $ 0');
    }
}
