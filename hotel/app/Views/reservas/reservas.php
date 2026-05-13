<h3 class="mb-3">Gestión de Reservas</h3>

<!-- 🔹 FILTROS RÁPIDOS -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <div class="row g-3 align-items-end">

            <!-- 🔍 Buscar habitación
            <div class="col-lg-3 col-md-6">
                <label class="form-label fw-semibold small text-muted">
                    Buscar habitación
                </label>

                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search text-secondary"></i>
                    </span>

                    <input type="text"
                        class="form-control"
                        placeholder="Número o nombre"
                        onkeyup="buscarHabitacion(this.value)">
                </div>
            </div>

            
            <div class="col-lg-2 col-md-3">
                <label class="form-label fw-semibold small text-muted">
                    Fecha inicio
                </label>

                <input type="date" id="fechaInicial"
                    class="form-control"
                    value="<?= date('Y-m-d') ?>">
            </div>

            
            <div class="col-lg-2 col-md-3">
                <label class="form-label fw-semibold small text-muted">
                    Fecha fin
                </label>

                <input type="date"
                    class="form-control" id="fechaFinal"
                    value="<?= date('Y-m-d') ?>">
            </div>

           
            <div class="col-lg-2 col-md-6">
                <button class="btn btn-outline-primary w-100" onclick="buscarHabitacionFecha()">
                    <i class="fas fa-calendar-check me-2"></i>
                    Buscar fechas
                </button>
            </div> -->
            <div class="col-lg-10 col-md-6">
            </div>

            <!-- ➕ Nueva reserva -->
            <div class="col-lg-2 col-md-6 text-start">
                <button class="btn btn-success w-100 fw-semibold"
                    onclick="modalNuevaReserva()">

                    <i class="fas fa-plus-circle me-2"></i>
                    Nueva reserva
                </button>
            </div>

        </div>

    </div>
</div>

<!-- 🔹 TABLA DE RESERVAS -->

<div class="table-responsive">
    <table class="table ">
        <thead class="table-dark">
            <tr>
                <th>Habitación</th>
                <th>Fecha reserva </th>
                <th>Tipo vehiculo</th>
                <th>Estado reserva</th>
                <th>Notas reserva</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="reservasHabitaciones">
            <?= $this->include('reservas/tablaReservas'); ?>
        </tbody>
    </table>
</div>