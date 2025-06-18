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
                        <form method="GET" action="">
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

                                <!-- Botón PDF -->
                                <div class="col-1">
                                    <button type="submit" name="export" value="pdf" class="btn btn-outline-danger w-100"
                                        data-bs-toggle="tooltip"
                                        title="
                                        Exportar PDF"
                                        data-bs-placement="bottom">PDF</button>
                                </div>

                            </div>
                        </form>


                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">

                                <tr class="table-dark">
                                    <td scope="col">Código</th>
                                    <td scope="col">Producto</th>
                                    <td scope="col">Cantidad</th>
                                    <td scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($hora = 0; $hora < 24; $hora++) {

                                    $inicio = str_pad($hora, 2, '0', STR_PAD_LEFT) . ":00";
                                    $fin = str_pad($hora + 1, 2, '0', STR_PAD_LEFT) . ":00";

                                    $productos = model('reporteProductoModel')->getProductos($inicio, $fin, date('Y-m-d'));
                                    $total = model('reporteProductoModel')->getTotal($inicio, $fin, date('Y-m-d'));

                                    echo "<tr><td colspan='4'><strong>HORA : {$inicio} - {$fin}</strong></td></tr>";

                                    if (!empty($productos)) {
                                        foreach ($productos as $producto) {
                                            echo "<tr>";
                                            echo "<td>{$producto['codigo']}</td>";
                                            echo "<td>{$producto['nombreproducto']}</td>";
                                            echo "<td>{$producto['total_cantidad']}</td>";
                                            echo "<td>$" . number_format($producto['total_valor'], 0, ',', '.') . "</td>";
                                            echo "</tr>";
                                        }

                                        // Mostrar total por hora alineado a la izquierda
                                        echo "<tr>";
                                        echo "<td colspan='4' style='text-align: left; font-weight: bold;'>TOTAL VENDIDO ENTTRE  {$inicio} Y LAS  {$fin}: $" . number_format($total[0]['total_valor'], 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    } else {
                                        echo "<tr><td colspan='4'>No se registraron productos en esta hora.</td></tr>";
                                    }
                                }
                                ?>



                                <tr>
                                    <td>002</td>
                                    <td>Leche Entera</td>
                                    <td>2</td>
                                    <td>$6.00</td>
                                </tr>
                                <tr>
                                    <td>003</td>
                                    <td>Pan Integral</td>
                                    <td>1</td>
                                    <td>$2.50</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    async function buscarDatos() {
        try {
            // Captura de valores del formulario
            const fechaInicio = document.getElementById("fecha_inicio").value;
            const horaInicio = document.getElementById("hora_inicio").value;
            const fechaFin = document.getElementById("fecha_fin").value;
            const horaFin = document.getElementById("hora_fin").value;

            // Unimos fecha y hora en formato completo tipo timestamp
            const datetimeInicio = `${fechaInicio} ${horaInicio}:00`;
            const datetimeFin = `${fechaFin} ${horaFin}:59`;

            const datos = {
                inicio: datetimeInicio,
                fin: datetimeFin
            };


            const response = await fetch("<?= base_url('pedidos/reporteHoras') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            });

            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }

            const resultado = await respuesta.json();
            console.log(resultado); // Puedes mostrarlo en tabla o consola

            mostrarResultadosEnTabla(resultado);

        } catch (error) {
            console.error('Error al buscar datos:', error);
        }
    }

    function mostrarResultadosEnTabla(datos) {
        const tabla = document.getElementById("tabla_resultados");
        tabla.innerHTML = "";

        datos.forEach(item => {
            const fila = `
                <tr>
                    <td>${item.nombreproducto}</td>
                    <td>${item.hora}</td>
                    <td>${item.total_cantidad}</td>
                    <td>${item.total_valor}</td>
                </tr>
            `;
            tabla.innerHTML += fila;
        });
    }
</script>



<?= $this->endSection('content') ?>