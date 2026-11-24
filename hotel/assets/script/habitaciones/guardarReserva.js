async function guardarReserva() {
    try {
        const form = document.getElementById('formRegistroHuesped');


        const data = {
            id_habitacion: document.getElementById('id_de_habitacion').value,
            fecha: document.getElementById('fecha').value,
            observaciones: document.getElementById('observaciones').value
        };

        // Loader
        Swal.fire({
            title: 'Guardando reserva...',
            html: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const response = await fetch(`${BASE_URL}/habitaciones/reservar`, {
            method: 'POST',
            body: JSON.stringify(data)
        });

        // Validar respuesta HTTP
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const result = await response.json();

        Swal.close();

        if (result.success) {

            // Cerrar modal
            const modalEl = document.getElementById('modalReserva');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            // Esperar animación
            setTimeout(async () => {

                await Swal.fire({
                    icon: 'success',
                    title: 'Reserva guardada',
                    text: result.message || 'La reserva se registró correctamente'
                });

                // Resetear formulario
                form.reset();

                // Limpiar campos adicionales
                //document.getElementById('listaClientes').innerHTML = '';
                // document.getElementById('nombre_completo').value = '';
                //document.getElementById('id_cliente').value = '';
                document.getElementById('nombre_habitacion').value = '';
                document.getElementById('observaciones').value = '';

                // Actualizar tabla
                if (result.habitaciones) {
                    document.getElementById('tablaHabitaciones').innerHTML = result.habitaciones;
                }

            }, 300);

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message || 'No se pudo guardar la reserva'
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