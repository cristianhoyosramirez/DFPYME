<?php $user_session = session(); ?>
<?= $this->extend('template/caja') ?>
<?= $this->section('title') ?>
CAJA
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Ventas</a></li>
                    <li class="breadcrumb-item"><a href="#">Caja</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('caja/apertura') ?>">Apertura de caja</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">Apertura de caja general  </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>



<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('caja_general/generar_apertura') ?>" method="POST" id="formulario_apertura">
            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="usuario_apertura">
            <div class="row">
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Fecha de aperura </label>
                    <input type="date" class="form-control" id="fecha_apertura_caja" name="fecha_apertura_caja" value=<?php echo date('Y-m-d'); ?>>
                    <div class="text-danger"><?= session('errors.salon') ?></div>
                </div>
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Caja </label>
                    <input type="text" class="form-control" name="numero_caja" value="Caja general" readonly >

                    <div class="text-danger"><?= session('errors.numero_caja') ?></div>
                </div>

                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Apertura</label>
                    <div class="input-icon mb-3">
                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                <path d="M12 3v3m0 12v3" />
                            </svg>
                        </span>
                        <input type="text" class="form-control" name="apertura_caja" id="apertura_caja" onkeyup="saltar_apertura(event,'abrir_caja_diaria')" autofocus>
                    </div>
                    <span id="error_apertura_caja" style="color:red;"></span>
                    <div class="text-danger"><?= session(
                                                    'errors.apertura_caja'
                                                ) ?></div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-md"><i class="mdi mdi-plus"></i> Generar apertura de caja general</button>
            </div>
        </form>
    </div>
</div>




<?= $this->endSection('content') ?>