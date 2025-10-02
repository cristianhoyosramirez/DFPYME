<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
TABLA CONSECUTIVOS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>

<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">
      <button type="button" class="btn btn-outline-orange" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Agregar consecutivo
      </button>
    </div>
  </div>

</div>
<br>
<!--Sart row-->
<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">

    </div>

    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">Tabla de consecutivos </p>
    </div>
    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
      <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
    </div>
  </div>
</div>
<br>
<div class="container">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="mesas" class="table  table-hover table-borderless">
            <thead class="table-dark">
              <tr>
                <td>Código</td>
                <td>Nombre de campo</td>
                <td>consecutivo</td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($consecutivos as $detalle) { ?>
                <tr>
                  <td>



                    <input type="text" value="<?php echo $detalle['idconsecutivos'] ?>" class="form-control" oninput="actualizarSerieConsecutivo(this.value)">

                  </td>
                  <td>
                    <input type="text" class="form-control"
                      value="<?php echo $detalle['conceptoconsecutivo'] ?>"
                      oninput="actualizarConceptoConsecutivo(this.value, <?php echo $detalle['idconsecutivos'] ?>)">
                  </td>
                  <td><input type="text" class="form-control" value="<?php echo $detalle['numeroconsecutivo'] ?>" onkeyup="actualizar_consecutivos(event, this.value,<?php echo $detalle['idconsecutivos'] ?>)"></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar consecutivo </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="row">

          <div class="col">
            <label for="" class="form-label">Concepto consecutivo</label>
            <input type="text" class="form-control" id="conceptoConsecutivo">
          </div>
          <div class="col">
            <label for="" class="form-label">Valor</label>
            <input type="text" class="form-control" id="valorConsecutivo">
          </div>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" onclick="crearConsecutivo()">Guardar </button>
        <button type="button" class="btn btn-outline-danger">Cancelar </button>
      </div>
    </div>
  </div>
</div>

<script>
  async function actualizarConceptoConsecutivo(nombre, id) {
    try {
      const response = await fetch('<?php echo base_url(); ?>/empresa/actualizarNombreConsecutivo', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id: id,
          nombre: nombre
        })
      });

      const data = await response.json();

      if (!response.ok) {
        console.error('Error al actualizar:', data.mensaje || 'Error desconocido');
      } else {
        console.log('Actualización exitosa:', data);
      }
    } catch (error) {
      console.error('Error de red:', error);
    }
  }
</script>
<script>
  async function crearConsecutivo() {
    try {
      let concepto = document.getElementById('conceptoConsecutivo').value.trim();
      let valor = document.getElementById('valorConsecutivo').value.trim();

      // Validación: ambos campos son obligatorios
      if (!concepto || !valor) {
        alert('Por favor complete todos los campos: concepto y valor.');
        return; // Salir de la función si hay campos vacíos
      }

      const response = await fetch('<?= base_url(); ?>/empresa/crearConsecutivo', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          concepto: concepto,
          valor: valor
        })
      });

      const data = await response.json();

      if (data.response === "success") {
        alert('Registro insertado');
        location.reload(); 
      }

      if (!response.ok) {
        console.error('Error al actualizar:', data.mensaje || 'Error desconocido');
      } else {
        console.log('Actualización exitosa:', data);
      }
    } catch (error) {
      console.error('Error de red:', error);
    }
  }
</script>


<script>
  async function actualizarSerieConsecutivo(valor) {
    try {
      const response = await fetch('<?php echo base_url(); ?>/empresa/actualizarSerieConsecutivo', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          valor: valor
        })
      });

      const data = await response.json();

      if (!response.ok) {
        console.error('Error al actualizar:', data.mensaje || 'Error desconocido');
      } else {
        console.log('Actualización exitosa:', data);
      }
    } catch (error) {
      console.error('Error de red:', error);
    }
  }
</script>
<?= $this->endSection('content') ?>
<!-- end row -->