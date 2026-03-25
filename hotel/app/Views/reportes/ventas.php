<style>
    /* Contenedor del header */
    .header-habitaciones {
        background: #ffffff;
        /* blanco limpio */
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .header-habitaciones:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    /* Botón moderno con gradiente */
    .btn-gradient-primary {
        background: linear-gradient(90deg, #0d6efd, #6610f2);
        border: none;
        color: #fff;
        border-radius: 50px;
        padding: 0.35rem 0.9rem;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .btn-gradient-primary i {
        font-size: 14px;
    }

    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
    }

    .table-responsive thead th {
        position: sticky;
        top: 0;
        background-color: #343a40;
        /* mismo color del table-dark */
        color: white;
        z-index: 10;
    }
</style>

<style>
    /* Contenedor de la lista */
    .ui-autocomplete {
        list-style: none;
        padding: 0;
        margin: 0;
        width: 250px;
        /* un poco más ancho */
        border: 1px solid #ccc;
        border-radius: 8px;
        /* bordes redondeados */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* sombra suave */
        background-color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow: hidden;
        /* para bordes redondeados */
        transition: all 0.2s ease-in-out;
    }

    /* Cada item */
    .ui-autocomplete li {
        padding: 10px 15px;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.1s;
    }

    /* Efecto hover y activo */
    .ui-autocomplete li:hover,
    .ui-autocomplete li.active {
        background-color: #3399ff;
        color: white;
        transform: scale(1.02);
        /* pequeño “zoom” al pasar el mouse */
    }

    /* Separador entre items */
    .ui-autocomplete li+li {
        border-top: 1px solid #eee;
    }

    /* Estilo de texto */
    .ui-autocomplete li span {
        display: block;
        font-size: 14px;
    }

    /* Scroll si hay muchos items */
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
    }

    /* Scrollbar moderno (opcional) */
    .ui-autocomplete::-webkit-scrollbar {
        width: 6px;
    }

    .ui-autocomplete::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 3px;
    }
</style>

<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3 header-habitaciones px-3 py-2 shadow-sm rounded-3">
        <p class="mb-0 fw-bold fs-5 text-dark">
            <i class="fas fa-hotel me-2"></i> Listado de Habitaciones
        </p>
        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalHabitacion">
            <i class="fas fa-plus"></i> Nueva Habitación
        </button>
    </div>
    <div class="card shadow">


        <div class="card-body p-2">

            <div class="row mb-2 g-2">

                <!-- BUSCADOR -->
                <div class="col-md-6">
                    <input
                        type="text"
                        id="buscarHabitacion"
                        class="form-control"
                        placeholder="Buscar habitación...">
                </div>

                <!-- FILTRO ESTADO -->
                <div class="col-md-6">
                    <select id="filtroEstado" class="form-select ">
                        <option value="">Todos los estados</option>
                        <option value="disponible">Disponible</option>
                        <option value="reservada">Reservada</option>
                        <option value="ocupada">Ocupada</option>
                    </select>
                </div>

            </div>

            <!-- TABLA -->
            <div class="table-responsive" style="height: 70vh; overflow-y: auto;">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Habitación</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Estado</th>

                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="tablaHabitaciones">
                        <?= view('habitaciones/habitaciones', ['habitaciones' => $habitaciones]) ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


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
                    <input type="hidden" id="id_de_habitacion" name="id_de_habitacion">

                    <div class="card-body p-4">

                        <!-- BLOQUE 1: Buscar Huésped -->
                        <div class="mb-3 position-relative">
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
                        <input type="text" id="id_cliente" name="id_cliente" hidden>

                        <!-- BLOQUE 2: Detalles del huésped y reserva -->
                        <div class="row g-3">
                            <!-- Nombres completos (readonly) -->


                            <!-- Fecha de reserva -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Fecha reserva</label>
                                <input type="date"
                                    class="form-control"
                                    name="fecha"
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


<!-- Modal Registrar Habitación -->
<div class="modal fade" id="modalHabitacion" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Habitación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formHabitacion">
                    <div class="row g-3">
                        <!-- Nombre -->
                        <div class="col-md-3">
                            <label>Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-door-closed"></i></span>
                                <input type="text" name="numero" class="form-control" placeholder="Nombre de habitación" required>
                            </div>
                        </div>

                        <!-- Tipo -->
                        <div class="col-md-3">
                            <label>Tipo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-bed"></i></span>
                                <select name="tipo" class="form-select" required>
                                    <option value="">Seleccione</option>
                                    <option value="simple">Simple</option>
                                    <option value="doble">Doble</option>
                                    <option value="suite">Suite</option>
                                </select>
                            </div>
                        </div>

                        <!-- Precio -->
                        <div class="col-md-3">
                            <label>Precio</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input type="text" name="precio" class="form-control precio" placeholder="Precio por noche"
                                    onkeypress="return soloNumeros(event)"
                                    onkeyup="formatCurrency(this)" required>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-3">
                            <label>Estado</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                <select name="estado" class="form-select" required>
                                    <?php foreach ($estado_habitaciones as $estado): ?>
                                        <option value="<?= $estado['id'] ?>"><?= $estado['nombre'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-danger" onclick="cancelarHabitacion()">Cancelar</button>
                        <button type="button" class="btn btn-outline-success" onclick="saveHabitacion()" id="btnGuardarHabitacion">Guardar</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>


<!-- Modal confirmar ingreso -->
<div class="modal fade" id="modalConfirmarIngreso" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Check in </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <!-- FORMULARIO -->
                <form id="formRegistroHuesped">
                    <div class="container-fluid">
                        <div class="row g-3">
                            <?= view('habitaciones/registrar') ?>
                        </div>
                    </div>
                </form>

                <!-- BOTONES FUERA PERO ALINEADOS -->
                <div class="container-fluid">
                    <div class="d-flex justify-content-end mt-4 gap-2 flex-wrap border-top pt-3">

                        <button type="reset" form="formRegistroHuesped" class="btn btn-outline-danger btn-sm px-4">
                            Cancelar reserva
                        </button>

                        <button onclick="checkIn()" class="btn btn-outline-success btn-sm px-4 shadow-sm">
                            Confirmar ingreso
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>