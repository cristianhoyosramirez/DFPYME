<?php foreach ($reservas as $detalle): ?>

    <?php

    $estado = (int)$detalle['id_estado_reservas'];

    /*
    |--------------------------------------------------------------------------
    | Configuración visual de estados
    |--------------------------------------------------------------------------
    */
    $estados = [

        1 => [
            'texto' => 'Pendiente',
            'badge' => 'bg-warning text-dark'
        ],

        6 => [
            'texto' => 'Confirmada',
            'badge' => 'bg-success'
        ]

    ];

    $configEstado = $estados[$estado] ?? [
        'texto' => 'Sin estado',
        'badge' => 'bg-secondary'
    ];

    ?>

    <tr id="reserva<?= $detalle['id_reserva'] ?>" class="align-middle">

        <!-- ===================================================== -->
        <!-- HABITACIÓN -->
        <!-- ===================================================== -->
        <td>

            <div class="fw-semibold text-dark">

                <i class="fas fa-bed text-secondary me-1"></i>

                <?= esc($detalle['nombre']) ?>

            </div>

        </td>


        <!-- ===================================================== -->
        <!-- FECHA -->
        <!-- ===================================================== -->
        <td style="min-width: 220px;">

            <?php if ($estado == 1): ?>

                <div class="d-flex gap-2 align-items-center">

                    <input
                        type="date"
                        class="form-control form-control-sm"
                        id="fechaReserva<?= $detalle['id_reserva'] ?>"
                        value="<?= date('Y-m-d', strtotime($detalle['fecha_reserva'])) ?>">

                    <button
                        class="btn btn-sm btn-outline-primary"
                        title="Guardar fecha"
                        onclick="actualizar_fecha_reserva(
                            <?= $detalle['id_reserva'] ?>,
                            document.getElementById('fechaReserva<?= $detalle['id_reserva'] ?>').value
                        )">

                        <i class="fas fa-save"></i>

                    </button>

                </div>

            <?php else: ?>

                <div class="fw-semibold text-muted">

                    <i class="fas fa-calendar-alt me-1"></i>

                    <?= date('d/m/Y', strtotime($detalle['fecha_reserva'])) ?>

                </div>

            <?php endif; ?>

        </td>


        <!-- ===================================================== -->
        <!-- VEHÍCULO -->
        <!-- ===================================================== -->
        <td style="min-width: 200px;">

            <?php if ($estado == 1): ?>

                <select class="form-select form-select-sm"
                    onchange="cambiarVehiculo(this.value, <?= $detalle['id_reserva'] ?>)">

                    <option value="Tractomula"
                        <?= $detalle['vehiculo'] == 'Tractomula' ? 'selected' : '' ?>>
                        Tractomula
                    </option>

                    <option value="Carro"
                        <?= $detalle['vehiculo'] == 'Carro' ? 'selected' : '' ?>>
                        Carro
                    </option>

                    <option value="Moto"
                        <?= $detalle['vehiculo'] == 'Moto' ? 'selected' : '' ?>>
                        Moto
                    </option>

                    <option value="Camioneta"
                        <?= $detalle['vehiculo'] == 'Camioneta' ? 'selected' : '' ?>>
                        Camioneta
                    </option>

                    <option value="Camión"
                        <?= $detalle['vehiculo'] == 'Camión' ? 'selected' : '' ?>>
                        Camión
                    </option>

                    <option value="Bus"
                        <?= $detalle['vehiculo'] == 'Bus' ? 'selected' : '' ?>>
                        Bus
                    </option>

                    <option value="Doble troque"
                        <?= $detalle['vehiculo'] == 'Doble troque' ? 'selected' : '' ?>>
                        Doble troque
                    </option>

                    <option value="Tractor"
                        <?= $detalle['vehiculo'] == 'Tractor' ? 'selected' : '' ?>>
                        Tractor
                    </option>

                </select>

            <?php else: ?>

                <span class="fw-semibold text-muted">

                    <?= esc($detalle['vehiculo']) ?>

                </span>

            <?php endif; ?>

            

        </td>


        <!-- ===================================================== -->
        <!-- ESTADO -->
        <!-- ===================================================== -->
        <td>

            <span class="badge rounded-pill px-3 py-2 <?= $configEstado['badge'] ?>">

                <?= $configEstado['texto'] ?>

            </span>

        </td>


        <!-- ===================================================== -->
        <!-- NOTAS -->
        <!-- ===================================================== -->
        <td style="min-width: 300px;">

            <?php if ($estado == 1): ?>

                <div class="position-relative">

                    <textarea
                        class="form-control form-control-sm"
                        id="nota<?= $detalle['id_reserva'] ?>"
                        rows="1"
                        placeholder="Agregar nota..."
                        oninput="autoGuardarNota(<?= $detalle['id_reserva'] ?>)"><?= esc(trim($detalle['notas'])) ?></textarea>

                    <small
                        id="estadoNota<?= $detalle['id_reserva'] ?>"
                        class="text-success d-none">
                    </small>

                </div>

            <?php else: ?>

                <div class="bg-light border rounded-3 p-2 shadow-sm">

                    <?php if (!empty(trim($detalle['notas']))): ?>

                        <div class="small text-dark fw-semibold">

                            <i class="fas fa-sticky-note text-warning me-1"></i>

                            <?= esc($detalle['notas']) ?>

                        </div>

                    <?php else: ?>

                        <span class="text-muted small fst-italic">

                            Sin observaciones

                        </span>

                    <?php endif; ?>

                </div>

            <?php endif; ?>

        </td>


        <!-- ===================================================== -->
        <!-- ACCIONES -->
        <!-- ===================================================== -->
        <td class="text-end">

            <div class="dropdown">

                <button
                    class="btn btn-light border btn-sm shadow-sm"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    title="Más acciones">

                    <i class="fas fa-ellipsis-h"></i>

                </button>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">

                    <?php if ($estado == 1): ?>

                        <!-- Confirmar -->
                        <li>

                            <a
                                class="dropdown-item d-flex align-items-center gap-2"
                                href="javascript:void(0);"
                                onclick="confirmar_reserva(
                                    <?= $detalle['id_reserva'] ?>,
                                    '<?= $detalle['fecha_reserva'] ?>',
                                    '<?= esc($detalle['nombre']) ?>',
                                    '<?= esc($detalle['notas']) ?>'
                                )">

                                <i class="fas fa-check-circle text-success"></i>

                                <span>Confirmar reserva</span>

                            </a>

                        </li>

                        <!-- Cancelar -->
                        <li>

                            <a
                                class="dropdown-item d-flex align-items-center gap-2"
                                href="javascript:void(0);"
                                onclick="cancelar_reserva(<?= $detalle['id_reserva'] ?>)">

                                <i class="fas fa-ban text-warning"></i>

                                <span>Cancelar reserva</span>

                            </a>

                        </li>

                    <?php endif; ?>


                    <?php if ($estado == 6): ?>

                        <!-- Ver detalle -->
                        <li>

                            <a
                                class="dropdown-item d-flex align-items-center gap-2"
                                href="javascript:void(0);"
                                onclick="editarReserva(<?= $detalle['id_reserva'] ?>)">

                                <i class="fas fa-info-circle text-primary"></i>

                                <span>Detalle</span>

                            </a>

                        </li>

                    <?php endif; ?>

                </ul>

            </div>

        </td>

    </tr>

<?php endforeach; ?>