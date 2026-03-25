<div class="container-fluid py-3" style="height: calc(100vh - 70px);">

    <div class="row g-3 h-100">

        <!-- FORMULARIO -->
        <div class="col-12 col-xl-4 h-100">
            <div class="card shadow-sm h-100 d-flex flex-column">

                <!-- 🔒 TÍTULO FIJO -->
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Producto</h5>
                </div>

                <!-- 📜 FORMULARIO CON SCROLL -->
                  <form id="formProducto" class="p-3 flex-grow-1 overflow-auto">

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" id="pro_nombre" class="form-control">
                    </div>

                    <!-- Tipo -->
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select id="pro_tipo" class="form-select" data-url="<?= base_url('productos/bloque-compuesto') ?>">
                            <option value="simple">Producto sencillo</option>
                            <option value="compuesto">Producto compuesto</option>
                        </select>
                    </div>

                    <!-- Códigos -->
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">SKU</label>
                            <input type="text" id="pro_sku" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Código de barras</label>
                            <input type="text" id="pro_barra" class="form-control">
                        </div>
                    </div>

                    <!-- Categoría / Marca -->
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <label class="form-label">Categoría</label>
                            <select id="pro_categoria" class="form-select"></select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Marca</label>
                            <input type="text" id="pro_marca" class="form-control">
                        </div>
                    </div>

                    <!-- Inventario -->
                    <hr>
                    <h6 class="text-muted">Inventario</h6>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Unidad</label>
                            <select id="pro_unidad" class="form-select">
                                <option>Unidad</option>
                                <option>Kg</option>
                                <option>Litro</option>
                                <option>Caja</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Maneja inventario</label>
                            <select id="pro_stock_control" class="form-select">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <label class="form-label">Stock inicial</label>
                            <input type="number" id="pro_stock" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Stock mínimo</label>
                            <input type="number" id="pro_stock_min" class="form-control">
                        </div>
                    </div>

                    <!-- Precios -->
                    <hr>
                    <h6 class="text-muted">Precios</h6>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Costo</label>
                            <input type="number" id="pro_costo" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Precio venta</label>
                            <input type="number" id="pro_precio" class="form-control">
                        </div>
                    </div>

                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <label class="form-label">IVA %</label>
                            <input type="number" id="pro_iva" class="form-control" value="19">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Estado</label>
                            <select id="pro_estado" class="form-select">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <!-- COMPONENTES -->
                    <div id="bloqueComponentes" class="d-none mt-3">
                        <hr>
                        <h6 class="text-muted">Componentes</h6>
                        <div id="componentesList"></div>
                        <button type="button" id="btnAddComponente" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="bi bi-plus"></i> Agregar componente
                        </button>
                    </div>

                </form>

                <!-- FOOTER FIJO -->
                <div class="card-footer bg-white d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

            </div>
        </div>

        <!-- LISTADO -->
        <div class="col-12 col-xl-8 h-100">
            <div class="card shadow-sm h-100 d-flex flex-column">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Productos registrados</h5>
                </div>

                <!-- 📜 LISTADO CON SCROLL -->
                <div class="card-body overflow-auto" id="listaProductos">

                    <!-- productos de prueba -->

                </div>
            </div>
        </div>

    </div>
</div>
