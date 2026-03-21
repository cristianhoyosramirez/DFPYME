<div class="container-fluid mt-3">

    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Listado de Habitaciones</h6>

            <!-- BOTÓN MODAL -->
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalHabitacion">
                + Nueva Habitación
            </button>
        </div>

        <div class="card-body p-2">

            <!-- BUSCADOR -->
            <div class="mb-2">
                <input type="text" class="form-control form-control-sm" placeholder="Buscar habitación...">
            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Capacidad</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Piso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="tablaHabitaciones">

                        <!-- EJEMPLO -->
                        <tr>
                            <td>101</td>
                            <td>Simple</td>
                            <td>2</td>
                            <td>$50.000</td>
                            <td><span class="badge bg-success">Disponible</span></td>
                            <td>1</td>
                            <td>
                                <button class="btn btn-warning btn-sm">✏️</button>
                                <button class="btn btn-danger btn-sm">🗑️</button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>


<div class="modal fade" id="modalHabitacion" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Habitación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formHabitacion">

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label>Número</label>
                            <input type="text" name="numero" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Tipo</label>
                            <select name="tipo" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="simple">Simple</option>
                                <option value="doble">Doble</option>
                                <option value="suite">Suite</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Capacidad</label>
                            <input type="number" name="capacidad" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" name="precio" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Estado</label>
                            <select name="estado" class="form-control">
                                <option value="disponible">Disponible</option>
                                <option value="ocupada">Ocupada</option>
                                <option value="reservada">Reservada</option>
                                <option value="mantenimiento">Mantenimiento</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Piso</label>
                            <input type="number" name="piso" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control"></textarea>
                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" id="btnGuardarHabitacion">Guardar</button>
            </div>

        </div>
    </div>
</div>