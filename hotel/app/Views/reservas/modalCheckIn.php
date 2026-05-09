<div class="modal fade" id="modalCheckIn" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Confirmar Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistroHuesped">
                    <!-- ID oculto de la habitación -->
                    <input type="text" id="id_de_habitacion" name="id_de_habitacion" hidden>

                    <div class="card-body p-4">

                        <div class="mb-3 position-relative">
                            <label class="form-label fw-semibold small">Huéspedddd</label>
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
                        <input type="text" id="id_cliente" name="id_cliente" hidden>
                        <input type="text" id="id_reserva_edicion" name="id_reserva_edicion" hidden>

                        <span class="text-danger" id="faltaCliente"></span>

                        <!-- BLOQUE 2: Detalles del huésped y reserva -->
                        <div class="row g-3">
                            <!-- Nombres completos (readonly) -->


                            <!-- Fecha de reserva -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Fecha reserva</label>
                                <input type="text"
                                    class="form-control"
                                    name="fecha"
                                    id="fecha_edicion"
                                    value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>

                            <!-- Habitación -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Habitación</label>
                                <input type="text"
                                    class="form-control bg-light"
                                    name="nombre_habitacion_edicion"
                                    id="nombre_habitacion_edicion"
                                    readonly>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Observaciones</label>
                                <textarea name="observaciones" id="observaciones_edicion" class="form-control"></textarea>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                    Cerrar
                </button>

                <button type="button"
                    class="btn btn-outline-success"
                    onclick="procesar_confirmacion()">
                    Confirmar reserva
                </button>
            </div>
        </div>
    </div>
</div>