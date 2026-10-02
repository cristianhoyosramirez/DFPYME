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
          <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="id_usuario">
          <div class="row">
            <div class="col-md-3">
              <label for="inputEmail4" class="form-label">Sálon </label>
              <select class="form-select select2" name="salon" id="salon_mesa">
                <?php foreach ($salones as $detalle) { ?>
                  <option value=""></option>
                  <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?> </option>
                <?php } ?>
              </select>
              <div class="text-danger"><?= session('errors.salon') ?></div>
            </div>
            <div class="col-md-3">
              <label for="inputEmail4" class="form-label">Nombre mesa</label>
              <input type="text" class="form-control" name="nombre" autofocus>
              <div class="text-danger"><?= session('errors.nombre') ?></div>
            </div>
            <div class="col-md-4">

              <div class="col-md-6 mb-3">
                <label for="cantidad" class="form-label">
                  ¿Cantidad mesas ?
                </label>

                <input
                  type="number"
                  name="cantidad"
                  id="cantidad"
                  class="form-control">
                <!-- 
                <div class="form-text">
                  Si crea una sola mesa, tendrá el nombre indicado.
                  Si crea varias, se numerarán automáticamente.
                </div> -->
              </div>

              <div class="text-danger"><?= session('errors.cantidad') ?></div>

            </div>
            <!--  <div class="col-md-3">
              <label class="form-label">Imágen de la mesa</label>

              <input
                type="file"
                class="form-control"
                name="imagen"
                accept=".jpg,.jpeg,.png,.webp">

              <small class="text-muted">
                Formatos permitidos: JPG, PNG y WEBP.
              </small>

              <div class="text-danger">
                <?= session('errors.imagen') ?>
              </div>
            </div> -->
          </div>
          <div class="mt-4">
            <button type="submit" class="btn btn-primary w-md"><i class="mdi mdi-plus"></i> Crear mesa</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>



<input type="hidden" id="url" value="<?= base_url() ?>">
<script src="<?= base_url() ?>/Assets/script_js/mesas/eliminarMesa.js"></script>

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