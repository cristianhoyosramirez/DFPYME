async function guardarReserva() {
    const form = document.getElementById('formRegistroHuesped');
    const formData = new FormData(form);

    try {
        // Mostrar SweetAlert con spinner mientras se procesa
        Swal.fire({
            title: 'Guardando reserva...',
            html: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading(); // ¡Muestra el spinner!
            }
        });

        const response = await fetch(`${BASE_URL}/habitaciones/reservar`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        Swal.close(); // Cierra el spinner

        if (data.success) {
            // Cerrar modal de Bootstrap si está abierto
            const modalEl = document.getElementById('modalReserva');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }

            // Esperar un momento para que el modal se cierre visualmente
            setTimeout(async () => {
                // Mostrar SweetAlert de éxito
                await Swal.fire({
                    icon: 'success',
                    title: 'Reserva guardada',
                    text: 'La reserva se registró correctamente',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });

                // Resetear formulario
                form.reset();
                document.getElementById('listaClientes').innerHTML = '';
                document.getElementById('nombre_completo').value = '';
                document.getElementById('id_cliente').value = '';
                document.getElementById('id_de_habitacion').value = '';
                document.getElementById('nombre_habitacion').value = '';
                document.getElementById('observaciones').value = '';

                // Actualizar tabla de habitaciones
                document.getElementById('tablaHabitaciones').innerHTML = data.habitaciones;

            }, 300); // 300ms suele ser suficiente para que el modal desaparezca
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