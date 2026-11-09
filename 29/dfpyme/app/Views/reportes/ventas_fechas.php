  <?php foreach ($ventas as $venta): ?>
      <tr>
          <td><?= $venta['fecha'] ?> </td>
          <td><?= number_format($venta['base'], 0, ',', '.') ?></td>
          <td><?= number_format($venta['total_iva'], 0, ',', '.') ?></td>
          <td><?= number_format($venta['total_ico'], 0, ',', '.') ?></td>
          <td><?= number_format($venta['total_ventas'], 0, ',', '.') ?></td>
          <td>
              <button class="btn btn-sm btn-primary">Ver</button>
          </td>
      </tr>

  <?php endforeach ?>