<div class="mb-3">
    <label for="rutaJson" class="form-label fw-semibold">
        <i class="bi bi-folder2-open me-1"></i> Ruta donde están los JSON
    </label>

    <div class="input-group">
        <input
            type="text"
            id="ruta"
            name="ruta"
            class="form-control shadow-sm border-0"
            placeholder="Ejemplo: C:/Users/Cristian/Desktop/json_files/">

        <button class="btn btn-primary" onclick="cargarArchivos()">
            Usar ruta
        </button>
    </div>

    <div id="contenedorArchivos" class="mt-2 text-muted small"></div>
</div>