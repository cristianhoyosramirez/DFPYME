<div class="container-fluid mt-3">

    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Listado de Vehículos</h6>

            <!-- BOTÓN MODAL -->
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
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Placa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="tablaVehiculos">

                        <!-- EJEMPLO -->
                        <tr>
                            <td>1</td>
                            <td>Carro</td>
                            <td>ABC123</td>
                            <td>
                                <button class="btn btn-warning btn-sm">✏️</button>
                                <button class="btn btn-danger btn-sm">🗑️</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Moto</td>
                            <td>XYZ987</td>
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