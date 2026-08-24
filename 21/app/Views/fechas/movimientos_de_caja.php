<table class="table table-hover">
  <thead class="table-dark">
    <tr>
      <td>Fecha apertura</th>
      <td>Hora apertura</th>
      <td>Fecha cierre</th>
      <td>Hora cierre</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($aperturas as $apertura): ?>
      <tr class="cursor-pointer"
        onclick="seleccionarApertura(
                    <?= $apertura['id_apertura'] ?>,
                    '<?= $apertura['fecha_apertura'] ?>',
                    '<?= $apertura['hora_apertura'] ?>',
                    '<?= $apertura['fecha_cierre'] ?>',
                    '<?= $apertura['hora_cierre'] ?>'
                )">

        <td><?= $apertura['fecha_apertura'] ?></td>
        <td><?= $apertura['hora_apertura'] ?></td>
        <td><?= $apertura['fecha_cierre'] ?></td>
        <td><?= $apertura['hora_cierre'] ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>

