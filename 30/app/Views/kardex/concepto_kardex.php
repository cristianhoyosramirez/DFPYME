
<div class="d-flex justify-content-end mb-3">
    <button
        type="button"
        class="btn btn-outline-primary"
        onclick="agregarConcepto()">
        <i class="bi bi-plus-circle me-1"></i>
        Agregar concepto
    </button>
</div>

<table class="table">
    <thead class="table-dark">
        <tr>
            <td scope="col">Nombre</th>
            <td scope="col">Tipo</th>
            <td scope="col">Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datos as $detalle): ?>
            <tr>
                <td><?= esc($detalle['nombre']) ?></td>
                <td>
                    <?php
                    if ($detalle['idoperacion'] == 1) {
                        echo "Entrada";
                    } elseif ($detalle['idoperacion'] == 2) {
                        echo "Salida";
                    }
                    ?>
                </td>
                <td id="filaBotones<?= $detalle['id'] ?>">
                    <?php if ($detalle['estado'] == true): ?>
                        <button
                            type="button"
    
                            class="btn btn-outline-success"
                            onclick="cambiarEstado(<?= $detalle['id'] ?>, 0)">
                            Activo
                        </button>
                    <?php elseif ($detalle['estado'] == false): ?>
                        <button
                            type="button"
                            class="btn btn-outline-danger"
                            onclick="cambiarEstado(<?= $detalle['id'] ?>, 1)">
                            Inactivo
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
