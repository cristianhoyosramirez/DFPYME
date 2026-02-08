<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS POR HORA
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <p class="text-center text-primary h3 ">Reporte de ventas por horas </p>
    <div class="card">
        <div class="card-body">
            <div class="container mt-4">


                <div class="card">
                    <div class="card-body">
                        <form method="post" action="<?php  echo base_url() ?>/factura_directa/ventasHora" >
                            <div class="row mb-3 align-items-end">

                                <!-- Fecha de inicio -->
                                <div class="col-2">
                                    <label for="fecha_inicio" class="form-label">Fecha:</label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>

                                <!-- Botón Buscar -->
                                <div class="col-2">
                                    <button type="button"
                                        class="btn btn-outline-primary w-100"
                                        onclick="buscarDatos()"
                                        data-bs-toggle="tooltip"
                                        title="Haz clic para buscar los datos"
                                        data-bs-placement="bottom">
                                        Buscar
                                    </button>
                                </div>

                                <!-- Botón Excel -->
                                <div class="col-1">
                                    <button type="submit" name="export" value="excel"
                                        class="btn btn-outline-success w-100"
                                        data-bs-toggle="tooltip"
                                        title="Exportar a excel "
                                        data-bs-placement="bottom">Excel</button>
                                </div>
                            </div>
                        </form>

                        <div id="barra-progreso" class="d-none mb-3">
                            <p class="mb-1 fw-bold text-muted">Buscando datos...</p>
                            <div class="progress">
                                <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                            </div>
                        </div>




                        <div style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <td>Código</th>
                                        <td>Producto</th>
                                        <td>Cantidad</th>
                                        <td>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="reporteHoras">
                                    <?php
                                    for ($hora = 0; $hora < 24; $hora++) {
                                        $inicio = str_pad($hora, 2, '0', STR_PAD_LEFT) . ":00";
                                        $fin = str_pad($hora + 1, 2, '0', STR_PAD_LEFT) . ":00";

                                        $productos = model('reporteProductoModel')->getProductos($inicio, $fin, date('Y-m-d'));
                                        $total = model('reporteProductoModel')->getTotal($inicio, $fin, date('Y-m-d'));

                                        // Mostrar solo si hay productos
                                        if (!empty($productos)) {
                                            echo "<tr><td colspan='4'><strong>HORA : {$inicio} - {$fin}</strong></td></tr>";

                                            foreach ($productos as $producto) {
                                                echo "<tr>";
                                                echo "<td>{$producto['codigo']}</td>";
                                                echo "<td>{$producto['nombreproducto']}</td>";
                                                echo "<td>{$producto['total_cantidad']}</td>";
                                                echo "<td>$" . number_format($producto['total_valor'], 0, ',', '.') . "</td>";
                                                echo "</tr>";
                                            }

                                            echo "<tr>";
                                            echo "<td colspan='4' style='text-align: left; font-weight: bold;'>TOTAL VENDIDO ENTRE {$inicio} Y LAS {$fin}: $" . number_format($total[0]['total_valor'], 0, ',', '.') . "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    async function buscarDatos() {
        document.getElementById('barra-progreso').classList.remove('d-none');
        try {
            // Captura de valores del formulario
            const fechaInicio = document.getElementById("fecha_inicio").value;




            const datos = {
                inicio: fechaInicio,
            };


            const response = await fetch("<?= base_url('pedidos/reporteHoras') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            });


            const resultado = await response.json();
            if (resultado.response = "success") {
                sweet_alert_centrado('success', 'Resultados encontrados')
                document.getElementById('barra-progreso').classList.add('d-none');
                document.getElementById('reporteHoras').innerHTML = resultado.productos
            }




        } catch (error) {
            console.error('Error al buscar datos:', error);
        }
    }
</script>



<?= $this->endSection('content') ?>