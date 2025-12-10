<form action="<?= base_url('renombrar') ?>" method="post" class="p-3">

  <div class="row g-3">

    <!-- Selección Carpeta XML -->
    <div class="col-12">
      <label class="form-label fw-bold">Carpeta XML</label>

      <input type="file" id="carpetaXmlInput" webkitdirectory directory multiple hidden>

      <div class="input-group shadow-sm">
        <span class="input-group-text">
          <i class="bi bi-folder2-open"></i>
        </span>

        <input type="text"
               class="form-control"
               id="carpetaXml"
               name="carpetaXml"
               placeholder="Seleccione una carpeta..."
               readonly required>

        <button type="button"
                class="btn btn-outline-primary"
                onclick="document.getElementById('carpetaXmlInput').click()">
          Buscar
        </button>
      </div>
    </div>

    <!-- Selección Archivo TXT -->
    <div class="col-12">
      <label class="form-label fw-bold mt-2">Archivo TXT</label>

      <input type="file" id="archivoTxtInput" accept=".txt" hidden>

      <div class="input-group shadow-sm">
        <span class="input-group-text">
          <i class="bi bi-filetype-txt"></i>
        </span>

        <input type="text"
               class="form-control"
               id="rutaTxt"
               name="rutaTxt"
               placeholder="Seleccione un archivo..."
               readonly required>

        <button type="button"
                class="btn btn-outline-primary"
                onclick="document.getElementById('archivoTxtInput').click()">
          Buscar
        </button>
      </div>
    </div>

    <!-- Botón procesar -->
    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-success w-100 py-2">
        <i class="bi bi-check-circle"></i> Guardar y Procesar
      </button>
    </div>

  </div>

</form>
