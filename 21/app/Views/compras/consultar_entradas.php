<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
Entradas de productos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-1" style="margin-bottom: -10px; padding-bottom: 0;">
                <div class="card-title">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-center text-primary">Consultar compras </p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-primary h3 ">Buscar por :</p>
                <div class="row g-3 align-items-end">

                    <!-- FECHA INICIAL -->
                    <div class="col-md-3">
                        <label class="form-label">Fecha inicial</label>
                        <input type="date"
                            class="form-control"
                            id="fecha_inicial"
                            value="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <!-- FECHA FINAL -->
                    <div class="col-md-3">
                        <label class="form-label">Fecha final</label>
                        <input type="date"
                            class="form-control"
                            id="fecha_final"
                            value="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <!-- PROVEEDOR -->
                    <div class="col-md-4">
                        <label class="form-label">Proveedor</label>
                        <select id="id_proveedor" class="form-select">
                            <option value="">Todos los proveedores</option>
                            <?php foreach ($proveedores as $proveedor): ?>
                                <option value="<?php echo $proveedor['codigointernoproveedor']; ?>">
                                    <?php echo $proveedor['nombrecomercialproveedor']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- BOTONES -->
                    <div class="col-md-2 d-flex gap-2">
                        <button class="btn btn-outline-success" id="btn_filtrar">
                            Buscar
                        </button>

                        <button class="btn btn-outline-success" onclick="exportarExcel()">
                            Excel
                        </button>
                    </div>

                </div>

                <br>
                <div class="table-responsive">
                    <input type="hidden" id="url" value="<?php echo base_url() ?>">
                    <input type="hidden" value="general" id="buscar_por">
                    <table id="consulta_compras" class="table">
                        <thead class="table-dark">
                            <td>Fecha</th>
                            <td>Factura</th>
                            <td>Proveedor</th>
                            <td>Nit</th>
                            <td>Valor </th>
                            <td>Usuario</th>
                            <td>Accion</th>

                        </thead>
                        <tbody id="compras">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modal_compra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle de compras </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="" id="detalle_productos_compra"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>





<script>
    async function buscarDatos() {
        try {
            let fecha_inicial = document.getElementById('fecha_inicial').value;
            let fecha_final = document.getElementById('fecha_final').value;
            let proveedor = document.getElementById('proveedor').value;

            let response = await fetch("<?= base_url('inventario/buscar_compras') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    fecha_inicial,
                    fecha_final,
                    proveedor
                })
            });

            let data = await response.text();

            document.getElementById('resultado_busqueda').innerHTML = data;

        } catch (error) {
            console.error("Error:", error);
            alert("Error al realizar la búsqueda");
        }
    }
</script>

<script>
    function imprimir_compra(id) {

        var url = document.getElementById("url").value;

        $.ajax({
            data: {
                id

            },
            url: url + "/comanda/imprimir_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    sweet_alert_centrado('success', 'Impresion de compra')


                }
            },
        });

    }
</script>


<script>
    function detalle_compra(id) {

        var url = document.getElementById("url").value;

        $.ajax({
            data: {
                id

            },
            url: url + "/inventario/consultar_productos_compra",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    $('#detalle_productos_compra').html(resultado.productos)
                    $('#modal_compra').modal('show')


                }
            },
        });

    }
</script>


<?= $this->endSection('content') ?>