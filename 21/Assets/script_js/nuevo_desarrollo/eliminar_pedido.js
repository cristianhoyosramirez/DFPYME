function eliminar_pedido() {

    let url = document.getElementById("url").value;
    let id_mesa = document.getElementById("id_mesa_pedido").value;
    let id_usuario = document.getElementById("id_usuario").value;
    let justificacionEliminarPedido = document.getElementById("justificacion_eliminar_pedido").value;

    if (id_mesa == "") {
        sweet_alert('warning', 'No hay pedido');
        return;
    }

    Swal.fire({
        title: '¿Seguro de eliminar el pedido?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#2AA13D',
        cancelButtonColor: '#D63939',
        focusConfirm: false,
        html: `
            <label for="justificacion" class="form-label">
                Justificación ${justificacionEliminarPedido == 't' ? '<span style="color:red">*</span>' : '(opcional)'}
            </label>
            <textarea
                id="justificacion"
                class="swal2-textarea"
                placeholder="Escriba la justificación..."
            ></textarea>
        `,
        preConfirm: () => {

            const justificacion = Swal.getPopup()
                .querySelector('#justificacion')
                .value
                .trim();

            // Si la configuración indica que es obligatoria
            if (justificacionEliminarPedido == 't') {

                if (justificacion.length == 0) {
                    Swal.showValidationMessage('Debe ingresar una justificación para eliminar el pedido.');
                    return false;
                }

            }

            return {
                justificacion: justificacion
            };
        }

    }).then((result) => {

        if (!result.isConfirmed) {
            return;
        }

        $.ajax({

            type: 'POST',
            url: url + "/pedidos/eliminacion_de_pedido",

            data: {
                id_mesa: id_mesa,
                id_usuario: id_usuario,
                justificacion: result.value.justificacion
            },

            success: function (resultado) {

                resultado = JSON.parse(resultado);

                if (resultado.resultado == 1) {

                    limpiar_todo();

                    sweet_alert('success', 'Pedido eliminado');

                    $("#todas_las_mesas").html(resultado.mesas);
                    $("#val_pedido").html(0);

                    let mesas = document.getElementById("todas_las_mesas");

                    if (mesas) {
                        mesas.style.display = "block";
                    }

                    let lista_categorias = document.getElementById("lista_categorias");

                    if (lista_categorias) {
                        lista_categorias.style.display = "none";
                    }

                    header_pedido();

                } else if (resultado.resultado == 0) {

                    sweet_alert_start('error', 'Acción requiere permisos');

                } else if (resultado.resultado == 2) {

                    sweet_alert_centrado(
                        'info',
                        'Acción no se puede completar porque hay productos impresos en comanda'
                    );

                }

            },

            error: function () {
                sweet_alert('error', 'Ocurrió un error al eliminar el pedido.');
            }

        });

    });

}