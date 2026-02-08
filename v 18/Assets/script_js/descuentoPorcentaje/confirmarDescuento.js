function confirmarDescuento() {

    var url = document.getElementById("url").value;
    var id_producto_pedido = document.getElementById("id_producto_pedido").value;
    var nuevoPrecio = document.getElementById("precio_producto").value;
    var porcentaje = document.getElementById("discountPercent").value;


    $.ajax({
        data: {
            nuevoPrecio,
            id_producto_pedido,
            porcentaje
        },
        url: url +
            "/" +
            "eventos/actualizar_producto_porcentaje",
        type: "post",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

                $('#lista_completa_mesas').html(resultado.mesas)
                //$('#precio_producto').val(resultado.total)
                //$('#precio_producto').inn(resultado.valorUnitario)

                document.getElementById('descuento_porcentaje').style.display = 'none';
                $('#agregar_nota').modal('hide');
                document.getElementById('val_uni' + resultado.id).innerHTML = resultado.valorUnitario
                document.getElementById('discountPercent').value = ""
                document.getElementById('total_producto' + resultado.id).innerHTML = resultado.total
                document.getElementById('valor_pedido').innerHTML = resultado.total_pedido
                document.getElementById('subtotal_pedido').value = resultado.sub_total
                sweet_alert_centrado('success', 'Se ha aplicado un descuento del ' + resultado.porcentaje + 'al producto ' + resultado.nombreProducto)



            }
        },
    });



}