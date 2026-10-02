<div class="alert alert-warning alert-dismissible fade show" 
     role="alert" 
     id="alertaCaja">

    <i class="fas fa-exclamation-triangle me-2"></i>
    No hay una apertura de caja activa para continuar.

    <button type="button" 
            class="btn-close" 
            data-bs-dismiss="alert" 
            aria-label="Close">
    </button>

</div>

<script>
    // Cerrar automáticamente después de 3 segundos
    setTimeout(() => {

        const alerta = document.getElementById('alertaCaja');

        if (alerta) {

            const bsAlert = bootstrap.Alert.getOrCreateInstance(alerta);

            bsAlert.close();
        }

    }, 3000);
</script>