<?php
$grupos = model('grupoImpresionModel')->orderBy('id', 'asc')->findAll();
$impresoras = model('impresorasModel')->findAll();

?>
<div class="d-flex justify-content-end mb-2">
    <button type="button" class="btn btn-outline-orange" data-bs-toggle="modal" data-bs-target="#newGroup">
        Nuevo grupo
    </button>
</div>

<p class="text-center text-primary h3">Grupo de impresión</p>

<table class="table">
    <thead class="table-dark">
        <tr>
            <td scope="col">Nombre grupo</th>
            <td scope="col">Impresora asignada</th>
            <td scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody id="gruposImpresion">
        <?php foreach ($grupos as $detalleGrupos):  ?>
            <tr id="grupo<?php echo $detalleGrupos['id'] ?>">
                <td><input type="text" class="form-control" value="<?php echo $detalleGrupos['nombre']; ?>" id="nombre<?php echo $detalleGrupos['id'] ?>"></td>
                <td>
                    <select name="" class="form-select" id="impresora<?php echo $detalleGrupos['id'] ?>">
                        <?php foreach ($impresoras as $detalleImpresoras): ?>
                            <option value="<?php echo $detalleImpresoras['id']; ?>"
                                <?php if ($detalleImpresoras['id'] == $detalleGrupos['id_impresora_asignada']) echo 'selected'; ?>>
                                <?php echo $detalleImpresoras['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-success btn-icon" data-bs-toggle="tooltip" title="Actualizar" data-bs-placement="bottom" onclick="actualizarGrupo(<?php echo $detalleGrupos['id'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/refresh -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                            </svg></button>
                        <button onclick="eliminacioGrupo(<?php echo $detalleGrupos['id'] ?>)" class="btn btn-outline-danger btn-icon" data-bs-toggle="tooltip" title="Eliminar" data-bs-placement="bottom"><!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="10" y1="11" x2="10" y2="17" />
                                <line x1="14" y1="11" x2="14" y2="17" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg></button>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<!-- Modal -->
<div class="modal fade" id="newGroup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Nuevo grupo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreGrupo" oninput="limpiarSpan()">
                        <span id="errorNombre" class="text-danger"></span>
                    </div>
                    <div class="col">
                        <label for="" class="form-label">Asignar impresora</label>
                        <?php $impresoras = model('impresorasModel')->findAll();  ?>
                        <select name="" class="form-select" id="idImpresora">
                            <?php foreach ($impresoras as $detalleImpreoras): ?>
                                <option value="<?php echo $detalleImpreoras['id']; ?>"><?php echo $detalleImpreoras['nombre']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" onclick="generarGrupo()">Guardar </button>
                <button type="button" class="btn btn-outline-danger">Cancelar </button>
            </div>
        </div>
    </div>
</div>