<h3 class="mb-3">Gestión de Reservas</h3>

<!-- 🔹 FILTROS RÁPIDOS -->
<div class="row mb-3 g-3 align-items-end">

    <!-- 🔍 Buscar por habitación -->
    <div class="col-md-3">
        <label class="form-label small fw-semibold">Habitación</label>
        <input type="text" class="form-control" placeholder="Buscar por habitación" onkeyup="buscarHabitacion(this.value)">
    </div>
    <?php $estados = model('estadoReservasModel')->findAll(); ?>
    <div class="col-md-3">
        <label class="form-label small fw-semibold">Estado</label>
        <select name="estado" id="estado" class="form-select" onchange="cambiarEstado(this.value)">
            
            <?php foreach ($estados as $s): ?>
                <option value="<?= $s['id'] ?>">
                    <?= $s['descripcion'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- 📅 Fecha inicio -->
    <div class=" col-md-2">
        <label class="form-label small fw-semibold">Fecha inicio</label>
        <input type="date" class="form-control"
            value="<?= date('Y-m-d') ?>">
    </div>

    <!-- 📅 Fecha fin -->
    <div class="col-md-2">
        <label class="form-label small fw-semibold">Fecha fin</label>
        <input type="date" class="form-control"
            value="<?= date('Y-m-d') ?>">
    </div>

    <!-- ➕ Botón -->
    <div class="col-md-2">
        <label class="form-label small fw-semibold invisible">Acción</label>
        <button class="btn btn-success w-100" onclick="modalNuevaReserva()">
            <i class="fas fa-plus"></i> Nueva reserva
        </button>
    </div>

</div>

<!-- 🔹 TABLA DE RESERVAS -->

<div class="table-responsive">
    <table class="table ">
        <thead class="table-dark">
            <tr>
                <th>Habitación</th>
                <th>Fecha reserva </th>
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