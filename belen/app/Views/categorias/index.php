<div class="container py-4">

    <div class="row g-4">

        <!-- FORMULARIO -->
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Crear Categoría</h5>
                </div>

                <form id="formCategoria" class="p-3">

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" id="cat_nombre" class="form-control" placeholder="Ej: Bebidas, Aseo" >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea id="cat_descripcion" class="form-control" rows="3" placeholder="Breve descripción (opcional)"></textarea>
                    </div>

                </form>

                <div class="card-footer d-flex justify-content-end gap-2">
                    <button type="reset" form="formCategoria" class="btn btn-outline-secondary" >Limpiar</button>
                    <button type="submit" form="formCategoria" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>

        <!-- LISTADO -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Categorías Registradas</h5>
                    <button class="btn btn-outline-primary btn-sm" onclick="mostrarCategorias()">
                        <i class="bi bi-arrow-clockwise"></i> Actualizar
                    </button>
                </div>

                <div class="card-body">
                    <div id="listaCategorias" class="list-group">
                        <!-- Se llena por JS -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>





