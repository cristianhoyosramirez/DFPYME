function eliminar_producto(event, id_tabla_producto,nombre) {
    let url = document.getElementById("url").value
    let id_usuario = document.getElementById("id_usuario").value
    event.stopPropagation();

    Swal.fire({
        title: 'Esta apunto de eliminar: ',
        text:  nombre,
        icon: 'warning',
        input: 'textarea',
        inputPlaceholder: 'Escriba la justificación...',
        inputAttributes: {
            'aria-label': 'Escriba la justificación'
        },
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#2AA13D',
        cancelButtonColor: '#D63939',
        focusConfirm: true,
      /*   preConfirm: (justificacion) => {
            if (!justificacion || justificacion.trim() === '') {
                Swal.showValidationMessage('Debe ingresar una justificación');
            }
            return justificacion;
        } */
    }).then((result) => {
        if (result.isConfirmed) {
            let justificacion = result.value; // el texto del textarea

            $.ajax({
                type: 'post',
                url: url + "/" + "pedidos/eliminar_producto",
                data: {
                    id_tabla_producto,
                    id_usuario,
                    justificacion
                },
                success: function (resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {
                        sweet_alert('success', resultado.mensaje)
                        $("#mesa_productos").html(resultado.productos);
                        $("#valor_pedido").html(resultado.total_pedido);
                        $("#val_pedido").html(resultado.total_pedido);
                        $("#subtotal_pedido").val(resultado.sub_total);
                        $("#propina_del_pedido").val(resultado.propina);
                    }

                    if (resultado.resultado == 0) {
                        sweet_alert('error', 'El producto ya fue impreso en comanda, esta acción requiere permiso')
                    }
                },
            });
        }
    })
}
