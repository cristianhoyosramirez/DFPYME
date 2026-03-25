<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<h1>Dashboard</h1>
<p>Bienvenido al sistema</p>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Productos</h5>
        <a href="/productos/crear" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nuevo producto
        </a>
    </div>

    <div class="card-body">
        <!-- Buscador -->
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Buscar por nombre, código o SKU">
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">Todas las categorías</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Combo Hamburguesa</strong><br>
                            <small class="text-muted">Incluye papas y bebida</small>
                        </td>
                        <td>COM-001</td>
                        <td><span class="badge bg-info">Kit</span></td>
                        <td><span class="badge bg-warning">3</span></td>
                        <td>$18.000</td>
                        <td><span class="badge bg-success">Activo</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <nav class="mt-3">
            <ul class="pagination pagination-sm justify-content-end">
                <li class="page-item active"><a class="page-link">1</a></li>
                <li class="page-item"><a class="page-link">2</a></li>
            </ul>
        </nav>
    </div>
</div>


<?= $this->endSection() ?>