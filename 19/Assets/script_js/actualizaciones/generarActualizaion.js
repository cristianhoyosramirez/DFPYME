 async function generarActulizacion(actualizacion) {



        try {
            // Mostrar SweetAlert con spinner
            Swal.fire({
                title: 'Aplicando cambios...',
                html: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const url = document.getElementById('url').value.trim();
            const response = await fetch(url + '/actualizacion/crearVersion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    actualizacion: actualizacion,
                })
            });

            const data = await response.json();

            if (data.response == "success") {
                // Cerrar spinner y mostrar éxito
                Swal.fire({
                    title: 'Cambios aplicados',
                    text: 'La versión se guardó correctamente',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Aquí puedes cerrar modal, limpiar formulario, etc.
            } else {
                // Error del servidor (respuesta no OK)
                Swal.fire('Error', data.mensaje || 'No se pudo crear la versión', 'error');
            }

        } catch (error) {
            console.error('Error en crearVersion:', error);
            Swal.fire('Error', 'Hubo un problema de red o del servidor', 'error');
        }

    }