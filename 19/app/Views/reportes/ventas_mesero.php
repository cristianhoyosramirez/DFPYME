  <?php foreach ($ventas as $detalle): ?>

      <tr>
          <td><?= $detalle['nombresusuario_sistema']; ?></td>
          <td><?= $detalle['mesas_atendidas']; ?></td>
          <td><?= $detalle['facturas']; ?></td>

          <td>$ <?= number_format($detalle['total_vendido'], 0, '', '.'); ?></td>
          <td>$ <?= number_format($detalle['promedio_venta'], 0, '', '.'); ?></td>
          <td>$ <?= number_format($detalle['total_propinas'], 0, '', '.'); ?></td>
          <td>$ <?= number_format($detalle['promedio_propina'], 0, '', '.'); ?></td>
      </tr>

  <?php endforeach ?>