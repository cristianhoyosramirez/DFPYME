<?php
  $fechas = model('KardexConceptoModel')->fechasApertura($id_apertura);
?>


<div class="row mb-3">

  <div class="col-md-6">
    <p class="mb-0">
      <strong>Fecha inicial:</strong>
      <span id="fecha_inicial" class="text-muted"><?= $fechas[0]['fecha_apertura'] ?></span>
    </p>
  </div>

  <div class="col-md-6">
    <p class="mb-0">
      <strong>Fecha final:</strong>
      <span id="fecha_final" class="text-muted"><?= $fechas[0]['fecha_cierre'] ?></span>
    </p>
  </div>

</div>
<?php if (!empty($meseros)) : ?>

  <?php foreach ($meseros as $mesero) : ?>

    <?php
    $datos = model('FacturaPropinaModel')->getPropinas(
      $id_apertura,
      $mesero['id_mesero']
    );

    $total = model('FacturaPropinaModel')->get_total_propinas(
      $id_apertura,
      $mesero['id_mesero']
    );

    $total_propina = $total[0]['total_propina'] ?? 0;
    ?>

    <?php if (!empty($datos)) : ?>

      <table class="table table-striped table-hover mb-4">

        <thead>
          <tr class="table-primary">
            <td colspan="4" class="text-dark">
              <?= esc($datos[0]['mesero'] ?? 'Mesero general') ?>
              </th>
          </tr>

          <tr class="table-dark">
            <td>Mesa</th>
            <td>Documento</th>
            <td>Valor documento</th>
            <td>Valor propina</th>
          </tr>
        </thead>

        <tbody>

          <?php foreach ($datos as $dato) : ?>

            <tr>
              <td>
                <?= esc($dato['mesa'] ?? 'SIN MESA') ?>
              </td>

              <td>
                <?= esc($dato['documento'] ?? '') ?>
              </td>

              <td>
                $<?= number_format($dato['total_documento'] ?? 0, 0, ',', '.') ?>
              </td>

              <td>
                $<?= number_format($dato['propina'] ?? 0, 0, ',', '.') ?>
              </td>
            </tr>

          <?php endforeach; ?>

        </tbody>

        <?php if ($total_propina > 0) : ?>

          <tfoot>
            <tr>
              <th colspan="3" class="text-end">
                Total:
              </th>

              <th class="table-warning">
                $<?= number_format($total_propina, 0, ',', '.') ?>
              </th>
            </tr>
          </tfoot>

        <?php endif; ?>

      </table>

    <?php endif; ?>

  <?php endforeach; ?>

<?php else : ?>

  <div class="card">
    <div class="card-header text-center">
      <h3 class="card-title text-primary mb-0">
        Total propinas
      </h3>
    </div>

    <div class="card-body">
      <p class="text-center h3 mb-0">$0</p>
    </div>
  </div>

<?php endif; ?>