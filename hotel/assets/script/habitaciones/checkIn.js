async function checkIn() {
    try {
        // Capturamos los datos del formulario
        const datos = {
            valor_hospedaje: document.getElementById('valor_hospedaje').value.replace(/,/g, ''),
            id_placa: document.getElementById('id_placa').value,
            id_procedencia: document.getElementById('id_procedencia').value,
            id_destino: document.getElementById('id_destino').value,
            notas: document.getElementById('notas_reserva').value,
            id_reserva: document.getElementById('id_habitacion_reserva').value
        };


        // Mostramos un spinner mientras se procesa (opcional)
        Swal.fire({
            title: 'Guardando reserva...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Enviamos la petición al servidor
        const response = await fetch(`${BASE_URL}/habitaciones/checkIn`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        });

        const data = await response.json();

        // Cerramos el spinner
        Swal.close();

        if (data.success) {
            // Cerramos el modal de Bootstrap si está abierto
            const modalEl = document.getElementById('modalReserva');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            // Esperamos un pequeño delay para que el modal cierre visualmente
            setTimeout(async () => {
                // Mostramos mensaje de éxito
                await Swal.fire({
                    icon: 'success',
                    title: 'Reserva guardada',
                    text: 'La reserva se registró correctamente',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });

                // Reseteamos el formulario
                const form = document.getElementById('formRegistroHuesped');
                if (form) form.reset();

                // Limpiamos campos adicionales
                document.getElementById('listaClientes').innerHTML = '';
                document.getElementById('nombre_completo').value = '';
                document.getElementById('id_cliente').value = '';
                document.getElementById('id_de_habitacion').value = '';
                document.getElementById('nombre_habitacion').value = '';
                document.getElementById('observaciones').value = '';

                const modalEl = document.getElementById('modalConfirmarIngreso');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }

                // Actualizamos tabla de habitaciones si llega información
                if (data.habitaciones) {
                    document.getElementById('tablaHabitaciones').innerHTML = data.habitaciones;
                }
            }, 300);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se pudo guardar la reserva'
            });
        }

    } catch (error) {
        console.error('Error al guardar la reserva:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al guardar la reserva'
        });
    }
}