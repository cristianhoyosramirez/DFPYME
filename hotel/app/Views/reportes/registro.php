<!-- Filtros -->
<form method="GET" class="row g-3 mb-3">
    <div class="col-md-3">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" id="fecha" name="fecha" class="form-control" value="<?= htmlspecialchars($_GET['fecha'] ?? '') ?>">
    </div>
    <div class="col-md-3">
        <label for="habitacion" class="form-label">Habitación</label>
        <input type="text" id="habitacion" name="habitacion" class="form-control" placeholder="Número o nombre" value="<?= htmlspecialchars($_GET['habitacion'] ?? '') ?>">
    </div>
    <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Reset</a>
    </div>
</form>

<!-- Tabla responsive con head fijo -->
<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
    <table class="table table-striped table-bordered">
        <thead class="table-dark" style="position: sticky; top: 0; z-index: 10;">
            <tr>
                <th>Habitación</th>
                <th>Nombre y apellidos</th>
                <th>Tipo Documento</th>
                <th>Número Documento</th>
                <th>Tipo Vehículo</th>
                <th>Placa</th>
                <th>Lugar Procedencia</th>
                <th>Lugar Destino</th>
                <th>Hora Salida</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($registro)): ?>
                <?php foreach($registro as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['habitacion']) ?></td>
                    <td><?= htmlspecialchars($row['nombrescliente'] . ' | ' . $row['nitcliente']) ?></td>
                    <td><?= htmlspecialchars($row['codigo_documento']) ?></td>
                    <td><?= htmlspecialchars($row['nitcliente']) ?></td>
                    <td><?= htmlspecialchars($row['tipo_vehiculo'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['placa_vehiculo'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['origen']) ?></td>
                    <td><?= htmlspecialchars($row['destino']) ?></td>
                    <td><?= htmlspecialchars($row['hora_salida'] ?? '-') ?></td>
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