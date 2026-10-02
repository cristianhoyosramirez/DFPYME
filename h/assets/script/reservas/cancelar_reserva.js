async function cancelar_reserva(id_reserva) {

    const confirmar = await Swal.fire({
        title: '¿Cancelar reserva?',
        text: 'La reserva será cancelada',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Cerrar'
    });

    if (!confirmar.isConfirmed) {
        return;
    }

    Swal.fire({
        title: 'Procesando...',
        text: 'Cancelando reserva',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {

        const response = await fetch(`${BASE_URL}/reservas/cancelarReserva`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_reserva: id_reserva
            })
        });

        const result = await response.json();

        Swal.close();

        if (result.success === 'ok') {

            const fila = document.getElementById('reserva' + result.id_reserva);

            if (fila) {
                fila.remove();
            }

            Swal.fire({
                icon: 'success',
                title: 'Reserva cancelada',
                timer: 1500,
                showConfirmButton: false
            });

        } else {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message || 'Error al cancelar'
            });

        }

    } catch (error) {

        Swal.close();

        console.error('Error:', error);

        Swal.fire({
            icon: 'error',
            title: 'Error del sistema',
            text: 'Ocurrió un problema inesperado'
        });
    }
}