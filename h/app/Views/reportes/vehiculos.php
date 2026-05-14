<div class="container-fluid mt-3">

    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Listado de Vehículos</h6>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalVehiculo">
                + Nuevo Vehículo
            </button>
        </div>

        <div class="card-body p-2">

            <!-- BUSCADOR -->
            <div class="mb-2">
                <input type="text" class="form-control form-control-sm" placeholder="Buscar vehículo...">
            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Tipo</th>
                            <th>Placa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="tablaVehiculos">
                        <?php foreach ($vehiculos as $vehiculo): ?>
                            <tr>
                                <td>

                                    <select
                                        class="form-select"
                                        id="tipo"
                                        name="tipo"
                                        onchange="actualizarTipoVehiculo(this.value, <?= $vehiculo['id'] ?>)"
                                        required>

                                        <option value="" disabled <?= empty($vehiculo['tipo']) ? 'selected' : '' ?>>
                                            Seleccione un tipo
                                        </option>

                                        <option value="Carro" <?= ($vehiculo['tipo'] == 'Carro') ? 'selected' : '' ?>>
                                            Carro
                                        </option>

                                        <option value="Moto" <?= ($vehiculo['tipo'] == 'Moto') ? 'selected' : '' ?>>
                                            Moto
                                        </option>

                                        <option value="Camioneta" <?= ($vehiculo['tipo'] == 'Camioneta') ? 'selected' : '' ?>>
                                            Camioneta
                                        </option>

                                        <option value="Camión" <?= ($vehiculo['tipo'] == 'Camión') ? 'selected' : '' ?>>
                                            Camión
                                        </option>

                                        <option value="Bus" <?= ($vehiculo['tipo'] == 'Bus') ? 'selected' : '' ?>>
                                            Bus
                                        </option>

                                        <option value="Tractomula" <?= ($vehiculo['tipo'] == 'Tractomula') ? 'selected' : '' ?>>
                                            Tractomula
                                        </option>

                                        <option value="Doble troque" <?= ($vehiculo['tipo'] == 'Doble troque') ? 'selected' : '' ?>>
                                            Doble troque
                                        </option>

                                        <option value="Tractor" <?= ($vehiculo['tipo'] == 'Tractor') ? 'selected' : '' ?>>
                                            Tractor
                                        </option>

                                        <option value="No aplica" <?= ($vehiculo['tipo'] == 'No aplica') ? 'selected' : '' ?>>
                                            No aplica
                                        </option>

                                    </select>

                                </td>
                                <td><input
                                        type="text"
                                        class="form-control"
                                        id="placa"
                                        name="placa"
                                        value="<?= $vehiculo['placa'] ?>"
                                        placeholder="Ej: ABC-123"
                                        required
                                        oninput="this.value = this.value.toUpperCase()"
                                        onchange="actualizarPlaca(this.value, <?= $vehiculo['id'] ?>)"></td>

                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>


<!-- MODAL CREAR VEHÍCULO -->
<div class="modal fade" id="modalVehiculo" tabindex="-1" aria-labelledby="modalVehiculoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalVehiculoLabel">Nuevo Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formVehiculo" method="POST">
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="" selected disabled>Seleccione un tipo</option>
                            <option value="Carro">Carro</option>
                            <option value="Moto">Moto</option>
                            <option value="Camioneta">Camioneta</option>
                            <option value="Camión">Camión</option>
                            <option value="Bus">Bus</option>
                            <option value="Tractomula">Tractomula</option>
                            <option value="Doble troque">Doble troque</option>
                            <option value="Tractor">Tractor</option>
                            <option value="No aplica">No aplica</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="placa" class="form-label">Placa</label>
                        <input
                            type="text"
                            class="form-control"
                            id="placa"
                            name="placa"
                            placeholder="Ej: ABC-123"
                            required
                            oninput="this.value = this.value.toUpperCase()">
                    </div>

                    <div class="text-end">
                        <!-- Aquí el cambio -->
                        <button type="button" class="btn btn-primary" onclick="agregarVehiculo()">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>