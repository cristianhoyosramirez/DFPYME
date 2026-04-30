function eliminar_pedido() {
    let url = document.getElementById("url").value;
    var id_mesa = document.getElementById("id_mesa_pedido").value;
    var id_usuario = document.getElementById("id_usuario").value;

    if (id_mesa == "") {
        sweet_alert('warning', 'No hay pedido ');
    } else {

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
                <label for="justificacion" class="form-label">Justificación (opcional):</label>
                <textarea id="justificacion" class="swal2-textarea" placeholder="Escriba la justificación..."></textarea>
            `,
            preConfirm: () => {
                const justificacion = Swal.getPopup().querySelector('#justificacion').value;
                return { justificacion: justificacion };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let justificacion = result.value.justificacion;

                $.ajax({
                    type: 'post',
                    url: url + "/pedidos/eliminacion_de_pedido",
                    data: {
                        id_mesa,
                        id_usuario,
                        justificacion // se envía también al controlador
                    },
                    success: function (resultado) {
                        var resultado = JSON.parse(resultado);
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
                        }
                        if (resultado.resultado == 0) {
                            sweet_alert_start('error', 'Acción requiere permisos');
                        } else if (resultado.resultado == 2) {
                            sweet_alert_centrado('info', 'Acción no se puede completar porque hay productos impresos en comanda');
                        }
                    },
                });
            }
        });
    }
}
