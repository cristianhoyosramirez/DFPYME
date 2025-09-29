<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container my-4">
    <p class="text-center h3 text-primary mb-3">
        Configuración de facturación
    </p>

    <div class="w-50 mx-auto">
        <label for="preguntar_impresora_prefactura" class="form-label">
            Permitir incluir productos en cero en la factura:
        <select id="facturar_cero"
            class="form-select"
            onchange="facturar_cero(this.value)">
            <option value="t" <?= ($configuracion == 't') ? 'selected' : '' ?>>
                Sí
            </option>
            <option value="f" <?= ($configuracion == 'f') ? 'selected' : '' ?>>
                No
            </option>
        </select>

    </div>
</div>

<script>
    async function facturar_cero(valor) {
        try {
            let response = await fetch("<?= base_url('edicion_eliminacion_factura_pedido/updateFacturarCero') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
                },
                body: JSON.stringify({
                    facturar_cero: valor
                })
            });

            let data = await response.json();

            if (data.response === "success") {
                sweet_alert_centrado('success', 'Configuracion guardada')
            } else {
                alert("Error al guardar la configuración ❌");
            }
        } catch (error) {
            console.error("Error en la petición:", error);
            alert("No se pudo guardar la configuración ❌");
        }
    }
</script>

<?= $this->endSection('content') ?>