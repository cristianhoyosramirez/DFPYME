<div class="modal fade" id="modalNuevaReserva" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-black text-white">
                <h5 class="modal-title">Reservar habitación</h5>
                <button type="button" class="btn-close btn-close-white" onclick="cerrarModalHuesped()"></button>
            </div>

            <div class="modal-body">

                <form id="formRegistroHuesped">

                    <!-- ID oculto -->
                    <input type="hidden"
                        id="id_de_habitacion"
                        name="id_de_habitacion">

                    <div class="card-body p-4">

                        <div class="row g-3">

                            <!-- =========================
                FILA 1
            ========================== -->

                            <!-- Fecha -->
                            <div class="col-12 col-md-4">

                                <label class="form-label fw-semibold small">
                                    Fecha reserva
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>

                                    <input type="date"
                                        class="form-control"
                                        name="fecha"
                                        id="fechaNuevaReserva"
                                        value="<?= date('Y-m-d') ?>"
                                        required>

                                </div>

                            </div>

                            <!-- Habitación -->
                            <?php $habitaciones = model('habitacionesModel')->getTodasHabitaciones(); ?>

                            <div class="col-12 col-md-4">

                                <label for="id_habitacionNuevaReserva"
                                    class="form-label fw-semibold small">

                                    Habitación

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-bed"></i>
                                    </span>

                                    <select name="id_habitacion"
                                        id="id_habitacionNuevaReserva"
                                        class="form-select">

                                        <?php foreach ($habitaciones as $h): ?>

                                            <option value="<?= $h['id_habitacion'] ?>">
                                                <?= $h['nombre_habitacion'] ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                            <!-- Tipo vehículo -->
                            <div class="col-12 col-md-4">

                                <label for="tipo"
                                    class="form-label fw-semibold small">

                                    Tipo vehículo

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-car"></i>
                                    </span>

                                    <select class="form-select"
                                        id="tipoVehiculo"
                                        name="tipo"
                                        required>


                                        <option value="Tractomula">Tractomula</option>
                                        <option value="Carro">Carro</option>
                                        <option value="Moto">Moto</option>
                                        <option value="Camioneta">Camioneta</option>
                                        <option value="Camión">Camión</option>
                                        <option value="Bus">Bus</option>
                                        <option value="Doble troque">Doble troque</option>
                                        <option value="Tractor">Tractor</option>

                                    </select>

                                </div>

                            </div>

                            <!-- =========================
                FILA 2
            ========================== -->

                            <!-- Observaciones -->
                            <div class="col-12">

                                <label class="form-label fw-semibold small">
                                    Observaciones
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text align-items-start pt-2">
                                        <i class="fas fa-sticky-note"></i>
                                    </span>

                                    <textarea name="observaciones"
                                        id="observacionesNuevaReserva"
                                        class="form-control"
                                        rows="4"
                                        placeholder="Ingrese observaciones adicionales..."></textarea>

                                </div>

                            </div>

                        </div>

                        <!-- =========================
            BOTONES
        ========================== -->

                        <div class="d-flex justify-content-end mt-4 gap-2 flex-wrap">

                            <button type="reset"
                                class="btn btn-outline-danger px-4"
                                onclick="cerrarModalHuesped()">

                                <i class="fas fa-times me-1"></i>
                                Cancelar

                            </button>

                            <button type="button"
                                class="btn btn-outline-success px-4 shadow-sm"
                                onclick="nuevaReserva()">

                                <i class="fas fa-save me-1"></i>
                                Reservar

                            </button>

                        </div>

                    </div>

                </form>



            </div>

        </div>
    </div>
</div>