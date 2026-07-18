<?php $session = session(); ?>
<?= $this->extend('template/salones') ?>

<?= $this->section('title') ?>
LISTADO DE SALONES
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

  <!-- Encabezado -->
  <div class="row align-items-center mb-4">

    <div class="col-auto">
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

    <div class="col">
      <h3 class="text-primary mb-0">
        Administración de Salones
      </h3>
    </div>

    <div class="col-auto">

    </div>

  </div>

  <!-- Breadcrumb -->
  <div class="row mb-4">
    <div class="col">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item active">Salones</li>
          <li class="breadcrumb-item">Mesas</li>
          <li class="breadcrumb-item">Usuarios</li>
          <li class="breadcrumb-item">Empresa</li>
        </ol>
      </nav>
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
            <!-- <a href="<?php echo base_url('mesas/add'); ?>" class="btn btn-warning btn-pill w-100">Agregar mesa</a> -->
            <a href="<?= base_url('salones/datos_iniciales'); ?>" class="btn btn-warning btn-pill">
              <i class="mdi mdi-plus"></i>
              Agregar salón
            </a>
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
                            class="btn btn-primary ">

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
                          class="btn btn-success "
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
          <h5 class="mb-0">
            <i class="mdi mdi-table-furniture"></i>
            Mesas del salón
          </h5>
          <div class="col-lg-auto ms-lg-auto">
            <!-- <a href="<?php echo base_url('mesas/add'); ?>" class="btn btn-warning btn-pill w-100">Agregar mesa</a> -->
            <a href="<?php echo base_url('mesas/add'); ?>" class="btn btn-warning btn-pill w-100">Agregar mesas</a>
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



                </tbody>

              </table>

            </div>
          </div>



        </div>

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