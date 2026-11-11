function eliminar_producto(event, id_tabla_producto, nombre) {

    let url = document.getElementById("url").value;
    let id_usuario = document.getElementById("id_usuario").value;
    let justificacionEliminarProducto = document.getElementById("justificacion_eliminar_producto").value;

    event.stopPropagation();

    Swal.fire({
        title: 'Está a punto de eliminar:',
        text: nombre,
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

        preConfirm: (justificacion) => {

            // Solo validar cuando la justificación es obligatoria
            if (justificacionEliminarProducto == 't') {
                if (!justificacion || justificacion.trim() === '') {
                    Swal.showValidationMessage('Debe ingresar una justificación');
                    return false;
                }
            }

            return justificacion;
        }

    }).then((result) => {

        if (result.isConfirmed) {

            let justificacion = result.value;

            $.ajax({
                type: 'post',
                url: url + "/pedidos/eliminar_producto",
                data: {
                    id_tabla_producto,
                    id_usuario,
                    justificacion
                },
                success: function(resultado) {

                    resultado = JSON.parse(resultado);

                    if (resultado.resultado == 1) {
                        sweet_alert('success', resultado.mensaje);
                        $("#mesa_productos").html(resultado.productos);
                        $("#valor_pedido").html(resultado.total_pedido);
                        $("#val_pedido").html(resultado.total_pedido);
                        $("#subtotal_pedido").val(resultado.sub_total);
                        $("#propina_del_pedido").val(resultado.propina);
                    }

                    if (resultado.resultado == 0) {
                        sweet_alert('error', 'El producto ya fue impreso en comanda, esta acción requiere permiso');
                    }

                }
            });
        }
    });
}