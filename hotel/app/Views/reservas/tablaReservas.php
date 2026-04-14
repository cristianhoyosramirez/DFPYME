<?php foreach ($reservas as $detalle): ?>

    <tr id="reserva<?= $detalle['id_reserva'] ?>">

        <td><?= $detalle['nombre'] ?></td>
        <td><?= $detalle['fecha_reserva'] ?></td>

        <!-- Estado con BADGE -->
        <td>
            <?php
            $estado = $detalle['id_estado_reservas'];

            $badge = '';
            $texto = '';

            switch ($estado) {
                case 1:
                    $badge = 'bg-warning text-dark';
                    $texto = 'Pendiente';
                    break;
                case 2:
                    $badge = 'bg-primary';
                    $texto = 'Confirmada';
                    break;
                case 3:
                    $badge = 'bg-success';
                    $texto = 'En curso';
                    break;
                case 4:
                    $badge = 'bg-secondary';
                    $texto = 'Finalizada';
                    break;
                case 5:
                    $badge = 'bg-danger';
                    $texto = 'Cancelada';
                    break;
                case 6:
                    $badge = 'bg-success';
                    $texto = 'Check in';
                    break;
                default:
                    $badge = 'bg-light text-dark';
                    $texto = 'Sin estado';
                    break;
            }
            ?>

            <span class="badge rounded-pill px-3 py-2 <?= $badge ?>" id="estado<?= $detalle['id_reserva'] ?>">
                <?= $texto ?>
            </span>
        </td>

        <td style="min-width:220px;">
            <div class="nota-wrapper">

                <textarea
                    class="form-control form-control-sm nota-textarea"
                    id="nota<?= $detalle['id_reserva'] ?>"
                    oninput="autoGuardarNota(<?= $detalle['id_reserva'] ?>)"
                    rows="1"
                    placeholder="Escribe una nota..."><?= trim($detalle['notas']) ?></textarea>

                <!-- estado oculto inicialmente -->
                <small id="estadoNota<?= $detalle['id_reserva'] ?>"
                    class="nota-status d-none">
                </small>

            </div>
        </td>

        <td>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-icon"
                    type="button"
                    id="dropdownMenuButton<?= $detalle['id_habitacion'] ?>"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    title="Más acciones">
                    <i class="fas fa-ellipsis-v"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="dropdownMenuButton<?= $detalle['id_habitacion'] ?>">

                    <!-- Pendiente -->
                    <?php if ($estado == 1): ?>

                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="javascript:void(0);"
                                onclick="confirmar_reserva(
            <?= $detalle['id_reserva'] ?>,
            '<?= $detalle['fecha_reserva'] ?>',
            '<?= $detalle['nombre'] ?>',
            '<?= $detalle['notas'] ?>'
        )">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Confirmar reserva
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center text-warning"
                                href="javascript:void(0);"
                                onclick="cancelar_reserva(<?= $detalle['id_reserva'] ?>)">
                                <i class="fas fa-ban me-2"></i>
                                Cancelar reserva
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center text-danger"
                                href="javascript:void(0);"
                                onclick="eliminar_reserva(<?= $detalle['id_reserva'] ?>)">
                                <i class="fas fa-trash-alt me-2"></i>
                                Eliminar reserva
                            </a>
                        </li>

                    <?php endif; ?>

                    <!-- Confirmada -->
                    <?php if ($estado == 2): ?>

                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="javascript:void(0);"
                                onclick="checkin_reserva(<?= $detalle['id_reserva'] ?>)">
                                <i class="fas fa-sign-in-alt text-primary me-2"></i>
                                Check-in
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center text-danger"
                                href="javascript:void(0);"
                                onclick="cancelar_reserva(<?= $detalle['id_reserva'] ?>)">
                                <i class="fas fa-times-circle me-2"></i>
                                Cancelar
                            </a>
                        </li>

                    <?php endif; ?>

                    <!-- En curso -->
                    <?php if ($estado == 3): ?>

                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="javascript:void(0);"
                                onclick="checkout_reserva(<?= $detalle['id_reserva'] ?>)">
                                <i class="fas fa-sign-out-alt text-warning me-2"></i>
                                Check-out
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>
        </td>

    </tr>

<?php endforeach; ?>