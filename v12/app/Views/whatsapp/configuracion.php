<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>

<?= $this->section('title') ?>
Configuración
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h4 class="mb-4 text-center h3 text-primary">Configuración de Pedidos</h4>

    <!-- Configuración principal -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            
           
            <div class="mb-3">
                <label for="salonSelect" class="form-label">Indique si desea habilitar la gestión de pedidos vía WhatsApp.</label>
                <select id="salonSelect" class="form-select" onchange="salonCambiado(this.value)">
                    
                    <option value="true">Si</option>
                    <option value="false">No</option>
                    
                </select>
            </div>
        </div>
    </div>

    <!-- Configuración de salón -->
    <div class="accordion" id="accordionSalones">
        <div class="accordion-item shadow-sm">
            <h2 class="accordion-header" id="headingSalon">
                <button class="accordion-button collapsed d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSalon" aria-expanded="false" aria-controls="collapseSalon">
                    <i class="bi bi-house-door me-2"></i> Configuración pedidos WhatsApp
                </button>
            </h2>
            <div id="collapseSalon" class="accordion-collapse collapse" aria-labelledby="headingSalon" data-bs-parent="#accordionSalones">
                <div class="accordion-body">

                    <!-- Nombre del salón -->
                    <div class="mb-3 d-flex align-items-center">
                        <label for="salonName" class="form-label me-2">Zona:</label>
                        <input type="text" id="salonName" class="form-control me-2" value="Salón WhatsApp">
                        <button class="btn btn-outline-success btn-icon" type="button" title="Editar Zona">
                            <!-- Download SVG icon from http://tabler-icons.io/i/refresh -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                            </svg>
                        </button>
                    </div>


                    <!-- Número de mesas -->
                    <div class="mb-3 d-flex align-items-center">
                        <label for="numMesas" class="form-label me-2">Número de mesas:</label>

                        <input type="number"
                            id="numMesas"
                            class="form-control me-2"
                            value="30"
                            min="1"
                            max="99"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); 
                if(this.value > 99) this.value = 99;">


                        <button class="btn btn-outline-success btn-icon" type="button" title="Crear Mesas">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </button>
                    </div>


                    <!-- Mesas -->
                    <h6 class="mb-3">Mesas del salón</h6>
                    <div class="row g-2">
                        <?php for ($i = 1; $i <= 30; $i++): ?>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-light hover-shadow">

                                    <!-- Nombre editable de la mesa -->
                                    <input type="text" class="form-control form-control me-2" value="Mesa <?= $i ?>">

                                    <!-- Icono eliminar -->
                                    <button class="btn btn-sm btn-outline-danger btn-icon" title="Eliminar Mesa <?= $i ?>">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <line x1="4" y1="7" x2="20" y2="7" />
                                            <line x1="10" y1="11" x2="10" y2="17" />
                                            <line x1="14" y1="11" x2="14" y2="17" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>
                                    </button> &nbsp;
                                    <!-- Icono eliminar -->
                                    <button class="btn btn-sm btn-outline-success btn-icon" title="Editar <?= $i ?>">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                            <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                        </svg>
                                    </button>

                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: box-shadow 0.2s ease-in-out;
    }
</style>
<?= $this->endSection('content') ?>