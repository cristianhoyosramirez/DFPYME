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
                            <th>Habitación</th>
                            <th>Tipo</th>
                            <th>Capacidad</th>
                            <th>Precio</th>
                            <th>Estado</th>
                      
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="tablaHabitaciones">
                        <?php foreach ($habitaciones as $habitacion): ?>
                            <tr>
                                <td><?= $habitacion['nombre_mesa'] ?></td>
                                <td><?= $habitacion['tipo'] ?></td>
                                <td><?= $habitacion['capacidad'] ?></td>
                                <td><?= number_format($habitacion['precio'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-success">Disponible</span>
                                </td>
                                
                                <td>
                                    <!-- Reservar -->
                                    <button class="btn btn-primary btn-sm" title="Reservar habitación" onclick="reservar(<?= $habitacion['id_mesa'] ?>)">
                                        <i class="fas fa-bed"></i>
                                    </button>

                                    <!-- Editar -->
                                    <button class="btn btn-warning btn-sm" title="Editar habitación" onclick="editar(<?= $habitacion['id_mesa'] ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Eliminar -->
                                    <button class="btn btn-danger btn-sm" title="Eliminar habitación" onclick="eliminar(<?= $habitacion['id_mesa'] ?>)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <!-- Ver detalles -->
                                    <button class="btn btn-info btn-sm" title="Ver detalles de la habitación" onclick="verDetalles(<?= $habitacion['id_mesa'] ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
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
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Número</label>
                            <input type="text" name="numero" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Tipo</label>
                            <select name="tipo" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="simple">Simple</option>
                                <option value="doble">Doble</option>
                                <option value="suite">Suite</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Capacidad</label>
                            <input type="number" name="capacidad" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" name="precio" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Estado</label>
                            <select name="estado" class="form-control" required>
                                <option value="disponible">Disponible</option>
                                <option value="ocupada">Ocupada</option>
                                <option value="reservada">Reservada</option>
                                <option value="mantenimiento">Mantenimiento</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-success" onclick="saveHabitacion()" id="btnGuardarHabitacion">Guardar</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>