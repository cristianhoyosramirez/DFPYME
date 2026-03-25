
    async function confirmarIngreso(id_habitacion) {

        try {
            const response = await fetch(`${BASE_URL}/habitaciones/consultarReserva`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_habitacion: id_habitacion
                })
            });

            const data = await response.json();

            if (data.response === 'ok') {

                /* const modal = new bootstrap.Modal(document.getElementById('modalConfirmarIngreso'));
                modal.show(); */
                document.getElementById('fecha_reserva').value = data.fecha
                document.getElementById('habitacion_reserva').value = data.habitacion
                document.getElementById('tipo_documento').innerHTML = data.documento
                document.getElementById('identificacion').value = data.numero_documento
                document.getElementById('nombres').value = data.nombres
                document.getElementById('notas_reserva').value = data.notas
                document.getElementById('valor_hospedaje').value = data.precio
                document.getElementById('id_habitacion_reserva').value = data.id_reserva


                let modal = new bootstrap.Modal(document.getElementById('modalConfirmarIngreso'));
                modal.show();
            } else {
                alert("No se encontró la reserva");
            }

        } catch (error) {
            console.error("Error:", error);
            alert("Error al consultar la reserva");
        }
    }
