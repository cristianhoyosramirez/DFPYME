<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    /* Contenedor general de mesas */
    .mesas-wrapper {
        height: calc(100vh - 140px); /* ajusta según tu header */
        overflow-y: auto;
        padding-right: 5px;
    }

    .mesa-card {
        border-radius: 14px;
        transition: all .25s ease;
    }

    .mesa-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,.15);
    }
</style>

<div class="container-fluid">

    <!-- TABS DE SALONES -->
    <ul class="nav nav-tabs mb-3" id="salonesTab" role="tablist">

        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#salon1">
                <i class="fas fa-store"></i> Salón Principal
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#salon2">
                <i class="fas fa-umbrella-beach"></i> Terraza
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#salon3">
                <i class="fas fa-star"></i> VIP
            </button>
        </li>

    </ul>

    <!-- CONTENIDO DE TABS -->
    <div class="tab-content">

        <!-- SALON PRINCIPAL -->
        <div class="tab-pane fade show active" id="salon1">
            <div class="mesas-wrapper">
                <div class="row g-3">

                    <!-- Mesas ejemplo -->
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card mesa-card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-chair fa-3x text-success mb-2"></i>
                                    <h6 class="fw-bold">Mesa <?= $i ?></h6>
                                    <span class="badge bg-success">Libre</span>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <a href="#" class="btn btn-outline-success btn-sm w-100">
                                        Abrir
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>

                </div>
            </div>
        </div>

        <!-- TERRAZA -->
        <div class="tab-pane fade" id="salon2">
            <div class="mesas-wrapper">
                <div class="row g-3">

                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card mesa-card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-chair fa-3x text-danger mb-2"></i>
                                    <h6 class="fw-bold">Mesa T<?= $i ?></h6>
                                    <span class="badge bg-danger">Ocupada</span>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <a href="#" class="btn btn-outline-danger btn-sm w-100">
                                        Ver cuenta
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>

                </div>
            </div>
        </div>

        <!-- VIP -->
        <div class="tab-pane fade" id="salon3">
            <div class="mesas-wrapper">
                <div class="row g-3">

                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card mesa-card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="fas fa-chair fa-3x text-warning mb-2"></i>
                                    <h6 class="fw-bold">Mesa VIP <?= $i ?></h6>
                                    <span class="badge bg-warning text-dark">Reservada</span>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <a href="#" class="btn btn-outline-warning btn-sm w-100">
                                        Detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>

                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
