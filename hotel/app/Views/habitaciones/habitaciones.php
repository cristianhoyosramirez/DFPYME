<?php foreach ($habitaciones as $habitacion): ?>
    <tr id="trHabitacion<?= $habitacion['id_habitacion'] ?>">
        <td><?= htmlspecialchars($habitacion['nombre_mesa']) ?></td>
        <td><?= htmlspecialchars($habitacion['tipo']) ?></td>
        <td><?= number_format($habitacion['precio'], 0, ',', '.') ?></td>
        <td id="estado<?= $habitacion['id_habitacion'] ?>">
            <?php
            $badgeClass = '';
            $icon = '';
            $texto = $habitacion['estado'];

            switch ($habitacion['id_estado']) {
                case 1: // Disponible
                    $badgeClass = 'bg-success';
                    $icon = '<i class="fas fa-check-circle me-1"></i>';
                    break;
                case 2: // Reservada
                    $badgeClass = 'bg-warning text-dark';
                    $icon = '<i class="fas fa-clock me-1"></i>';
                    break;
                case 3: // ocupada
                    $badgeClass = 'bg-danger text-dark';
                    $icon = '<i class="fas fa-clock me-1"></i>';
                    break;
                case 4: // Mantenimiento
                    $badgeClass = 'bg-secondary';
                    $icon = '<i class="fas fa-tools me-1"></i>';
                    break;
                default:
                    $badgeClass = 'bg-info';
                    $icon = '<i class="fas fa-info-circle me-1"></i>';
            }
            ?>
            <span class="badge rounded-pill px-3 py-2 <?= $badgeClass ?>">
                <?= $icon ?><?= htmlspecialchars($texto) ?>
            </span>
        </td>
        <td class="d-flex justify-content-end align-items-center gap-1">
            <?php if ($habitacion['id_estado'] == 1): ?>
                <button class="btn btn-outline-dark btn-icon"
                    title="Reservar habitación"
                    onclick="abrirModalReserva(<?= $habitacion['id_habitacion'] ?>,'<?= htmlspecialchars($habitacion['nombre_mesa'], ENT_QUOTES) ?>')">
                    <i class="fas fa-calendar-alt"></i>
                </button>
            <?php elseif ($habitacion['id_estado'] == 2): ?>
                <button class="btn btn-outline-success btn-icon"
                    title="Confirmar ingreso"
                    onclick="confirmarIngreso(<?= $habitacion['id_habitacion'] ?>)">
                    <i class="fas fa-check-circle"></i>
                </button>

                <button class="btn btn-outline-warning btn-icon"
                    title="Editar reserva"
                    onclick="editar_reserva(<?= $habitacion['id_habitacion'] ?>)">
                    <i class="fas fa-edit"></i>
                </button>

                <button class="btn btn-outline-danger btn-icon"
                    title="Cancelar reserva"
                    onclick="cancelar_reserva(<?= $habitacion['id_habitacion'] ?>)">
                    <i class="fas fa-times-circle"></i>
                </button>
            <?php endif ?>

            <!-- Dropdown único para todas las filas -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-icon"
                    type="button"
                    id="dropdownMenuButton<?= $habitacion['id_habitacion'] ?>"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    title="Más acciones">
                    <i class="fas fa-ellipsis-v"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $habitacion['id_habitacion'] ?>">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="cancelar_reserva(<?= $habitacion['id_habitacion'] ?>)">
                            <i class="fas fa-trash-alt me-2"></i> Eliminar habitación
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="editar_reserva(<?= $habitacion['id_habitacion'] ?>)">
                            <i class="fas fa-edit me-2"></i> Editar habitación
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
<?php endforeach ?>