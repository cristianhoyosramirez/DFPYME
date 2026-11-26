<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/salones') ?>

<?= $this->section('title') ?>
LISTADO DE SALONES
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

  <!-- Encabezado -->
  <div class="position-relative d-flex align-items-center mb-4">

    <!-- Botón atrás -->
    <div>
      <a class="nav-link p-0">
        <img
          src="<?= base_url('Assets/img/atras.png') ?>"
          width="20"
          height="20"
          style="cursor:pointer;"
          onclick="history.go(-1);"
          title="Sección anterior">
      </a>
    </div>

    <!-- Título centrado -->
    <h3 class="text-primary mb-0 position-absolute start-50 translate-middle-x">
      Administración de salones y mesas
    </h3>

    <!-- Botón mesas inactivas -->
    <div class="ms-auto">
      <button type="button" class="btn btn-outline-success btn-pill" onclick="mesasInactivas()">
        Mesas inactivas
      </button>
    </div>

  </div>



  <!-- Contenido -->
  <div class="row g-4">

    <!-- Salones -->
    <div class="col-lg-6">

      <div class="card shadow-sm h-100">


        <div class="card-header bg-white text-dark">
          <h5 class="mb-0">
            <i class="mdi mdi-table-furniture"></i>
            Salones
          </h5>
          <div class="col-lg-auto ms-lg-auto">

            <!-- <a href="<?= base_url('salones/datos_iniciales'); ?>" class="btn btn-warning btn-pill">
              <i class="mdi mdi-plus"></i>
              Agregar salón
            </a> -->

            <button type="button" class="btn btn-outline-warning btn-pill" data-bs-toggle="modal" data-bs-target="#AgregarSalon">
              Agregar salón
            </button>
          </div>
        </div>

        <div class="card-body">

          <div class="table-responsive">

            <table class="table table-hover table-bordered align-middle" id="tabla_salones">

              <thead class="table-dark">
                <tr>
                  <td width="60">#</th>
                  <td>Nombre del salón</th>
                  <td width="260">Acciones</th>
                </tr>
              </thead>

              <tbody>

                <?php $i = 1; ?>

                <?php foreach ($salones as $detalle) { ?>

                  <tr>

                    <td><?= $i++; ?></td>

                    <td>
                      <?= esc($detalle['nombre']); ?>
                    </td>

                    <td>

                      <div class="d-flex gap-2">

                        <form action="<?= base_url('salones/edit') ?>" method="POST">

                          <input
                            type="hidden"
                            name="id"
                            value="<?= $detalle['id'] ?>">

                          <button
                            type="submit"
                            class="btn btn-outline-primary ">

                            <i class="mdi mdi-pencil"></i>
                            Editar

                          </button>

                        </form>



                        <input
                          type="hidden"
                          name="id_salon"
                          value="<?= $detalle['id'] ?>">

                        <button
                          type="submit"
                          class="btn btn-outline-success "
                          onclick="mesasSalon(<?= $detalle['id'] ?>)">

                          <i class="mdi mdi-table-furniture"></i>
                          Mesas

                        </button>



                      </div>

                    </td>

                  </tr>

                <?php } ?>

              </tbody>

            </table>

          </div>

        </div>

      </div>

    </div>

    <!-- Mesas -->
    <div class="col-lg-6">

      <div class="card shadow-sm h-100">

        <div class="card-header bg-white text-dark">
          <h5 class="mb-0" id="nombreSalon">

            <?php
            $titulo = $titulo ?? session()->getFlashdata('titulo');
            ?>
            <?= "Mesas" ?>

          </h5>
          <div class="col-lg-auto ms-lg-auto">

            <!-- <a href="<?php echo base_url('mesas/add'); ?>" class="btn btn-warning btn-pill w-100">Agregar mesas</a> -->
            <button type="button" class="btn btn-outline-warning btn-pill" data-bs-toggle="modal" data-bs-target="#agregarMesa">
              Agregar mesas
            </button>
          </div>
        </div>

        <div class="card-body">


          <div style="max-height:500px; overflow-y:auto;">
            <div class="table-responsive">

              <table class="table table-hover table-bordered align-middle" id="tabla_mesas">

                <thead class="table-dark">

                  <tr>

                    <td>Nombre</th>
                    <td width="150">Acciones</th>
                  </tr>

                </thead>

                <tbody id="mesasSalon">

                  <?= $this->include('mesa/tabla_mesas', [
                    'mesas' => $mesas
                  ]) ?>

                </tbody>

              </table>

            </div>
          </div>



        </div>

      </div>

    </div>

  </div>

</div>


<!-- Modal -->
<div class="modal fade" id="AgregarSalon" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Agregar un salon </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('salones/save') ?>" method="POST">
          <div class="row">
            <div class="col-sm-4">
              <label class="form-label required"><b>Nombre</b></label>
              <div class="input-icon">
                <input type="text" class="number form-control form-control" name="nombre" value="<?= old('nombre') ?>" autofocus>
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/building-warehouse -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 21v-13l9 -4l9 4v13" />
                    <path d="M13 13h4v8h-10v-6h6" />
                    <path d="M13 21v-9a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v3" />
                  </svg>
                </span>
              </div>
              </br>
              <div class="text-danger"><?= session('errors.nombre') ?></div>
            </div>

          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary">Crear salon</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="agregarMesa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Agregar Mesas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form action="<?= base_url('mesas/save') ?>" method="POST" enctype="multipart/form-data">

          <input type="hidden" value="<?= $user_session->id_usuario ?>" name="id_usuario">

          <div class="row">

            <div class="col-md-3">
              <label for="salon_mesa" class="form-label">Salón</label>

              <select class="form-select select2" name="salon" id="salon_mesa">
                <option value="">Seleccione un salón</option>

                <?php foreach ($salones as $detalle) { ?>
                  <option value="<?= $detalle['id'] ?>">
                    <?= esc($detalle['nombre']) ?>
                  </option>
                <?php } ?>
              </select>

              <div class="text-danger"><?= session('errors.salon') ?></div>
            </div>

            <div class="col-md-3">
              <label for="nombre" class="form-label">Nombre mesa</label>

              <input
                type="text"
                class="form-control"
                name="nombre"
                id="nombre"
                placeholder="Ej. Terraza"
                autofocus>

              <div class="text-danger"><?= session('errors.nombre') ?></div>
            </div>

            <div class="col-md-3">
              <label for="cantidad" class="form-label">Cantidad de mesas</label>

              <input
                type="number"
                name="cantidad"
                id="cantidad"
                class="form-control"
                min="1"
                placeholder="Ej. 3">

              <div class="text-danger"><?= session('errors.cantidad') ?></div>
            </div>

          </div>

          <!-- Instructivo -->
          <div class="border rounded p-3 mt-4 mb-3">

            <div class="d-flex align-items-center mb-2">
              <i class="mdi mdi-information-outline text-primary fs-4 me-2"></i>
              <h6 class="mb-0 fw-semibold">Instructivo para la creación de mesas</h6>
            </div>

            <p class="text-muted mb-2">
              Complete los campos teniendo en cuenta las siguientes indicaciones:
            </p>

            <ul class="text-muted mb-3 ps-4">
              <li class="mb-1">
                <strong>Salón:</strong> Seleccione el salón donde se crearán las mesas.
              </li>
              <li class="mb-1">
                <strong>Nombre mesa:</strong> Ingrese el nombre o identificador que tendrán las mesas.
              </li>
              <li>
                <strong>Cantidad:</strong> Indique el número de mesas que desea crear.
              </li>
            </ul>

            <div class="bg-light rounded p-2">
              <small class="fw-semibold text-dark">
                <i class="mdi mdi-lightbulb-outline text-warning me-1"></i>
                Ejemplo de creación:
              </small>

              <div class="mt-2">
                <span class="text-muted">Nombre:</span> <strong>Terraza</strong>
                <span class="mx-2 text-muted">|</span>
                <span class="text-muted">Cantidad:</span> <strong>3</strong>
              </div>

              <div class="text-success mt-2">
                <i class="mdi mdi-check-circle-outline me-1"></i>
                Se crearán: Terraza 1, Terraza 2 y Terraza 3.
              </div>
            </div>

          </div>

          <div class="mt-3">
            <button type="submit" class="btn btn-outline-primary">
              <i class="mdi mdi-plus me-1"></i> Crear mesas
            </button>
          </div>

        </form>

      </div>

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="mesas_inactivas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Mesas inactivas </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="todasLasMesas()"></button>
      </div>
      <div class="modal-body">
        <div id="mesasInactivas"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" onclick="todasLasMesas()">Cerrar</button>

      </div>
    </div>
  </div>
</div>


<input type="hidden" id="url" value="<?= base_url() ?>">
<script src="<?= base_url() ?>/Assets/script_js/mesas/eliminarMesa.js"></script>

<script>
  function todasLasMesas() {
    location.reload();
  }
</script>

<script>
  async function activarMesa(id) {

    const resultado = await Swal.fire({
      title: '¿Activar mesa?',
      text: 'La mesa volverá a estar disponible.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, activar',
      cancelButtonText: 'Cancelar',
      reverseButtons: true,
      buttonsStyling: false,
      customClass: {
        actions: 'gap-3',
        confirmButton: 'btn btn-outline-success px-4',
        cancelButton: 'btn btn-outline-danger px-4'
      }
    });

    if (!resultado.isConfirmed) return;

    try {

      Swal.fire({
        title: 'Procesando...',
        text: 'Activando mesa, por favor espere.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading()
      });

      const response = await fetch(`${document.getElementById('url').value}/mesas/activarMesa`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          id: id
        })
      });

      if (!response.ok) {
        throw new Error('Error en la solicitud al servidor.');
      }

      const data = await response.json();

      if (data.status) {

        await Swal.fire({
          icon: 'success',
          title: '¡Mesa activada!',
          text: data.mensaje,
          timer: 1500,
          showConfirmButton: false
        });

        // Remover la fila de la mesa activada
        const mesa = document.getElementById('mesaActivar' + data.id).remove();

        if (mesa) {
          mesa.remove();
          console.log('Mesa eliminada del listado:', data.id);
        }
        //document.getElementById('mesasInactivas').

      } else {

        Swal.fire({
          icon: 'warning',
          title: 'Atención',
          text: data.mensaje
        });

      }

    } catch (error) {

      console.error('Error:', error);

      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No fue posible activar la mesa. Inténtelo nuevamente.'
      });
    }
  }
</script>

<script>
  async function mesasInactivas() {

    try {

      Swal.fire({
        title: 'Consultando mesas...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      const response = await fetch(`${$('#url').val()}/mesas/mesasInactivas`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        }
      });

      const data = await response.json();

      Swal.close();



      document.getElementById('mesasInactivas').innerHTML = data.mesas
      var modalElemento = document.getElementById('mesas_inactivas');
      var myModal = new bootstrap.Modal(modalElemento);
      myModal.show();


    } catch (error) {

      Swal.fire({
        icon: 'error',
        title: 'Atención',
        text: error.message || 'Ocurrió un error al consultar las mesas.'
      });

    }
  }
</script>

<script>
  async function mesasSalon(id_salon) {

    const url = document.getElementById("url").value;

    // Mostrar spinner
    Swal.fire({
      title: 'Cargando mesas...',
      text: 'Por favor espere',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    try {

      const response = await fetch(url + "/salones/listadoMesas", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
          id_salon: id_salon
        })
      });

      if (!response.ok) {
        throw new Error("Error en la respuesta del servidor.");
      }

      const resultado = await response.json();

      // Cerrar spinner
      Swal.close();

      if (resultado.resultado == 1) {

        const contenedor = document.getElementById("mesasSalon");

        if (contenedor) {
          contenedor.innerHTML = resultado.html;
        }

      } else {

        Swal.fire({
          icon: "warning",
          title: "Atención",
          text: resultado.mensaje ?? "No se encontraron mesas."
        });

      }

    } catch (error) {

      console.error(error);

      // Cerrar spinner
      Swal.close();

      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No fue posible cargar las mesas."
      });

    }

  }
</script>

<?= $this->endSection('content') ?>