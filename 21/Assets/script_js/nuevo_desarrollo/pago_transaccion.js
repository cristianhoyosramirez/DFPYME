function pago_transaccion() {
    var tipo_pago = document.getElementById("tipo_pago").value;
    let total_venta = parseFloat(document.getElementById("valor_total_a_pagar").value);
    /*  let propina_parcial = document.getElementById("total_propina").value;
     // Reemplaza todos los puntos en la propina
     let total_propina = propina_parcial.replace(/\./g, ''); */

    let propina_parcial = document.getElementById("total_propina");

    let total_propina = 0;

    if (propina_parcial && propina_parcial.value) {

        total_propina = propina_parcial.value.replace(/\./g, '');

    }

    //console.log(tipo_pago)

    if (tipo_pago == 1) {
        $('#transaccion').val(total_venta.toLocaleString('es-CO'))
        $('#efectivo').val(0)
        $('#pago').html('Valor pago: ' + total_venta.toLocaleString('es-CO'))
        $('#faltante').html('Faltante: 0 ')
        $('#cambio').html('Cambio: 0')
    }


    if (tipo_pago == 0) {
        //let total = total_venta + parseFloat(total_propina);
        let total = total_venta;
        $('#transaccion').val(total.toLocaleString('es-CO'))
        $('#pago').html('Valor pago: ' + total.toLocaleString('es-CO'))
        $('#faltante').html('Faltante: 0')
        $('#cambio').html('Cambio: 0')
        $('#efectivo').val(0)
    }

}