<!-- Modal confirmar ingreso -->
<div class="modal fade" id="modalConfirmarIngreso" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Check in </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <!-- FORMULARIO -->
                <form id="formRegistroHuesped">
                    <div class="container-fluid">
                        <div class="row g-3">
                            <?= view('habitaciones/registrar') ?>
                        </div>
                    </div>
                </form>

                <!-- BOTONES FUERA PERO ALINEADOS -->
                <div class="container-fluid">
                    <div class="d-flex justify-content-end mt-4 gap-2 flex-wrap border-top pt-3">

                        <button type="reset" form="formRegistroHuesped" class="btn btn-outline-danger btn-sm px-4">
                            Cancelar reserva
                        </button>

                        <button onclick="checkIn()" class="btn btn-outline-success btn-sm px-4 shadow-sm">
                            Confirmar ingreso
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>