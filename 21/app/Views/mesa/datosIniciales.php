<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
MESAS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('mesas/save') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="id_usuario">
            <div class="row">
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Sálon </label>
                    <select class="form-select select2" name="salon" id="salon_mesa">
                        <?php foreach ($salones as $detalle) { ?>
                            <option value=""></option>
                            <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?> </option>
                        <?php } ?>
                    </select>
                    <div class="text-danger"><?= session('errors.salon') ?></div>
                </div>
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Nombre mesa</label>
                    <input type="text" class="form-control" name="nombre" autofocus>
                    <div class="text-danger"><?= session('errors.nombre') ?></div>
                </div>
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Cantidad mesas</label>
                    <input type="text" class="form-control" name="cantidad" value="1">
                    <div class="text-danger"><?= session('errors.cantidad') ?></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Imágen de la mesa</label>

                    <input
                        type="file"
                        class="form-control"
                        name="imagen"
                        accept=".jpg,.jpeg,.png,.webp">

                    <small class="text-muted">
                        Formatos permitidos: JPG, PNG y WEBP.
                    </small>

                    <div class="text-danger">
                        <?= session('errors.imagen') ?>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-md"><i class="mdi mdi-plus"></i> Crear mesa</button>
            </div>
        </form>
    </div>
</div>




<?= $this->endSection('content') ?>