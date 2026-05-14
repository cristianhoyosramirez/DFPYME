async function procesar_confirmacion() {
        try {
            // Obtener valores de forma segura
            const id_cliente = document.getElementById('id_cliente')?.value;
            const id_reserva = document.getElementById('id_reserva_edicion')?.value;
            const notas = document.getElementById('observaciones_edicion')?.value // Validaciones


            if (!id_cliente) {
                document.getElementById('faltaCliente').innerHTML = "Falta definir el cliente"
                return
            }

            // Loader
            Swal.fire({
                title: 'Procesando...',
                html: 'Confirmando reserva',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Envío al backend (JSON recomendado)
            const response = await fetch(`${BASE_URL}/reservas/confirmar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_cliente,
                    id_reserva,
                    notas
                })
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const result = await response.json();

            Swal.close();

            if (result.success == 'ok') {

                const fila = document.getElementById('reservasHabitaciones').innerHTML=result.reservas;

             

                await Swal.fire({
                    icon: 'success',
                    title: 'Reserva confirmada',
                    text: result.message || 'Se confirmó correctamente',
                    timer: 2000, // se cierra en 2 segundos
                    showConfirmButton: false
                });

                // Opcional: recargar o actualizar tabla
                // Cerrar modal correctamente
                const modalEl = document.getElementById('confirmar_reserva');

                let modal = bootstrap.Modal.getInstance(modalEl);

                // Si no existe instancia, la creamos
                if (!modal) {
                    modal = new bootstrap.Modal(modalEl);
                }

                modal.hide();

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'No se pudo confirmar'
                });
            }

        } catch (error) {
            console.error('Error:', error);

            Swal.fire({
                icon: 'error',
                title: 'Error inesperado',
                text: error.message || 'Ocurrió un problema'
            });
        }
    }