<div class="row g-3">
    <?php foreach ($mesas as $index => $mesa): ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card shadow-sm h-100" id="card<?php echo $mesa['id']; ?>">
                <div class="card-body d-flex flex-column justify-content-between">

                    <!-- Nombre editable -->
                    <input type="text"
                        class="form-control text-center mb-3"
                        value="<?php echo  esc($mesa['nombre']) ?>" id="nombreMesa<?php echo $mesa['id']; ?>">

                    <!-- Botones -->
                    <div class="d-flex justify-content-center gap-2">
                        <!-- Eliminar -->
                        <button class="btn btn-icon btn-outline-danger" onclick="eliminarMesa(<?php echo $mesa['id']; ?>)"
                            title="Eliminar Mesa <?= esc($mesa['nombre']) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="10" y1="11" x2="10" y2="17" />
                                <line x1="14" y1="11" x2="14" y2="17" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </button>

                        <!-- Editar -->
                        <button class="btn btn-icon btn-outline-success" onclick="editarMesa(<?php echo $mesa['id']; ?>)"
                            title="Editar <?= esc($mesa['nombre']) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>