<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Seleccionar Rutas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Seleccionar Carpeta y Archivo</h5>
    </div>
    <div class="card-body">
      <form action="<?= base_url('renombrar') ?>" method="post">

        <!-- Selección Carpeta -->
        <div class="mb-3">
          <label class="form-label fw-bold">Carpeta XML</label>
          <input type="file" id="carpetaXmlInput" webkitdirectory directory multiple hidden>
          <div class="input-group">
            <input type="text" class="form-control" id="carpetaXml" name="carpetaXml" placeholder="Seleccione carpeta..." readonly required>
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('carpetaXmlInput').click()">Buscar</button>
          </div>
        </div>

        <!-- Selección Archivo TXT -->
        <div class="mb-3">
          <label class="form-label fw-bold">Archivo TXT</label>
          <input type="file" id="archivoTxtInput" accept=".txt" hidden>
          <div class="input-group">
            <input type="text" class="form-control" id="rutaTxt" name="rutaTxt" placeholder="Seleccione archivo..." readonly required>
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('archivoTxtInput').click()">Buscar</button>
          </div>
        </div>

        <button type="submit" class="btn btn-success w-100">Guardar y Procesar</button>
      </form>
    </div>
  </div>
</div>

<script>
  // Cuando selecciona carpeta
  document.getElementById('carpetaXmlInput').addEventListener('change', function(e) {
    if (this.files.length > 0) {
      // Obtenemos la ruta relativa de la primera carpeta
      let path = this.files[0].webkitRelativePath.split("/")[0];
      document.getElementById('carpetaXml').value = path;
    }
  });

  // Cuando selecciona archivo TXT
  document.getElementById('archivoTxtInput').addEventListener('change', function(e) {
    if (this.files.length > 0) {
      document.getElementById('rutaTxt').value = this.files[0].name; // Solo el nombre
    }
  });
</script>

</body>
</html>
