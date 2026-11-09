<!-- Modal -->
<div class="modal fade" id="modalNotaCredito" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Generación de notas crédito </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="clienteNotaCredito">
                <input type="hidden" id="numeroFacturaNotaCredito">
                <div id="facturaNc"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline-success" onclick="generarNc()">Generar borrador de nota crédito </button>
            </div>
        </div>
    </div>
</div>



<!-- <script>
    async function generarNc() {

        let documento = document.getElementById('id_factura_nota_credito').value;
        let id_usuario = document.getElementById("id_usuario").value;
        let nota = document.getElementById("notaCredito").value;
        let razon = document.getElementById('razon'); // 🔥 elemento completo

        // 🔥 limpiar error visual
        //razon.classList.remove('is-invalid');

        // 🔴 VALIDACIÓN CORRECTA
        if (!razon.value) {

            razon.classList.add('is-invalid');
            razon.focus();

            return;
        }

        // 🔥 SPINNER
        Swal.fire({
            title: 'Generando nota crédito...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch("<?= base_url('actualizacion/notaCredito') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    documento: documento,
                    id_usuario: id_usuario,
                    nota: nota,
                    razon: razon.value // 🔥 SOLO EL VALOR
                })
            });

            const data = await response.json();

            Swal.close();

            if (data.status == true) {

                Swal.fire({
                    icon: 'success',
                    title: '¡Nota crédito generada!',
                    text: data.mensaje || 'Se generó correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });

                const modal = document.getElementById('modalNotaCredito');
                const modalInstance = bootstrap.Modal.getInstance(modal);

                if (modalInstance) {
                    setTimeout(() => modalInstance.hide(), 500);
                }

                return data;
            }

        } catch (error) {

            Swal.close();

            console.error("Error en la validación de resolución", error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en la validación de resolución.'
            });

            return {
                response: 'error',
                message: 'Error en la validación de resolución.'
            };
        }
    }
</script> -->

<script>
    async function generarNc() {

        let documento = document.getElementById('id_factura_nota_credito').value;
        let numeroFactura = document.getElementById('numeroFacturaNotaCredito').value; // Agrega este input
        let cliente = document.getElementById('clienteNotaCredito').value; // Agrega este input

        let id_usuario = document.getElementById("id_usuario").value;
        let nota = document.getElementById("notaCredito").value;
        let razon = document.getElementById('razon');

        razon.classList.remove('is-invalid');

        if (!razon.value) {
            razon.classList.add('is-invalid');
            razon.focus();
            return;
        }

        // Confirmación
        const confirmacion = await Swal.fire({
            icon: 'question',
            title: '¿Está seguro?',
            //  html: `¿Está seguro de que desea generar la nota crédito al cliente <b>${cliente}</b> correspondiente a la factura <b>${numeroFactura}</b>?`,
            html: `¿Está seguro de que desea generar el borrador de la nota crédito y anular la factura?`,

            showCancelButton: true,
            confirmButtonText: 'Sí, generar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            focusCancel: true
        });

        if (!confirmacion.isConfirmed) {
            return;
        }

        // Cerrar el modal antes de iniciar el proceso
        const modal = document.getElementById('modalNotaCredito');
        const modalInstance = bootstrap.Modal.getInstance(modal);

        if (modalInstance) {
            modalInstance.hide();
        }

        // Spinner
        Swal.fire({
            title: 'Generando nota crédito...',
            text: 'Por favor espere.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {

            const response = await fetch("<?= base_url('actualizacion/notaCredito') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    documento: documento,
                    id_usuario: id_usuario,
                    nota: nota,
                    razon: razon.value
                })
            });

            const data = await response.json();

            Swal.close();

            /*  if (data.status) {

                 Swal.fire({
                     icon: 'success',
                     title: '¡Nota crédito generada!',
                     text: data.mensaje,
                     timer: 2000,
                     showConfirmButton: false
                 });

                 return data;
             } */

            if (data.status) {

                await Swal.fire({
                    icon: 'success',
                    title: '¡Borrador generado!',
                    text: data.mensaje,
                    confirmButtonText: 'Continuar',
                    confirmButtonColor: '#198754'
                });

                const resultado = await Swal.fire({
                    icon: 'info',
                    title: 'Siguiente paso',
                    html: `
            Se creó el <b>borrador de la nota crédito</b>.<br><br>
            Ahora consulte el borrador para realizar el envío a la <b>DIAN</b>.
        `,
                    showCancelButton: true,
                    confirmButtonText: 'Consultar borrador',
                    cancelButtonText: 'Cerrar',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-outline-primary mx-2',
                        cancelButton: 'btn btn-outline-secondary mx-2'
                    }
                });

                if (resultado.isConfirmed) {
                    window.location.href = "<?= base_url('reportes/notas_credito') ?>";
                }

                return data;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: data.mensaje
            });

        } catch (error) {

            Swal.close();

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Se presentó un error al generar la nota crédito.'
            });
        }
    }
</script>


<script>
    function cambiarColor(select) {

        if (select.value) {
            select.classList.remove('is-invalid');
            select.classList.add('is-valid'); // 🟢 opcional
        } else {
            select.classList.remove('is-valid');
            select.classList.add('is-invalid');
        }

    }
</script>