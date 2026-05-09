     <?php foreach ($registro as $row): ?>
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