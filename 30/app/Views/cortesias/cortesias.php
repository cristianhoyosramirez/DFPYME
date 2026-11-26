
<?php if (!empty($cortesias)): ?>

    <?php foreach ($cortesias as $cortesia): ?>

        <tr class="cursor-pointer" id="fila<?= $cortesia['id_factura']; ?>"
            onclick="verDetalle(<?= $cortesia['id_factura'] ?>)">

            <td>
                <?= $cortesia['fecha'] ?>
            </td>

            <td>
                <?= date('h:i A', strtotime(substr($cortesia['hora'], 0, 8))) ?>
            </td>

            <td>
                <?= $cortesia['nit_cliente'] ?>
            </td>

            <td>
                <?= $cortesia['nombrescliente'] ?>
            </td>

            <td>
                <?= $cortesia['documento'] ?>
            </td>

            <td class="text-end">
                <?= number_format(
                    $cortesia['total_documento'],
                    0,
                    ',',
                    '.'
                ) ?>
            </td>

            <td class="text-center">
                Contado
            </td>

        </tr>

    <?php endforeach; ?>

<?php else: ?>

    <tr>

        <td colspan="7" class="py-3">

            <div class="alert alert-light alert-dismissible fade show mb-0 text-center border"
                role="alert">

                <i class="bi bi-info-circle me-2"></i>

                No se encontraron cortesías.

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Cerrar">
                </button>

            </div>

        </td>

    </tr>

<?php endif; ?>