<?= $this->extend('template/categoria') ?>
<?= $this->section('title') ?>
CATEGORIAS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<input type="hidden" id="url" value="<?= base_url() ?>">


<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">



        <div class="col-lg-auto ms-lg-auto">
            <button type="button"
                class="btn btn-outline-success btn-pill w-100"
                onclick="categoriasInactivas()">
                <i class="mdi mdi-archive-outline"></i> Categorías inactivas
            </button>
        </div>

        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3 mb-0">
                Lista general de categorías de producto
            </p>
        </div>

        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link">
                <img
                    style="cursor:pointer;"
                    src="<?= base_url('Assets/img/atras.png') ?>"
                    width="20"
                    height="20"
                    onclick="history.go(-1);"
                    title="Sección anterior">
            </a>
        </div>

    </div>
</div>
<br>

<div class="card container p-3">
    <br>
    <div class="col-lg-auto ms-lg-auto">
        <a
            class="btn btn-outline-warning btn-pill w-auto px-4"
            data-bs-toggle="modal"
            data-bs-target="#nueva_categoria">
            <i class="mdi mdi-plus"></i> Agregar categoría
        </a>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <thead class="table-dark">
                <tr>
                    <td title="Cambiar el nombre de la impresora "> NOMBRE DE CATEGORIA <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                        <svg xmlns="http://www.w3.org/2000/svg" title="Activar o inactivar" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                            <polyline points="11 12 12 12 12 16 13 16" />
                        </svg>
                    </td>
                    <td title="Actiuvar o desactivar la categoria "> ESTADO
                        <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                        <svg xmlns="http://www.w3.org/2000/svg" title="Activar o inactivar" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                            <polyline points="11 12 12 12 12 16 13 16" />
                        </svg>

                    </td>

                    <!--<td title="Asignar impresora ">IMPRESORA ASIGNADA  Download SVG icon from http://tabler-icons.io/i/info-circle
                        <svg xmlns="http://www.w3.org/2000/svg" title="Activar o inactivar" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                            <polyline points="11 12 12 12 12 16 13 16" />
                        </svg>
                    </td> -->

                    <td>
                        Subcategoria
                    </td>
                    <td>
                        Orden
                    </td>
                    <td>
                        Acción
                    </td>
                </tr>
            </thead>
            <tbody id="tabla_categorias">

                <?php foreach ($categorias as $detalle) { ?>

                    <input type="hidden" name="codigo_categoria" value="<?php echo $detalle['codigocategoria'] ?>">
                    <tr>

                        <td> <input type="text" value="<?php echo $detalle['nombrecategoria'] ?>" class="form-control" onkeyup="actualizar_categoria(event, this.value,<?php echo $detalle['codigocategoria'] ?>)" placeholder="Cambiar el nombre de la categoria "> </td>
                        <td>
                            <select class="form-select" id="estado_categoria" name="estado_categoria" onchange="estado_categoria(this.options[this.selectedIndex].value,<?php echo $detalle['codigocategoria'] ?>)">
                                <option value="true" <?php if ($detalle['permitir_categoria'] == 't') : ?>selected <?php endif; ?>>
                                    <p class="text-success">ACTIVA</p>
                                </option>
                                <option value="false" <?php if ($detalle['permitir_categoria'] == 'f') : ?>selected <?php endif; ?>>
                                    <p class="text-success">INACTIVA</p>
                                </option>
                            </select>
                        </td>
                        <td>

                            <select class="form-select" aria-label="Default select example" onchange="sub_categoria(this.options[this.selectedIndex].value,<?php echo $detalle['codigocategoria'] ?>)">

                                <?php if ($detalle['subcategoria'] == 't') : ?>
                                    <option value="true" selected>Si </option>
                                    <option value="false">No </option>

                                <?php endif ?>
                                <?php if ($detalle['subcategoria'] == 'f') : ?>
                                    <option value="true">Si </option>
                                    <option value="false" selected>No </option>

                                <?php endif ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" value="<?php echo $detalle['orden']; ?>" onkeyup="actualizarOrden(this.value,<?php echo $detalle['id'];  ?>)">
                        </td>
                        <td class="text-end">
                            <button type="button"
                                class="btn btn-outline-danger "
                                onclick="eliminar_categoria(<?php echo $detalle['id']; ?>)">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                    <tr>

                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->include('categoria/modal_nueva_categoria') ?>

<!-- Modal -->
<div class="modal fade" id="categoriasInactivas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Categorias inactivas </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="window.location.reload()"></button>
            </div>
            <div class="modal-body">
                <div id="categorias_inactivas"></div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>


<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>

<script>
    async function activarCategoria(id) {

        try {

            const confirmacion = await Swal.fire({
                title: '¿Activar categoría?',
                text: 'La categoría volverá a estar disponible.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, activar',
                cancelButtonText: 'Cancelar',
                buttonsStyling: false,
                customClass: {
                    actions: 'gap-3',
                    confirmButton: 'btn btn-outline-success px-4',
                    cancelButton: 'btn btn-outline-danger px-4'
                }
            });

            if (!confirmacion.isConfirmed) {
                return;
            }

            Swal.fire({
                title: 'Activando categoría...',
                text: 'Por favor, espera un momento.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            const url = document.getElementById('url').value;

            const respuesta = await fetch(url + '/categoria/activarCategoria', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: id
                })
            });

            const data = await respuesta.json();

            if (!respuesta.ok || !data.status) {
                throw new Error(data.mensaje || 'No fue posible activar la categoría.');
            }

            await Swal.fire({
                icon: 'success',
                title: '¡Activada!',
                text: data.mensaje,
                confirmButtonText: 'Aceptar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-outline-success px-4'
                }
            });

            // Recargar las categorías inactivas
            await categoriasInactivas();

        } catch (error) {

            console.error('Error activando categoría:', error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Ocurrió un error al activar la categoría.',
                confirmButtonText: 'Aceptar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-outline-danger px-4'
                }
            });
        }
    }
</script>

<script>
    async function categoriasInactivas() {

        try {

            Swal.fire({
                title: 'Consultando categorías...',
                text: 'Por favor espere.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            const response = await fetch(`${document.getElementById('url').value}/categoria/categoriasInactivas`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Error en la consulta.');
            }

            const data = await response.json();

            Swal.close();

            if (data.status) {

                document.getElementById('categorias_inactivas').innerHTML = data.categorias;
                const modalElemento = document.getElementById('categoriasInactivas');
                const modal = bootstrap.Modal.getOrCreateInstance(modalElemento);
                modal.show();

                // Aquí estarán disponibles las categorías consultadas.

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
                text: 'No fue posible consultar las categorías inactivas.'
            });

        }
    }
</script>

<script>
    async function eliminar_categoria(id_categoria) {

        const confirmacion = await Swal.fire({
            title: '¿Eliminar categoría?',
            text: 'Esta acción eliminará la categoría seleccionada.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!confirmacion.isConfirmed) {
            return;
        }

        try {

            // Mostrar spinner
            Swal.fire({
                title: 'Eliminando categoría',
                text: 'Por favor, espere...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const url = document.getElementById('url').value;

            const response = await fetch(url + '/categoria/eliminarCategoria', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_categoria: id_categoria
                })
            });

            const resultado = await response.json();

            if (resultado.resultado == 1) {

                await Swal.fire({
                    icon: 'success',
                    title: 'Categoría eliminada',
                    text: resultado.mensaje,
                    confirmButtonText: 'Aceptar'
                });

                location.reload();

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo eliminar',
                    text: resultado.mensaje
                });

            }

        } catch (error) {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible eliminar la categoría.'
            });

            console.error(error);
        }
    }
</script>



<script>
    async function actualizarOrden(valor, id) {

        try {
            // Mensaje inicial (puedes quitarlo si ya no lo necesitas)


            // Ejemplo de petición asíncrona (ajústala a tu backend)
            const response = await fetch('<?= base_url('categoria/actualizarOrden') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    orden: valor,
                    id: id
                })
            });

            const data = await response.json();

            if (data.success == true) {
                sweet_alert_centrado('success', 'Cambio realizado')
            }

        } catch (error) {
            console.error("Error en actualizarOrden:", error);
        }
    }
</script>

<?= $this->endSection('content') ?>