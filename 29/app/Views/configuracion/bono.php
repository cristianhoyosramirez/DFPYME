<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
Configuración
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<p class="text-center h3 text-primary">Gestión de bono</p><br>
<div class="card container">
    <input type="hidden" value="<?php echo base_url() ?>" id="url">


    <div class="card-body">
        <div class="row g-3 align-items-start">
            <div class="col-md-4">
                <label class="form-label">Mostrar botón imprimir bono</label>

               
                <select name="mostrarBono" id="mostrarBono" class="form-select" onchange="mostrarBoton(this.value)">
                    <option value="true" <?= $mostrarBoton == 't' ? 'selected' : '' ?>>Sí</option>
                    <option value="false" <?= $mostrarBoton == 'f' ? 'selected' : '' ?>>No</option>
                </select>

            </div>

            <div class="col-md-8">
                <label for="terminos" class="form-label">Términos y condiciones</label>
                <textarea
                    name="terminos"
                    id="terminos"
                    class="form-control"
                    rows="4"
                    onkeyup="terminosCondiciones(this.value)">
    <?php echo $terminosCondiciones; ?>
</textarea>
            </div>
        </div>
    </div>
</div>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>



<script>
    async function mostrarBoton(valor) {

        try {

            const response = await fetch("<?= base_url('configuracion/mostrarBotonBono') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valor: valor,

                })
            });

            const data = await response.json();

            if (data.response === 'success') {



            }
        } catch (error) {
            alert("Error en la validación de resolución.");
            console.error(error);
        }

    }
</script>
<script>
    async function terminosCondiciones(valor) {

        try {

            const response = await fetch("<?= base_url('configuracion/terminosCondiciones') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valor: valor,

                })
            });

            const data = await response.json();

            if (data.response === 'success') {



            }
        } catch (error) {
            alert("Error en la validación de resolución.");
            console.error(error);
        }

    }
</script>




<?= $this->endSection('content') ?>