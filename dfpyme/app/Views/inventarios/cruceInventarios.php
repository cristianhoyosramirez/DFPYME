<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<div class="container">
    <div class="my-2">
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-center w-100 m-0 h3 text-primary">Cruce de inventario</p>
            <div class="d-flex ms-3">
                <button type="button" data-bs-toggle="modal" data-bs-target="#conteo" class="btn btn-outline-indigo d-flex ms-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Conteo maunual" onclick="ingresar_inventario()">
                    Conteo manual
                </button>
                <button type="button" class="btn btn-outline-yellow d-flex ms-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cruzar y revisar" onclick="cruzarRevisar()">
                    Cruzar y revisar
                </button>
                <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_cruce_inventarios">
                    <button class="btn btn-outline-success ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Exportar a excel">Descargar</button>
                </form>
                <!--  <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_sobrantes">
                    <button class="btn btn-outline-primary ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver sobrantes" data-bs-toggle="modal" data-bs-target="#sobrantes">Sobrantes</button>
             </form> -->
                <button type="button" class="btn btn-outline-primary d-flex ms-3" data-bs-toggle="modal" data-bs-target="#sobrantes">
                    Sobrantes
                </button>
                <button type="button" class="btn btn-outline-danger d-flex ms-3" data-bs-toggle="modal" data-bs-target="#faltantes">
                    faltantes
                </button>
                <!--  <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_faltantes">
                    <button class="btn btn-outline-danger ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver faltantes">Faltantes</button>
                </form> -->
            </div>
        </div>
    </div>

    <div style="display:none" id="progresBar" class="container">
        <p class="h3 text-warning text-center">Cruzando inventario, esta acción tomará un momento </p>
        <div class="progress mb-3">
            <div class="progress-bar progress-bar-indeterminate bg-green"></div>
        </div>
    </div>

    <div class="card">
        <div class="car-body mb-3">



            <div style="height: calc(100vh - 150px); overflow-y: auto;">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <td scope="col">Codigo</td>
                            <td scope="col">Producto</td>
                            <td scope="col">Cantidad conteo</td>
                            <td scope="col">Cantidad sistema</td>
                            <td scope="col">Diferencia inventario</td>
                            <td scope="col">Valor costo</td>
                            <td scope="col">Valor venta</td>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($conteo_manual)): ?>
                            <?php foreach ($inventario_sistema as $KeyInventarioFisico): ?>
                                <?php $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']); ?>
                                <?php if (!empty($producto)): ?>
                                    <tr>
                                        <td><?php echo $producto[0]['codigointernoproducto']; ?></td>
                                        <td><?php echo $producto[0]['nombreproducto']; ?></td>
                                        <td><?php echo $producto[0]['cantidad_inventario_fisico']; ?></td>
                                        <td><?php echo $producto[0]['cantidad_inventario']; ?></td>
                                        <td><?php echo $producto[0]['diferencia']; ?></td>
                                        <td><?php echo number_format($producto[0]['valor_costo'], 0, ",", "."); ?></td>
                                        <td><?php echo number_format($producto[0]['valor_venta'], 0, ",", "."); ?></td>
                                    </tr>
                                <?php else: ?>
                                    <?php $dato_producto = model('inventarioModel')->getProducto($KeyInventarioFisico['codigointernoproducto']); ?>
                                    <tr>
                                        <td><?php echo $KeyInventarioFisico['codigointernoproducto']; ?></td>
                                        <td><?php echo $dato_producto[0]['nombreproducto']; ?></td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td><?php echo number_format($dato_producto[0]['precio_costo'], 0, ",", "."); ?></td>
                                        <td><?php echo number_format($dato_producto[0]['valorventaproducto'], 0, ",", "."); ?></td>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($conteo_manual)): ?>

                <p class="text-center text-primary h3">No productos para hacer cruce de inventario </p>

            <?php endif ?>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="conteo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ingresar inventario </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col">
                        <label for="" class="form-label">Buscar producto</label>
                        <input type="text" class="form-control" id="inventario" name="inventario">
                        <div id="autocomplete-container"></div>
                    </div>

                </div>

                <style>
                    /* Ajustar el ancho del nombre del producto */
                    .nombre-producto {
                        width: 40%;
                        /* Ajusta el ancho según tus necesidades */
                    }

                    /* Reducir el ancho de los inputs */
                    .input-inventario {
                        width: 35%;
                        /* Ajusta el ancho según tus necesidades */
                    }
                </style>

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Código</th>
                            <td scope="col">Producto</th>
                            <td scope="col">Inventario actual</th>
                            <td scope="col">Ingresar inventario</th>
                        </tr>
                    </thead>
                    <tbody id="inventario">
                        <?php foreach ($productos as $keyProducto): ?>
                            <tr>
                                <td><?php echo $keyProducto['codigointernoproducto']; ?></td>
                                <td class="nombre-producto"><?php echo $keyProducto['nombreproducto']; ?></td>
                                <td>
                                    <input type="text" class="form-control input-inventario"
                                        value="<?php echo $keyProducto['cantidad_inventario']; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control input-inventario" id="<?php echo $keyProducto['id']; ?>" onkeyup="ingresarInv(this.value,<?php echo $keyProducto['id']; ?> )">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="sobrantes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reporte de productos sobrantes en el inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Codigo </td>
                            <td scope="col">Producto </td>
                            <td scope="col">Cantidad conteo </td>
                            <td scope="col">Cantidad sistema </td>
                            <td scope="col">Diferencia inventario </td>
                            <td scope="col">Valor costo </td>
                            <td scope="col">Valor venta </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($conteo_manual)): ?>
                            <?php $total_diferencia_venta = 0;
                            foreach ($inventario_sistema as $KeyInventarioFisico): ?>

                                <?php $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']); //d($producto);
                                ?>

                                <?php if (!empty($producto)): ?>
                                    <?php if ($producto[0]['diferencia'] > 0): ?>
                                        <tr>
                                            <td><?php echo $producto[0]['codigointernoproducto'];  ?></td>
                                            <td><?php echo $producto[0]['nombreproducto'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario_fisico'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario'];  ?></td>
                                            <td><?php echo $producto[0]['diferencia'];  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_costo'], 0, ",", ".");  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_venta'], 0, ",", ".");  ?></td>
                                        </tr>

                                        <?php
                                        // Sumar la diferencia al total
                                        $total_diferencia_venta += $producto[0]['diferencia'] * $producto[0]['valor_venta'];
                                        ?>

                                    <?php endif ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_sobrantes">
                    <button class="btn btn-outline-success ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver faltantes">Exportar EXCEL</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="faltantes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-xl  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reporte de productos faltantes en el inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Codigodd </td>
                            <td scope="col">Producto </td>
                            <td scope="col">Cantidad conteo </td>
                            <td scope="col">Cantidad sistema </td>
                            <td scope="col">Diferencia inventario </td>
                            <td scope="col">Valor costo </td>
                            <td scope="col">Valor venta </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($conteo_manual)): ?>
                            <?php $total_diferencia_venta = 0;
                            foreach ($inventario_sistema as $KeyInventarioFisico): ?>

                                <?php $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']);
                                ?>

                                <?php if (!empty($producto)): ?>
                                    <?php if ($producto[0]['diferencia'] < 0): ?>
                                        <tr>
                                            <td><?php echo $producto[0]['codigointernoproducto'];  ?></td>
                                            <td><?php echo $producto[0]['nombreproducto'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario_fisico'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario'];  ?></td>
                                            <td><?php echo $producto[0]['diferencia'];  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_costo'], 0, ",", ".");  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_venta'], 0, ",", ".");  ?></td>
                                        </tr>
                                    <?php endif ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_faltantes">
                    <button class="btn btn-outline-success ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver faltantes">Exportar EXCEL</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("inventario");
        const container = document.getElementById("autocomplete-container");
        const url = <?php echo base_url(); ?>;

        input.addEventListener("input", function() {
            const term = input.value.trim();

            if (term.length < 1) {
                container.innerHTML = "";
                return;
            }

            // Realiza la solicitud AJAX con fetch
            fetch(`${url}/inventario/producto_entrada`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        term: term
                    })
                })
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = ""; // Limpia la lista anterior

                    if (data.length > 0) {
                        const list = document.createElement("div");
                        list.className = "autocomplete-list";

                        data.forEach(item => {
                            const listItem = document.createElement("div");
                            listItem.className = "autocomplete-item";
                            listItem.textContent = item.value;

                            // Al hacer clic en un elemento
                            listItem.addEventListener("click", function() {
                                if (item.id_inventario === 1 || item.id_inventario === 4) {
                                    input.value = "";
                                    document.getElementById("display").value = item.value;
                                    document.getElementById("id_producto").value = item.codigo;
                                    document.getElementById("actual").value = item.cantidad;
                                    document.getElementById("precio").value = item.precio_costo;
                                    document.getElementById("cantidad").focus();
                                    document.getElementById("cantidad").select();

                                    // Calcula el nuevo total
                                    const actual = parseFloat(item.cantidad) || 0;
                                    const cantidad = parseFloat(document.getElementById("cantidad").value) || 0;
                                    document.getElementById("nuevo").value = actual + cantidad;
                                } else if (item.id_inventario === 3) {
                                    document.getElementById("error_producto").innerHTML =
                                        "Este producto es una receta y no se puede ingresar por compras";
                                }
                                container.innerHTML = ""; // Limpia la lista
                            });

                            list.appendChild(listItem);
                        });

                        container.appendChild(list);
                    }
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                });
        });

        // Cierra el autocomplete si se hace clic fuera
        document.addEventListener("click", function(event) {
            if (!container.contains(event.target) && event.target !== input) {
                container.innerHTML = "";
            }
        });
    });
</script>





<script>
    // Inicializa el modal
    const myModalElement = document.getElementById('sobrantes');
    const myModal = new bootstrap.Modal(myModalElement);

    // Muestra el modal
    myModal.show();

    /*  // Cambia dinámicamente el contenido
     const modalBody = myModalElement.querySelector('.sobrantes');
     modalBody.textContent = "Nuevo contenido dinámico."; */

    // Actualiza la posición y el estilo del modal
    myModal.handleUpdate();
</script>



<script>
    function cruzarRevisar() {

        Swal.fire({
            title: "¿Seguro desea cruzar el inventario?",
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: "Cruzar",
            denyButtonText: "Cancelar",
            customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-danger mx-2'
            },
            buttonsStyling: false // Necesario para que las clases personalizadas funcionen
        }).then((result) => {
            if (result.isConfirmed) {
                cruceInventario();
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
        });


    }
</script>
<!-- <script>
    async function cruceInventario() {

        const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
        const url = `${baseUrl}/pre_factura/cruzarInventario`; // Construye la URL dinámica

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.statusText}`);
        }

        const data = await response.json();
        //alert(`Producto actualizado: ${data.message}`);
        if (data.success === true) {
            sweet_alert_centrado('success', 'Inventario cruzado ')
            location.reload();
        }
        if (data.success === false) {
            sweet_alert_centrado('warning', 'No hay inventario para cruzar ')
        }

    }
</script> -->


<script>
    async function cruceInventario() {
        const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
        const url = `${baseUrl}/pre_factura/cruzarInventario`; // Construye la URL dinámica

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {
                 sweet_alert_centrado('success', 'Inventario cruzado');
                location.reload(); 
              

            } else if (data.success === false) {
                sweet_alert_centrado('warning', 'No hay inventario para cruzar');
            }
        } catch (error) {
            console.error("Error al cruzar el inventario:", error);
            sweet_alert_centrado('error', 'Ocurrió un error inesperado al cruzar el inventario');
        }
    }
</script>





<script>
    async function ingresarInv(valor, id) {
        try {
            const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
            const url = `${baseUrl}/pre_factura/ingresarInv`; // Construye la URL dinámica

            // Crea el payload (datos a enviar)
            const payload = {
                id: id, // ID del producto
                valor: valor // Valor ingresado por el usuario
            };

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload) // Enviar datos como JSON
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {
                sweet_alert_centrado('success', 'Inventario cruzado');
                location.reload(); // Recarga la página si la operación fue exitosa
            } else {
                console.warn('Respuesta inesperada del servidor:', data);
                alert(data.message || 'Hubo un problema en la actualización.');
            }
        } catch (error) {
            console.error('Hubo un problema al actualizar el producto:', error);
            alert('No se pudo actualizar el producto. Por favor, intenta de nuevo.');
        }
    }
</script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("producto_compra");
        const container = document.getElementById("autocomplete-container");
        const url = document.getElementById("url").value;

        input.addEventListener("input", function() {
            const term = input.value.trim();

            if (term.length < 1) {
                container.innerHTML = "";
                return;
            }

            // Realiza la solicitud AJAX con fetch
            fetch(`${url}/inventario/producto_entrada`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        term: term
                    })
                })
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = ""; // Limpia la lista anterior

                    if (data.length > 0) {
                        const list = document.createElement("div");
                        list.className = "autocomplete-list";

                        data.forEach(item => {
                            const listItem = document.createElement("div");
                            listItem.className = "autocomplete-item";
                            listItem.textContent = item.value;

                            // Al hacer clic en un elemento
                            listItem.addEventListener("click", function() {
                                if (item.id_inventario === 1 || item.id_inventario === 4) {
                                    input.value = "";
                                    document.getElementById("display").value = item.value;
                                    document.getElementById("id_producto").value = item.codigo;
                                    document.getElementById("actual").value = item.cantidad;
                                    document.getElementById("precio").value = item.precio_costo;
                                    document.getElementById("cantidad").focus();
                                    document.getElementById("cantidad").select();

                                    // Calcula el nuevo total
                                    const actual = parseFloat(item.cantidad) || 0;
                                    const cantidad = parseFloat(document.getElementById("cantidad").value) || 0;
                                    document.getElementById("nuevo").value = actual + cantidad;
                                } else if (item.id_inventario === 3) {
                                    document.getElementById("error_producto").innerHTML =
                                        "Este producto es una receta y no se puede ingresar por compras";
                                }
                                container.innerHTML = ""; // Limpia la lista
                            });

                            list.appendChild(listItem);
                        });

                        container.appendChild(list);
                    }
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                });
        });

        // Cierra el autocomplete si se hace clic fuera
        document.addEventListener("click", function(event) {
            if (!container.contains(event.target) && event.target !== input) {
                container.innerHTML = "";
            }
        });
    });
</script>





<?= $this->endSection('content') ?>