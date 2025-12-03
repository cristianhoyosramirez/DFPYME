<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<input type="text" id="url" value="<?= base_url() ?>" hidden>

<div class="container mt-5">
    <h3 class="text-center text-primary mb-4">
        Realizar cierre de caja con facturas pendiente por trasmitir a la DIAN 
    </h3>

    <div class="row justify-content-start">
        <div class="col-md-6 text-center">
            <label for="generar_fiscal" class="form-label fw-bold">
                Habilitar el cierre de caja aun cuando existan prefacturas pendientes de envío a la DIAN
            </label>

            <select id="generar_fiscal" class="form-select text-center" onchange="informe(this.value)">
                <option value="t" <?= ($informe === 't') ? 'selected' : '' ?>>✅ Sí</option>
                <option value="f" <?= ($informe === 'f') ? 'selected' : '' ?>>❌ No</option>
            </select>

        </div>
    </div>
</div>
<input type="text" id="url" value="<?php  echo base_url()?>" hidden>
<script>
    async function informe(valor) {
        try {
            let url = document.getElementById("url").value + "/edicion_eliminacion_factura_pedido/updateGestionFe";

            let response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest" // buena práctica para diferenciar AJAX
                },
                body: JSON.stringify({
                    valor: valor
                })
            });

            if (!response.ok) {
                throw new Error("Error en la petición: " + response.status);
            }

            let data = await response.json();
            

            // Opcional: feedback al usuario
            if (data.response=="success") {
                sweet_alert_centrado('success','Configuración correcta ')
            } else {
                alert("No se pudo guardar la configuración ❌");
            }

        } catch (error) {
            console.error("Error en informe():", error);
            alert("Ocurrió un error al guardar.");
        }
    }
</script>

<?= $this->endSection('content') ?>