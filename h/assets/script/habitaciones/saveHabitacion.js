async function saveHabitacion() {
    const formHabitacion = document.getElementById('formHabitacion');
    const btnGuardar = document.getElementById('btnGuardarHabitacion');

    // Crear objeto con los datos del formulario
    const formData = new FormData(formHabitacion);
    const data = {};
    formData.forEach((value, key) => data[key] = value.trim());

    try {
        // Deshabilitar botón mientras se procesa
        btnGuardar.disabled = true;

        // Mostrar loader
        Swal.fire({
            title: 'Creando habitación...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        // Llamada al backend
        //const response = await fetch('<?= base_url() ?>/habitaciones/crear', {
        const response = await fetch(`${BASE_URL}/habitaciones/crear`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        Swal.close();
        btnGuardar.disabled = false;

        if (result.success) {
            // Mostrar mensaje de éxito
            Swal.fire('¡Éxito!', 'Habitación creada correctamente', 'success');

            // Limpiar formulario
            formHabitacion.reset();

            // Cerrar el modal
            const modalEl = document.getElementById('modalHabitacion');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            // Actualizar el tbody con el HTML que viene del servidor
            const tbody = document.getElementById('tablaHabitaciones');
            tbody.innerHTML = result.habitaciones; // habitaciones es HTML desde el backend

        } else {
            Swal.fire('Error', result.message || 'Ocurrió un error al crear la habitación', 'error');
        }

    } catch (error) {
        Swal.close();
        btnGuardar.disabled = false;
        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
        console.error(error);
    }
}