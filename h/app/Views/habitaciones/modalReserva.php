<div class="modal fade" id="modalReserva" tabindex="-1">
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

                       
                    <!--     <div class="mb-3 position-relative">
                            <label class="form-label fw-semibold small">Huésped</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text"
                                    class="form-control"
                                    name="huesped"
                                    id="huesped"
                                    placeholder="Buscar por nombre o apellidos"
                                    autocomplete="off"
                                    onkeyup="buscarCliente(event,this.value)"
                                    autofocus>
                                <button type="button" class="btn btn-outline-secondary" onclick="limpiarHuesped()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>


                            <div id="listaClientes"></div>



                        </div>


                        <div class="col-12 col-md-12">
                            <label class="form-label fw-semibold small">Nombres y apellidos</label>
                            <input type="text"
                                class="form-control"
                                name="nombre_completo"
                                placeholder="Nombre completo del huésped"
                                id="nombre_completo"
                                readonly>
                        </div>
                        <input type="text" id="id_cliente" name="id_cliente" hidden> -->

                        
                        <div class="row g-3">
                            <!-- Nombres completos (readonly) -->


                            <!-- Fecha de reserva -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Fecha reserva</label>
                                <input type="date"
                                    class="form-control"
                                    name="fecha"
                                    id="fecha"
                                    value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>

                            <!-- Habitación -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Habitación</label>
                                <input type="text"
                                    class="form-control bg-light"
                                    name="nombre_habitacion"
                                    id="nombre_habitacion"
                                    readonly>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
                            </div>
                        </div>

                        <!-- BLOQUE 3: Botones -->
                        <div class="d-flex justify-content-end mt-4 gap-2 flex-wrap">
                            <button type="reset" class="btn btn-outline-danger px-4 border" onclick="cerrarModalHuesped()">
                                <i class="fas fa-eraser me-1"></i> Cancelar
                            </button>

                            <button type="button" class="btn btn-outline-success px-4 shadow-sm" onclick="guardarReserva()">
                                <i class="fas fa-save me-1"></i> Reservar
                            </button>

                        </div>
                    </div>
                </form>



            </div>

        </div>
    </div>
</div>