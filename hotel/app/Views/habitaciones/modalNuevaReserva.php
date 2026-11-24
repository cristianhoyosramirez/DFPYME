<div class="modal fade" id="modalNuevaReserva" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-black text-white">
                <h5 class="modal-title">Reservar habitación</h5>
                <button type="button" class="btn-close btn-close-white" onclick="cerrarModalHuesped()"></button>
            </div>

            <div class="modal-body">

                <form id="formRegistroHuesped">
                    <!-- ID oculto de la habitación -->
                    <input type="text" id="id_de_habitacion" name="id_de_habitacion" hidden>

                    <div class="card-body p-4">


                        <div class="row g-3">
                            <!-- Fecha de reserva -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Fecha reserva</label>
                                <input type="date"
                                    class="form-control"
                                    name="fecha"
                                    id="fechaNuevaReserva"
                                    value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>

                            <!-- Habitación --><?php $habitaciones = model('habitacionesModel')->getTodasHabitaciones(); ?>
                            <div class="col-12 col-md-4">
                                <label for="id_habitacion" class="form-label fw-semibold small">Habitación</label>

                                <select name="id_habitacion" id="id_habitacionNuevaReserva" class="form-select">
                                    <?php foreach ($habitaciones as $h): ?>
                                        <option value="<?= $h['id_habitacion'] ?>">
                                            <?= $h['nombre_habitacion'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Observaciones</label>
                                <textarea name="observaciones" id="observacionesNuevaReserva" class="form-control"></textarea>
                            </div>
                        </div>

                        <!-- BLOQUE 3: Botones -->
                        <div class="d-flex justify-content-end mt-4 gap-2 flex-wrap">
                            <button type="reset" class="btn btn-outline-danger px-4 border" onclick="cerrarModalHuesped()">
                                <i class="fas fa-eraser me-1"></i> Cancelar
                            </button>

                            <button type="button" class="btn btn-outline-success px-4 shadow-sm" onclick="nuevaReserva()">
                                <i class="fas fa-save me-1"></i> Reservar
                            </button>

                        </div>
                    </div>
                </form>



            </div>

        </div>
    </div>
</div>