<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <td scope="col" class="text-start">Mesa</th>
                <td scope="col" class="text-center" style="width: 150px;">Acción</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($mesas)): ?>

                <?php foreach ($mesas as $mesa): ?>

                   <tr id="mesaActivar<?= $mesa['id'] ?>">
                        <td class="fw-semibold">
                            <?= esc($mesa['nombre']) ?>
                        </td>

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-outline-success"
                                onclick="activarMesa(<?= $mesa['id'] ?>)">
                                <i class="fas fa-check-circle me-1"></i>
                                Activar
                            </button>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="2" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-1"></i>
                        No hay mesas inactivas.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>
    </table>
</div>