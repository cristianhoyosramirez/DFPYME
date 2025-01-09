<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/inventarios') ?>
<?= $this->section('title') ?>
INVENTARIOS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<style>
    /* Establece que la tabla se ajuste a la altura disponible */
    .table-container {
        height: 70vh;
        /* Usa el 100% de la altura de la ventana */
        overflow-y: auto;
        /* Habilita la barra de desplazamiento si es necesario */
    }

    /* Si deseas que el contenido de la tabla tenga un área visible con scroll */
    .table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 10px;
        text-align: left;
    }

    .table thead {
        position: sticky;
        top: 0;
        background-color: #343a40;
        /* Cambia según el color de fondo de tu cabecera */
        z-index: 1;
    }
</style>

<div class="container">

    <div class="row align-items-center mb-3">
        <!-- Columna para el título centrado -->
        <div class="col text-center">
            <p class="h3 text-primary mb-0">INVENTARIO</p>
        </div>
        <!-- Columna para el botón alineado a la derecha -->
        <div class="col-auto ms-auto">
            <form action="<?php  echo base_url() ?>/consultas_y_reportes/Inventario" method="POST"> 
                <button type="submit" class="btn btn-outline-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Exportar a EXCEL ">Excel</button>
            </form>

        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col" style="width: 30%;">Categoría</th>
                            <td scope="col" style="width: 8%;">Código</th>
                            <td scope="col" style="width: 30%;">Producto</th>
                            <td scope="col" style="width: 8%;">Cantidad</th>
                            <td scope="col">Costo unidad</th>
                            <td scope="col">Costo total</th>
                        </tr>
                    </thead>
                    <tbody id="inventario">
                        <tr>

                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="text-end text-primary h3 ">
                <span>Costo total inventario:</span> <span id="costoInventario"> </span><br>
                <span>Total unidades: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span id="totalUnidades"> </span>
            </div>





        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", async function() {
        await inventario(); // Llama a la función tan pronto como se carga la página
    });

    async function inventario() {
        try {
            const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
            const url = `${baseUrl}/administracion_impresora/ProductosInventario`; // Construye la URL dinámica

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();

            if (data.success === true) {
                // Inicializamos la variable rows antes del ciclo
                document.getElementById('costoInventario').innerHTML = data.costo_total;
                document.getElementById('totalUnidades').innerHTML = data.unidades;
                let rows = '';

                // Iteramos sobre los productos
                data.productos.forEach(producto => {
                    // Construimos las filas con los datos del producto
                    rows += `<tr>
                                 <td>${producto.nombrecategoria}</td>
                                 <td>${producto.codigointernoproducto}</td>
                                 <td>${producto.nombreproducto}</td>
                                 <td>${producto.cantidad_inventario}</td>
                               <td>${producto.costo_unitario.toLocaleString()}</td>
<td>${producto.costo_producto.toLocaleString()}</td>
                            </tr>`;

                    document.getElementById('inventario').innerHTML = rows;

                });



                // Finalmente, actualizamos el contenido del tbody con las filas acumuladas

            } else if (data.success === false) {
                sweet_alert_centrado('warning', 'No hay productos receta');
            }
        } catch (error) {
            console.error('Hubo un problema al cargar las recetas:', error);
            alert('No se pudo cargar las recetas. Por favor, intenta de nuevo.');
        }
    }
</script>


<?= $this->endSection('content') ?>