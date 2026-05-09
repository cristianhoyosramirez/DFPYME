async function confirmar_reserva(id_reserva) {

  
  try {

    const response = await fetch(`${BASE_URL}/habitaciones/consultarReserva`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        id_reserva: id_reserva
      })
    });

    const data = await response.json();

    if (data.response === 'ok') {

      // IDs
      document.getElementById('id_reserva_edicion').value = data.id_reserva;
      document.getElementById('id_habitacion_reserva').value = data.id_reserva;

      // Datos reserva
      document.getElementById('fecha_reserva').value = data.fecha;
      document.getElementById('habitacion_reserva').value = data.habitacion;
      document.getElementById('valor_hospedaje').value = data.precio;
      document.getElementById('notas_reserva').value = data.notas;

      // Datos huésped
      //document.getElementById('tipo_documento').innerHTML = data.documento;
      // document.getElementById('identificacion').value = data.numero_documento;
      //document.getElementById('nombres').value = data.nombres;
      //document.getElementById('nombre_completo').value = data.nombres;

      // Abrir modal
      const modal = new bootstrap.Modal(
        document.getElementById('confirmar_reserva')
      );

      modal.show();

    } else {

      Swal.fire({
        icon: 'warning',
        title: 'Reserva no encontrada',
        text: 'No se encontró información de la reserva'
      });

    }

  } catch (error) {

    console.error("Error:", error);

    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error al consultar la reserva'
    });

  }
}