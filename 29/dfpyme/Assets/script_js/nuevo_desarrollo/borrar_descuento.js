
async function borrar_descuento() {

    const id_mesa = document.getElementById("id_mesa_pedido").value;

    // Validar que exista un pedido.
    if (id_mesa === "") {
        sweet_alert('warning', 'No hay pedido');
        return;
    }

    try {

        // Mostrar indicador de carga.
        Swal.fire({
            title: 'Eliminando descuento',
            text: 'Por favor, espera...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // URL base de DFpyme.
        const url = document.getElementById('url').value;

        // Enviar solicitud al controlador.
        const respuesta = await fetch(url + '/actualizacion/borrar_descuento', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id_mesa: id_mesa
            })
        });

        if (!respuesta.ok) {
            throw new Error('Error HTTP: ' + respuesta.status);
        }

        const result = await respuesta.json();

        // Cerrar indicador de carga.
        Swal.close();

        // Validar respuesta del servidor.
        if (result.response) {

            document.getElementById('mesa_productos').innerHTML = result.productos;
            document.getElementById('valor_pedido').innerHTML = result.total;
            document.getElementById('subtotal_pedido').value = result.sub_total;
            document.getElementById('descuento_dinero').value = result.descuento;
            document.getElementById('descuento_por_porcentaje').value = result.descuento;

            mostrar_mensaje_descuento(
                result.message,
                'success'
            );

            return;
        }

        // El servidor respondió, pero no confirmó la operación.
        sweet_alert(
            'error',
            result.message || 'No fue posible eliminar el descuento.'
        );

    } catch (error) {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No fue posible eliminar el descuento.'
        });

        console.error('Error al eliminar descuento:', error);
    }
}
