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
            

     
        </div>
    </div>
</div>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>



<?= $this->endSection('content') ?>