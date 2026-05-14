async function buscarHabitacionFecha() {

    try {

        // Capturar fechas
        const fecha_inicio = document.getElementById('fechaInicial').value;
        const fecha_fin = document.getElementById('fechaFinal').value;

        // Validación básica
        if (!fecha_inicio || !fecha_fin) {

            Swal.fire({
                icon: 'warning',
                text: 'Debe seleccionar las fechas'
            });

            return;
        }

        // Spinner SweetAlert
        Swal.fire({
            title: 'Buscando reservas',
            text: 'Espere un momento...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const response = await fetch(`${BASE_URL}/reservas/buscarHabitaiconesFecha`, {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json'
            },

            body: JSON.stringify({
                fecha_inicial: fecha_inicio,
                fecha_final: fecha_fin
            })

        });

        const data = await response.json();

        document.getElementById('reservasHabitaciones').innerHTML = data.reservas;

        // Cerrar spinner
        Swal.close();

    } catch (error) {

        console.error('Error en la búsqueda:', error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al buscar'
        });

    }

}