<!-- Modal -->
<div class="modal fade" id="modal_retiros" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Retiro de dinero </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="datos_retiro"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<script>
  async function eliminar(id_retiro) {
    const confirmacion = await Swal.fire({
      title: "¿Estás seguro?",
      text: "Este retiro se eliminará de forma permanente.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6"
    });

    if (confirmacion.isConfirmed) {
      // Muestra la barra de progreso
      let timerInterval;
      Swal.fire({
        title: 'Eliminando...',
        html: 'Por favor espera <b></b> segundos.',
        timer: 3000,
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading();
          const b = Swal.getHtmlContainer().querySelector('b');
          timerInterval = setInterval(() => {
            const timer = Swal.getTimerLeft();
            if (timer) b.textContent = Math.ceil(timer / 1000);
          }, 100);
        },
        willClose: () => {
          clearInterval(timerInterval);
        }
      });

      try {
        const response = await fetch("<?= base_url('reportes/eliminarRetiros'); ?>", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            id_retiro: id_retiro
          }),
        });

        const resultado = await response.json();

        if (resultado.response === 'true') {
          Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            text: resultado.mensaje || 'El retiro fue eliminado correctamente.',
            timer: 1500,
            showConfirmButton: false
          });
          // Recarga o actualiza tabla
          setTimeout(() => location.reload(), 1500);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: resultado.mensaje || 'No se pudo eliminar el retiro.'
          });
        }
      } catch (error) {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error del sistema',
          text: 'Ocurrió un problema al eliminar el retiro.'
        });
      }
    }
  }
</script>