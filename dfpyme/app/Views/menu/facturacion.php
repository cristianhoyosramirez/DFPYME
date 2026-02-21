<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/facturacion') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container my-4">
    <p class="text-center h3 text-primary mb-3">
        Configuración de facturación
    </p>




    <div class="container">
        <div class="row">
            <!-- Columna izquierda: configuración facturar en cero -->
            <div class="col-6">
                <label for="facturar_cero" class="form-label">
                    Permitir incluir productos en cero en la factura:
                </label>
                <select id="facturar_cero"
                    class="form-select mb-3"
                    onchange="facturar_cero(this.value)">
                    <option value="t" <?= ($configuracion == 't') ? 'selected' : '' ?>>
                        Sí
                    </option>
                    <option value="f" <?= ($configuracion == 'f') ? 'selected' : '' ?>>
                        No
                    </option>
                </select>
            </div>

            <!-- Columna derecha: tabla de medios de pago electrónicos -->
            <div class="col-6">
                <label class="form-label">Medios de pago electrónicos:</label>
                <div class="d-flex justify-content-end mb-2">
                    <button class="btn btn-outline-success" onclick="abrirModal()">Agregar medio de pago</button>
                </div>
                <table class="table  table-striped">
                    <thead class="table-dark">
                        <tr>
                            <td>#</th>
                            <td>Nombre</th>

                            <td>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="medios_pago_body">

                        <?php if (!empty($clase_pago)) : ?>
                            <?php foreach ($clase_pago as $index => $medio) : ?>
                                <tr id="remover<?php echo $medio['id']; ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td><input type="text" value="<?= $medio['nombre'] ?>" class="form-control" id="medio<?php echo $medio['id']; ?>"> </td>
                                    <td class="d-flex justify-content-end gap-1">
                                        <button class="btn btn-outline-success" onclick="actualializarMedio(<?php echo $medio['id']; ?>)">Editar</button>
                                        <button class="btn btn-outline-danger" onclick="eliminarMedio(<?php echo $medio['id']; ?>,'<?php echo $medio['nombre']; ?>')">Eliminar</button>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay medios registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalMedios" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Agregar medios de pago </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col"><label for="" class="form-label">Nombre medio</label>
                        <input type="text" class="form-control" id="nombreMedio">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" onclick="guardarMedio()">Guardar </button>
                <button type="button" class="btn btn-outline-danger">Cancelar</button>
            </div>
        </div>
    </div>
</div>




<script>
    function abrirModal() {

        var miModal = new bootstrap.Modal(document.getElementById('modalMedios'), {
            backdrop: 'static', // opcional: evita cerrar al hacer click fuera
            keyboard: false // opcional: evita cerrar con ESC
        });

        // Abrir el modal
        miModal.show();

    }
</script>

<script>
    async function eliminarMedio(id, nombre) {
        Swal.fire({
                title: '¿Estás seguro?',
                text: "Este medio de pago se eliminará.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                focusConfirm: true, // Foco en "Sí, eliminar"
                customClass: {
                    confirmButton: 'btn btn-outline-success me-2', // outline verde Bootstrap
                    cancelButton: 'btn btn-outline-danger' // outline rojo Bootstrap
                },
                buttonsStyling: false // importante: usar clases Bootstrap sin estilos SweetAlert
            })
            .then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        let response = await fetch("<?= base_url('edicion_eliminacion_factura_pedido/eliminarClasePago') ?>", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        });

                        let data = await response.json();

                        if (data.response === "success") {
                            sweet_alert_centrado('success', 'Elemento eliminado');

                            // Remover la fila de la tabla
                            let fila = document.getElementById("remover" + id);
                            if (fila) fila.remove();
                        } else {
                            sweet_alert_centrado('error', 'Error al eliminar ❌');
                        }
                    } catch (error) {
                        console.error("Error en la petición:", error);
                        sweet_alert_centrado('error', 'No se pudo eliminar ❌');
                    }
                }
            });
    }
</script>



<script>
    async function actualializarMedio(id) {
        try {
            nombre = document.getElementById('medio' + id).value
            let response = await fetch("<?= base_url('edicion_eliminacion_factura_pedido/actualizarClasePago') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
                },
                body: JSON.stringify({
                    nombre: nombre,
                    id: id
                })
            });

            let data = await response.json();

            if (data.response === "success") {
                sweet_alert_centrado('success', 'Configuración guardada');

                // limpiar el tbody

            } else {
                alert("Error al guardar la configuración ❌");
            }
        } catch (error) {
            console.error("Error en la petición:", error);
            alert("No se pudo guardar la configuración ❌");
        }
    }
</script>
<script>
    async function guardarMedio() {
        try {
            nombre = document.getElementById('nombreMedio').value
            let response = await fetch("<?= base_url('edicion_eliminacion_factura_pedido/guardarMedioPago') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
                },
                body: JSON.stringify({
                    nombre: nombre,

                })
            });

            let data = await response.json();

            if (data.response === "success") {
                sweet_alert_centrado('success', 'Medio de pago adicionado')


               

                $('#modalMedios').modal('hide')

      


                let tbody = document.getElementById("medios_pago_body");
                tbody.innerHTML = "";

                // recorrer los medios de pago
                data.clase_pago.forEach((medio, index) => {
                    let tr = document.createElement("tr");

                    tr.innerHTML = `
            <td>${index + 1}</td>
            <td>
                <input type="text" value="${medio.nombre}" class="form-control" id="medio${medio.id}">
            </td>
            <td class="d-flex justify-content-end gap-1">
                <button class="btn btn-outline-success" onclick="actualializarMedio(${medio.id})">Editar</button>
                <button class="btn btn-outline-danger" onclick="eliminarMedio(<?= $medio['id'] ?>, '<?= $medio['nombre'] ?>')">Eliminar</button>

            </td>
        `;

                    tbody.appendChild(tr);
                });
            } else {
                alert("Error al guardar la configuración ❌");
            }
        } catch (error) {
            console.error("Error en la petición:", error);
            alert("No se pudo guardar la configuración ❌");
        }
    }
</script>

<script>
    async function facturar_cero(valor) {
        try {
            let response = await fetch("<?= base_url('edicion_eliminacion_factura_pedido/updateFacturarCero') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
                },
                body: JSON.stringify({
                    facturar_cero: valor
                })
            });

            let data = await response.json();

            if (data.response === "success") {
                sweet_alert_centrado('success', 'Configuracion guardada')
            } else {
                alert("Error al guardar la configuración ❌");
            }
        } catch (error) {
            console.error("Error en la petición:", error);
            alert("No se pudo guardar la configuración ❌");
        }
    }
</script>

<?= $this->endSection('content') ?>