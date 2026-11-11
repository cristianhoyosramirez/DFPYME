<div class="modal fade" id="finalizar_venta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="modal-title">Realizar abono</h5>
                        </div>
                        <div class="col-12">
                            <div id="mensaje_factura"></div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="cancelar_pagar()"></button>
            </div>
            <div class="modal-body">

                <div class="row row-cards">

                    <div class="col-md-6 col-lg-6">
                        <?= $this->include('cartera/datos_factura_credito') ?>
                    </div>

                    <div class="col-md-6 col-lg-6">
                        <div class="card shadow-sm border-0 w-100 h-100">

                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">Información de pago </h5>
                            </div>

                            <div class="card-body">

                                <form>

                                    <!-- TOTAL -->
                                    <div class="mb-4 text-center">
                                        <h1 id="total_pedido" class="fw-bold text-success mb-1"></h1>
                                        <p id="valor_pago_error" class="text-danger fw-bold mb-0"></p>
                                    </div>

                                    <input type="text" id="valor_total_a_pagar" hidden>
                                    <input type="text" id="id_factura_a_pagar" hidden>
                                    <input type="text" id="estado_factura_a_pagar" hidden>
                                    <!-- METODOS DE PAGO -->
                                    <div class="row g-3 mb-4">

                                        <!-- EFECTIVO -->
                                        <div class="col-md-6">

                                            <label
                                                class="w-100 p-3 border rounded bg-white"
                                                style="cursor:pointer;">

                                                <div class="fw-bold mb-2 text-dark">
                                                    💵 Efectivo
                                                </div>

                                                <div class="form-floating">

                                                    <input
                                                        type="text"
                                                        class="form-control text-dark"
                                                        id="efectivo"
                                                        value="0"
                                                        autocomplete="off"
                                                        onkeyup="formatearMiles(this); cambio(this.value)"
                                                        style="
                    color:#000 !important;
                    box-shadow:none !important;
                ">

                                                    <label
                                                        for="efectivo"
                                                        class="text-dark"
                                                        style="color:#000 !important;">

                                                        Valor efectivo

                                                    </label>

                                                </div>

                                            </label>

                                        </div>

                                        <!-- BANCO -->
                                        <div class="col-md-6">

                                            <label
                                                class="w-100 p-3 border rounded bg-white"
                                                style="cursor:pointer;">

                                                <div class="fw-bold mb-2 text-dark">
                                                    🏦 Banco
                                                </div>

                                                <div class="form-floating">

                                                    <input
                                                        type="text"
                                                        class="form-control text-dark"
                                                        id="transaccion"
                                                        value="0"
                                                        autocomplete="off"
                                                        onkeyup="formatearMiles(this); cambio_transaccion(this.value)"
                                                        style="
                    color:#000 !important;
                    box-shadow:none !important;
                ">

                                                    <label
                                                        for="transaccion"
                                                        class="text-dark"
                                                        style="color:#000 !important;">

                                                        Valor banco

                                                    </label>

                                                </div>

                                            </label>

                                        </div>

                                    </div>

                                    <!-- SELECT BANCO -->
                                    <div class="mb-4">

                                        <label for="clase_pago" class="form-label fw-bold">
                                            Banco
                                        </label>

                                        <?php
                                        $clasePago = model('clasePagoModel')
                                            ->where('estado', 'true')
                                            ->orderBy('nombre', 'asc')
                                            ->findAll();
                                        ?>

                                        <select
                                            name="clase_pago"
                                            id="clase_pago"
                                            class="form-select"
                                            onchange="limpiarErrorSelect()">

                                            <option value="">
                                                Seleccione un banco
                                            </option>

                                            <?php foreach ($clasePago as $detalleClasePago): ?>

                                                <option
                                                    value="<?= esc($detalleClasePago['id']) ?>"
                                                    <?= (count($clasePago) === 1) ? 'selected' : '' ?>>

                                                    <?= esc($detalleClasePago['nombre']) ?>

                                                </option>

                                            <?php endforeach ?>

                                        </select>

                                        <span class="text-danger" id="errorClasePago"></span>

                                    </div>

                                    <span id="abonoSuperior" class="text-danger"> </span>

                                    <!-- RESUMEN -->
                                    <div class="card border-0 shadow-sm mb-4">

                                        <div class="card-body">

                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="fw-bold">Valor pago</span>
                                                <span id="pago" class="h5 mb-0">0</span>
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="fw-bold">Faltante</span>
                                                <span id="faltante" class="h5 mb-0 text-danger">0</span>
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">Cambio</span>
                                                <span id="cambio" class="h5 mb-0 text-success">0</span>
                                            </div>

                                        </div>

                                    </div>

                                    <!-- HIDDEN -->
                                    <input type="hidden" id="tipo_pago" value="1">
                                    <input type="hidden" id="requiere_factura_electronica">

                                    <!-- BOTONES -->
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">

                                        <button
                                            type="button"
                                            class="btn btn-success px-5"
                                            id="btn_pagar"
                                            onclick="pagar()">
                                            Pagar
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-outline-danger px-5"
                                            onclick="cancelar_pagar()">
                                            Cancelar
                                        </button>

                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>