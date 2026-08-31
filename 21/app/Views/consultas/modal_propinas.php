<!-- Modal -->
<div class="modal fade" id="propinas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">

                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Reporte de propinas
                </h1>

                <!-- <div class="ms-auto me-3">
                    <button type="button"
                        class="btn btn-outline-success"
                        onclick="exportarPropinasExcel()">
                        <i class="bi bi-file-earmark-excel"></i>
                        Exportar a Excel
                    </button>
                </div> -->

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>

            <div class="modal-body">
                <div id="reporte_propinas"></div>
            </div>

            <div class="modal-footer">
                <p class="text-primary h2 mb-0" id="total_propinas"></p>
            </div>

        </div>
    </div>
</div>