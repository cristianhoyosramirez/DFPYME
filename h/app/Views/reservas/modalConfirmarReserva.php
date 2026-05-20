<div class="modal fade" id="confirmar_reserva" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Confirmar Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="limpiarReserva()"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistroHuesped">

                    <!-- IDs ocultos -->
                    <input type="hidden" id="id_de_habitacion" name="id_de_habitacion">
                    <input type="hidden" id="id_habitacion_reserva" name="id_habitacion_reserva">
                    <input type="hidden" id="id_cliente" name="id_cliente">
                    <input type="hidden" id="id_reserva_edicion" name="id_reserva_edicion">

                    <div class="card-body p-4">

                        <div class="row g-4">

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
                                        name="fecha_reserva"
                                        id="fecha_reserva"
                                        value="<?= date('Y-m-d') ?>"
                                        required>

                                </div>
                            </div>

                            <!-- Habitación -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">
                                    Habitación
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-bed"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control bg-light"
                                        name="habitacion_reserva"
                                        id="habitacion_reserva"
                                        readonly>

                                </div>
                            </div>

                            <!-- Valor hospedaje -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">
                                    Valor hospedaje
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control"
                                        name="valor_hospedaje"
                                        id="valor_hospedaje"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        placeholder="0"
                                        oninput="formatCurrency(this)"
                                        required>

                                </div>
                            </div>



                            <!-- =========================
                                    FILA 2
                            ========================== -->

                            <!-- Huésped -->
                            <div class="col-12 col-lg-4 position-relative">

                                <label class="form-label fw-semibold small">
                                    Cliente
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control"
                                        name="huesped"
                                        id="huesped"
                                        placeholder="Buscar huésped"
                                        autocomplete="off"
                                        onkeyup="buscarCliente(event,this.value)"
                                        autofocus>

                                    <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="limpiarHuesped()">

                                        <i class="fas fa-times"></i>

                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="modalCliente()" title="Crear cliente">

                                       <i class="fas fa-plus-circle"></i>

                                    </button>

                                </div>



                                <div id="listaClientes"></div>



                            </div>

                            <!-- Nombre completo -->
                            <div class="col-12 col-lg-4">

                                <label class="form-label fw-semibold small">
                                    Nombres y apellidos
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control bg-light text-dark fw-bold fs-5"
                                        name="nombre_completo"
                                        id="nombre_completo"
                                        placeholder="Nombre completo del huésped"
                                        required
                                        readonly>

                                </div>

                                <span class="text-danger small"
                                    id="id_clienteError"></span>

                            </div>

                            <div class="col-12 col-lg-4">
                                <label class="form-label fw-semibold small">
                                    Teléfono
                                </label>

                                <input type="text"
                                    class="form-control bg-light"
                                    name="nombre_completo"
                                    id="telefono_cliente"
                                    placeholder="Teléfono" required>
                            </div>

                            <!-- =========================
                                    FILA 3
                            ========================== -->

                            <!-- Vehículo -->
                            <div class="col-12 col-md-4">

                                <label class="form-label fw-semibold small">
                                    Vehículo
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-car"></i>
                                    </span>

                                    <input type="hidden" id="id_placa" name="id_placa">

                                    <input type="text"
                                        class="form-control text-uppercase"
                                        name="placaVehiculo"
                                        id="placavehiculo"
                                        autocomplete="off"
                                        autocorrect="off"
                                        autocapitalize="characters"
                                        spellcheck="false"
                                        onkeyup="buscarPlaca(this.value)"
                                        placeholder="Ej: ABC123">

                                    <span class="input-group-text">
                                        <i class="fas fa-plus"></i>
                                    </span>

                                </div>

                                <span id="id_placaError" class="text-danger small"></span>

                                <div id="listaVehiculos"></div>

                            </div>

                            <!-- Procedencia -->
                            <div class="col-12 col-md-4">

                                <label class="form-label fw-semibold small">
                                    Procedencia
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>

                                    <input type="hidden"
                                        id="id_procedencia"
                                        name="id_procedencia">

                                    <input type="text"
                                        class="form-control"
                                        id="procedencia"
                                        name="procedencia"
                                        placeholder="Ciudad de origen"
                                        autocomplete="off"
                                        autocorrect="off"
                                        autocapitalize="words"
                                        spellcheck="false"
                                        onkeyup="buscarCiudadProcedencia(this.value)">

                                    <button type="button"
                                        class="input-group-text"
                                        onclick="
                            document.getElementById('procedencia').value='';
                            document.getElementById('id_procedencia').value='';
                        ">

                                        <i class="fas fa-times"></i>

                                    </button>

                                </div>

                                <span id="id_procedenciaError"
                                    class="text-danger small"></span>

                                <div id="listaMunicipiosProcedencia"></div>

                            </div>

                            <!-- Destino -->
                            <div class="col-12 col-md-4">

                                <label class="form-label fw-semibold small">
                                    Destino
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-map-pin"></i>
                                    </span>

                                    <input type="hidden"
                                        id="id_destino"
                                        name="id_destino">

                                    <input type="text"
                                        class="form-control"
                                        id="destino"
                                        name="destino"
                                        placeholder="Ciudad destino"
                                        autocomplete="off"
                                        autocorrect="off"
                                        autocapitalize="words"
                                        spellcheck="false"
                                        onkeyup="buscarCiudadDestino(this.value)">

                                    <button type="button"
                                        class="input-group-text"
                                        onclick="
                            document.getElementById('destino').value='';
                            document.getElementById('id_destino').value='';
                        ">

                                        <i class="fas fa-times"></i>

                                    </button>

                                </div>

                                <span id="id_destinoError"
                                    class="text-danger small"></span>

                                <div id="listaMunicipiosDestino"></div>

                            </div>

                            <!-- =========================
                                FILA 4
                            ========================== -->

                            <!-- Hora de salida -->
                            <div class="col-12 col-md-4">

                                <label class="form-label fw-semibold small">
                                    Hora de salida
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-clock"></i>
                                    </span>

                                    <input type="time"
                                        class="form-control"
                                        name="hora_salida"
                                        id="hora_salida">

                                </div>

                            </div>

                            <!-- Notas -->
                            <div class="col-12 col-md-4">

                                <label class="form-label fw-semibold small">
                                    Notas / Observaciones
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text align-items-start pt-2">
                                        <i class="fas fa-sticky-note"></i>
                                    </span>

                                    <textarea class="form-control"
                                        rows="3"
                                        id="notas_reserva"
                                        name="notas_reserva"
                                        placeholder="Comentarios adicionales de la reserva..."></textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="limpiarReserva()">
                    Cerrar
                </button>

                <button type="button"
                    class="btn btn-outline-success"
                    onclick="checkIn()">
                    Confirmar reserva
                </button>
            </div>
        </div>
    </div>
</div>