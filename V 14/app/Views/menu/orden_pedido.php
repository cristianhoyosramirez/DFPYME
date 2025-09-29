<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container my-4">
    <p class="text-center h3 text-primary mb-3">
        Configuración de impresión de orden de pedido
    </p>

    <div class="w-50 mx-auto">
        <label for="preguntar_impresora_prefactura" class="form-label">
            Modo de impresión:
        </label>
        <select name="preguntar_impresora_prefactura" id="preguntar_impresora_prefactura"
            class="form-select"
            onchange="guardarConfiguracion(this.value)">
            <option value="true" <?= (isset($config) && $config->preguntar_impresora_prefactura) ? 'selected' : '' ?>>
                Preguntar impresora
            </option>
            <option value="false" <?= (isset($config) && !$config->preguntar_impresora_prefactura) ? 'selected' : '' ?>>
                Imprimir directo
            </option>
        </select>
    </div>
</div>

<script>
    async function guardarConfiguracion(valor) {
        try {
            let response = await fetch("<?= base_url('edicion_eliminacion_factura_pedido/updateConfOrdenPedido') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
                },
                body: JSON.stringify({
                    preguntar_impresora_prefactura: valor
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