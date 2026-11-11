<?php if (!empty($ventas)): ?>

    <?php foreach ($ventas as $venta): ?>
        <tr>
            <td><?= $venta['nombreproducto'] ?></td>
            <td><?= $venta['nombresusuario_sistema'] ?></td>
            <td class="text-end"><?= number_format($venta['cantidad_vendida'], 0, ',', '.') ?></td>
            <td class="text-end"><?= number_format($venta['valor_unitario'], 0, ',', '.') ?></td>
            <td class="text-end"><?= number_format($venta['valor_total'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>

<?php else: ?>

    <tr>
        <td colspan="5" class="text-center">
            <div class="alert alert-warning mb-0">
                No se encontraron ventas para los filtros seleccionados.
            </div>
        </td>
    </tr>

<?php endif; ?>