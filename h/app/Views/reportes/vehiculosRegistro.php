<?php foreach ($vehiculos as $vehiculo): ?>
    <tr>
        <td><?= $vehiculo['tipo'] ?></td>
        <td><?= $vehiculo['placa'] ?></td>
        <td>
            <!-- Editar -->
            <button class="btn btn-warning btn-sm" title="Editar vehículo" onclick="editarVehiculo(<?= $vehiculo['id'] ?>)">
                <i class="fas fa-edit"></i>
            </button>

            <!-- Eliminar -->
            <button class="btn btn-danger btn-sm" title="Eliminar vehículo" onclick="eliminarVehiculo(<?= $vehiculo['id'] ?>)">
                <i class="fas fa-trash-alt"></i>
            </button>

            <!-- Ver detalles -->
            <button class="btn btn-info btn-sm" title="Ver detalles del vehículo" onclick="verDetallesVehiculo(<?= $vehiculo['id'] ?>)">
                <i class="fas fa-eye"></i>
            </button>
        </td>
    </tr>
<?php endforeach ?>