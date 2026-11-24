  function confirmar_reserva(id_reserva, fecha, nombre, notas) {

        document.getElementById('id_reserva_edicion').value = id_reserva;
        document.getElementById('fecha_edicion').value = fecha;
        document.getElementById('nombre_habitacion_edicion').value = nombre;
        document.getElementById('observaciones_edicion').value = notas;
        // Abrir modal
        const modal = new bootstrap.Modal(document.getElementById('confirmar_reserva'));
        modal.show();

    }