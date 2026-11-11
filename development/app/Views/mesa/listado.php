<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
LISTADO DE MESAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>

<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Salones</a></li>
          <li class="breadcrumb-item"><a href="#">Mesas</a></li>
          <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
          <li class="breadcrumb-item"><a href="#">Empresa</a></li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<br>
<!--Sart row-->
<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">
      <!-- <a href="<?php echo base_url('mesas/add'); ?>" class="btn btn-warning btn-pill w-100">Agregar mesa</a> -->
      <a href="<?php echo base_url('mesas/add'); ?>" class="btn btn-warning btn-pill w-100">Agregar mesas</a>
    </div>

    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">Lista de mesas  </p>
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

          <?php
          $salon_actual = '';

        

          foreach ($mesas as $detalle) {

            // Cuando cambia el salón
            if ($salon_actual != $detalle['nombre_salon']) {

              // Cierra tabla anterior
              if ($salon_actual != '') {
                echo '</tbody></table><br>';
              }

              $salon_actual = $detalle['nombre_salon'];
          ?>

              <div class="card mb-3">

                <div class="card-header bg-dark text-white">
                  <h5 class="m-0">
                    <?= $detalle['nombre_salon'] ?>
                  </h5>
                </div>

                <table class="table table-hover m-0">

                  <thead class="table-light">
                    <tr>
                      <th>Mesa</th>
                      <th width="250">Acciones</th>
                    </tr>
                  </thead>

                  <tbody>

                  <?php } ?>

                  <tr>

                    <td>
                      <?= $detalle['nombre_mesas'] ?>
                    </td>

                    <td>
                      <div class="d-flex">

                        <form action="<?= base_url('mesas/editar') ?>" method="POST">

                          <input type="hidden"
                            value="<?= $detalle['id'] ?>"
                            name="id">

                          <button type="submit"
                            class="btn btn-primary ">

                            Editar
                          </button>
                        </form>

                        &nbsp;

                        <button type="submit"
                          class="btn btn-danger " onclick="eliminarMesa(<?= $detalle['id'] ?>)">
                          Eliminar
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
  </div>
</div>
</div>

<script src="<?= base_url() ?>/Assets/script_js/mesas/eliminarMesa.js"></script>


<!-- <script>
  async function eliminarMesa(id) {

    const confirmar = await Swal.fire({
        title: '¿Eliminar mesa?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (!confirmar.isConfirmed) {
        return;
    }

    try {

        const response = await fetch("<?= base_url('mesas/eliminarMesa') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: id
            })
        });

        const data = await response.json();

        if (data.status) {

            await Swal.fire({
                icon: 'success',
                title: 'Mesa eliminada correctamente',
                timer: 1500,
                showConfirmButton: false
            });

            location.reload();

        } else {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.mensaje
            });

        }

    } catch (error) {

        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error del sistema',
            text: 'No fue posible eliminar la mesa'
        });

    }
}
</script> -->


<?= $this->endSection('content') ?>
<!-- end row -->