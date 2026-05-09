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
        <td style="min-width: 200px;">

            <?php if ($estado == 1): ?>

                <!-- Editable -->

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

                <!-- Solo lectura -->

                <div class="fw-semibold text-muted">

                    <i class="fas fa-calendar-alt me-1"></i>

                    <?= date('d/m/Y', strtotime($detalle['fecha_reserva'])) ?>

                </div>

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
        <td style="min-width: 280px;">

            <?php if ($estado == 1): ?>

                <!-- Editable -->

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

            <?php elseif ($estado == 6): ?>

                <!-- Solo lectura -->

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

                    <!-- ========================================= -->
                    <!-- ESTADO PENDIENTE -->
                    <!-- ========================================= -->
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


                    <!-- ========================================= -->
                    <!-- ESTADO CONFIRMADA -->
                    <!-- ========================================= -->
                    <?php if ($estado == 6): ?>

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