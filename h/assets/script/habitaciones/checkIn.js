async function checkIn() {

    try {

        // =========================
        // LIMPIAR ERRORES
        // =========================

        const errores = document.querySelectorAll('.text-danger');

        errores.forEach(error => {
            error.innerHTML = '';
        });

        // =========================
        // CAMPOS OBLIGATORIOS
        // =========================

        const camposObligatorios = [
            'valor_hospedaje',
            'id_placa',
            'id_procedencia',
            'id_destino',
            'id_habitacion_reserva',
            'id_cliente'
        ];

        // =========================
        // VALIDAR CAMPOS
        // =========================

        for (let campo of camposObligatorios) {

            let elemento = document.getElementById(campo);
            let valor = elemento ? elemento.value : '';

            // quitar estilos anteriores
            elemento.classList.remove('is-invalid');

            if (!valor || valor.trim() === '') {

                let errorElemento =
                    document.getElementById(campo + 'Error');

                if (errorElemento) {
                    errorElemento.innerHTML = 'Dato requerido';
                }

                elemento.classList.add('is-invalid');

                elemento.focus();

                return;
            }
        }

        // =========================
        // DATOS
        // =========================

        const datos = {

            valor_hospedaje:
                document.getElementById('valor_hospedaje')
                    .value.replace(/,/g, ''),

            id_placa:
                document.getElementById('id_placa').value,

            id_procedencia:
                document.getElementById('id_procedencia').value,

            id_destino:
                document.getElementById('id_destino').value,

            notas:
                document.getElementById('notas_reserva').value,

            id_reserva:
                document.getElementById('id_habitacion_reserva').value,

            id_cliente:
                document.getElementById('id_cliente').value,

            hora_salida:
                document.getElementById('hora_salida').value,

            telefono:
                document.getElementById('telefono_cliente').value

        };

        // =========================
        // LOADING
        // =========================

        Swal.fire({
            title: 'Procesando Check-In...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // =========================
        // PETICIÓN
        // =========================

        const response = await fetch(
            `${BASE_URL}/habitaciones/checkIn`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            }
        );

        const data = await response.json();

        Swal.close();

        // =========================
        // SUCCESS
        // =========================

        if (data.success==true) {

            // Actualizar badge estado
            let badge =
                document.getElementById(
                    'estado' + data.id_reserva
                );

            if (badge) {

                badge.classList.remove(
                    'bg-danger',
                    'bg-warning',
                    'bg-info',
                    'bg-primary',
                    'bg-success'
                );

                badge.classList.add('bg-success');

                badge.innerText = 'Check In';
            }

            // Cerrar modal
            const modalEl =
                document.getElementById(
                    'confirmar_reserva'
                );

            const modal =
                bootstrap.Modal.getInstance(modalEl);

            if (modal) {
                modal.hide();
            }

            // Mensaje éxito
            await Swal.fire({
                icon: 'success',
                title: 'Confirmación realizada',
                text: 'El huésped fue registrado correctamente',
                confirmButtonColor: '#3085d6'
            });

            // =========================
            // RESET FORMULARIO
            // =========================

            const form =
                document.getElementById(
                    'formRegistroHuesped'
                );

            if (form) {
                form.reset();
            }

            document.getElementById('reservasHabitaciones').innerHTML=data.reservas

            // =========================
            // LIMPIAR CAMPOS EXTRA
            // =========================

            document.getElementById('listaClientes')
                .innerHTML = '';

            document.getElementById('listaVehiculos')
                .innerHTML = '';

            document.getElementById('listaMunicipiosProcedencia')
                .innerHTML = '';

            document.getElementById('listaMunicipiosDestino')
                .innerHTML = '';

            document.getElementById('nombre_completo')
                .value = '';

            document.getElementById('id_cliente')
                .value = '';

            document.getElementById('id_placa')
                .value = '';

            document.getElementById('id_procedencia')
                .value = '';

            document.getElementById('id_destino')
                .value = '';

            document.getElementById('procedencia')
                .value = '';

            document.getElementById('destino')
                .value = '';

            document.getElementById('placavehiculo')
                .value = '';

                
            document.getElementById('hora_salida')
                .value = '';

         

            // =========================
            // RECARGAR HABITACIONES
            // =========================

            if (data.habitaciones) {

                document.getElementById(
                    'tablaHabitaciones'
                ).innerHTML = data.habitaciones;
            }

        } else {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text:
                    data.message ||
                    'No se pudo realizar el Check-In'
            });
        }

    } catch (error) {

        console.error(
            'Error al realizar el Check-In:',
            error
        );

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text:
                'Ocurrió un error al procesar el Check-In'
        });
    }
}