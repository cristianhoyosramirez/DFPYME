<!-- Filtros -->
<form method="GET" class="row g-3 mb-4">

    <!-- Fecha inicial -->
    <div class="col-12 col-md-3">

        <label for="fecha_inicio" class="form-label fw-semibold small">
            Fecha inicial
        </label>

        <input type="date"
            id="fecha_inicio"
            name="fecha_inicio"
            class="form-control"
            value="<?= date('Y-m-d') ?>">

    </div>

    <!-- Fecha final -->
    <div class="col-12 col-md-3">

        <label for="fecha_final" class="form-label fw-semibold small">
            Fecha final
        </label>

        <input type="date"
            id="fecha_final"
            name="fecha_final"
            class="form-control"
            value="<?= date('Y-m-d') ?>">

    </div>

    <!-- Cliente -->
    <div class="col-12 col-md-4">

        <label for="cliente" class="form-label fw-semibold small">
            Buscar huésped / documento / habitación / placa
        </label>

        <input type="text"
            id="cliente"
            name="cliente"
            class="form-control"
            placeholder="Ingrese nombre, documento, habitación o placa"
            value="<?= htmlspecialchars($_GET['cliente'] ?? '') ?>"
            onkeyup="buscarClienteRegistro(this.value)">

    </div>

    <!-- Botones -->
    <div class="col-12 col-md-2 d-flex align-items-end gap-2">

        <button type="button" onclick="filtrarRegistroHotelero()" class="btn btn-outline-primary w-100">

            <i class="fas fa-search me-1"></i>
            Filtrar

        </button>

        <a href="<?= $_SERVER['PHP_SELF'] ?>"
            class="btn btn-outline-success w-100">

            <i class="fas fa-undo me-1"></i>
            Excel

        </a>

    </div>

</form>

<!-- Tabla responsive con head fijo -->
<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
    <table class="table table-striped table-bordered">
        <thead class="table-dark" style="position: sticky; top: 0; z-index: 10;">
            <tr>

                <th>Fecha</th>
                <th>Habitación</th>
                <th>Nombre y apellidos</th>
                <th>Tipo Documento</th>
                <th>Número Documento</th>
                <th>Teléfono</th>
                <th>Tipo Vehículo</th>
                <th>Placa</th>
                <th>Lugar Procedencia</th>
                <th>Lugar Destino</th>
                <th>Hora Salida</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody id="registroHotelero">
            <?php if (!empty($registro)): ?>
                <?php foreach ($registro as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= htmlspecialchars($row['habitacion']) ?></td>
                        <td><?= htmlspecialchars($row['nombrescliente'] . ' / ' . $row['nitcliente']) ?></td>
                        <td><?= htmlspecialchars($row['codigo_documento']) ?></td>
                        <td><?= htmlspecialchars($row['nitcliente']) ?></td>
                        <td><?= htmlspecialchars($row['telefonocliente']) ?></td>



                        <td><?= htmlspecialchars($row['tipo_vehiculo'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['placa_vehiculo'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['origen']) ?></td>
                        <td><?= htmlspecialchars($row['destino']) ?></td>
                        <td>
                            <?= !empty($row['hora_salida'])
                                ? date('h:i A', strtotime($row['hora_salida']))
                                : '-' ?>
                        </td>
                        <td><?= htmlspecialchars($row['notas_reserva'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">No hay registros</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>