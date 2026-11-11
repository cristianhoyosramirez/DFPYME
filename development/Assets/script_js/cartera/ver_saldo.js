async function ver_saldo(id_factura, id_estado) {
    let url = document.getElementById('url').value;
    try {
        //const response = await fetch("<?= base_url('consultas_y_reportes/saldo_factura') ?>", {
        const response = await fetch(url + "/consultas_y_reportes/saldo_factura", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id_factura: id_factura,
                id_estado: id_estado
            })
        });

        const data = await response.json();



        if (data.resultado == 1) {

            document.getElementById('numero_de_factura').innerHTML = data.documento
            document.getElementById('nombre_cliente').innerHTML = data.cliente
            document.getElementById('fecha_factura').innerHTML = data.fecha
            document.getElementById('total_factura').innerHTML = data.valor
            document.getElementById('abonado').innerHTML = data.tota_pagado
            document.getElementById('saldo_pendiente').innerHTML = data.saldo
            document.getElementById('valor_total_a_pagar').value = data.pendiente_de_pago
            document.getElementById('id_factura_a_pagar').value = data.id_factura
            document.getElementById('estado_factura_a_pagar').value = data.id_estado

            $("#finalizar_venta").modal("show");


        } else {
            alert("Error: " + data.message);
        }

    } catch (error) {
        console.error("Error en la petición:", error);
        alert("Ocurrió un error al consultar el saldo");
    }
}