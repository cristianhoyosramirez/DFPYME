<?php foreach ($cartera as $detalle): ?>

    <tr>

        <td>
            <?= $detalle['fecha'] ?>
        </td>

        <td>
            <?= $detalle['nit_cliente'] ?>
        </td>

        <td>
            <?= $detalle['nombrescliente'] ?>
        </td>

        <td>
            <?= $detalle['documento'] ?>
        </td>

        <td>
            <?= number_format($detalle['total_documento'], 0, ',', '.') ?>
        </td>
        <td>
            <?= number_format($detalle['abonado'], 0, ',', '.') ?>
        </td>

        <td>
            <?= number_format($detalle['saldo'], 0, ',', '.') ?>
        </td>

        <td>
            <?= $detalle['descripcionestado'] ?>

        </td>

        <td>



            <button
                class="btn btn-outline-danger btn-icon"
                title="Ver saldo"
                onclick="ver_saldo(<?= $detalle['id_factura'] ?>,<?= $detalle['id_estado'] ?> )">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-dollar">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                    <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                    <path d="M12 17v1m0 -8v1" />
                </svg>

            </button>


        </td>

    </tr>

<?php endforeach; ?>

<?= $this->include('cartera/modal_finalizar_venta') ?>

<script src="<?= base_url() ?>/Assets/script_js/cartera/ver_saldo.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/cartera/formatear_miles.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/cambio.js"></script>

<script>
    async function pagar() {

        try {

            Swal.fire({
                title: 'Procesando pago...',
                html: 'Espere un momento',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let valor_efectivo =
                document.getElementById("efectivo").value;

            let id_factura =
                document.getElementById("id_factura_a_pagar").value;

            let id_estado =
                document.getElementById("estado_factura_a_pagar").value;

            let efectivoFormat =
                valor_efectivo.replace(/[.]/g, "");

            let valor_e =
                efectivoFormat === "" ?
                0 :
                parseInt(efectivoFormat);

            let valor_t =
                document.getElementById("transaccion").value;

            let valor_t_Format =
                valor_t.replace(/[.]/g, "");

            let transaccion =
                valor_t_Format === "" ?
                0 :
                parseInt(valor_t_Format);

            let clase_pago =
                document.getElementById("clase_pago").value;

            let id_usuario =
                document.getElementById("id_usuario").value;

            // VALIDAR BANCO
            if (transaccion > 0 && clase_pago == "") {

                Swal.close();

                document.getElementById('errorClasePago')
                    .innerHTML = "Debe seleccionar un banco";

                return;
            }

            const response = await fetch(
                "<?= base_url('consultas_y_reportes/abonar') ?>", {
                    method: "POST",

                    headers: {
                        "Content-Type": "application/json"
                    },

                    body: JSON.stringify({
                        id_factura: id_factura,
                        id_estado: id_estado,
                        efectivo: valor_e,
                        transaccion: transaccion,
                        clase_pago: clase_pago,
                        id_usuario: id_usuario
                    })
                }
            );

            const result = await response.json();

            Swal.close();

            if (result.success == true) {

                $("#efectivo").val(0);
                $("#transaccion").val(0);
                document.getElementById('abonoSuperior').innerHTML = '';
                $('#clase_pago').prop('selectedIndex', 0);
                $("#finalizar_venta").modal("hide");

                location.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Ingreso registrado',
                    text: result.message,
                    showCancelButton: true,
                    confirmButtonText: '🖨 Imprimir comprobante',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#198754',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-4 shadow-lg',
                        actions: 'gap-4 mt-4',
                        confirmButton: 'btn btn-success px-4',
                        cancelButton: 'btn btn-outline-danger px-4'
                    }

                }).then(async (resultado) => {

                    if (resultado.isConfirmed) {


                        //alert(result.id_factura)
                        await imprimir_comprobante(result.id_transaccion);

                    }

                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                });

            } else {

                /*        Swal.fire({
                           icon: 'error',
                           title: result.mensaje,
                           timer: 2500,
                           timerProgressBar: true,
                           showConfirmButton: false,
                           toast: true,
                           position: 'top-end',
                           background: '#fef2f2',
                           color: '#991b1b',
                           iconColor: '#dc2626',
                           customClass: {
                               popup: 'rounded-4 shadow-lg border-0'
                           }
                       }); */

                document.getElementById('abonoSuperior').innerHTML = 'Abono supera el saldo actual';
            }

        } catch (error) {

            console.error(error);

            Swal.close();

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al procesar el pago'
            });

        }

    }
</script>

<!-- <script>
    async function ver_saldo(id_factura, id_estado) {
        try {
            const response = await fetch("<?= base_url('consultas_y_reportes/saldo_factura') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_factura: id_factura,
                    id_estado: id_estado
                })
            });

            const data = await response.json();



            if (data.resultado == 1) {

                document.getElementById('numero_de_factura').innerHTML = data.documento
                document.getElementById('nombre_cliente').innerHTML = data.cliente
                document.getElementById('fecha_factura').innerHTML = data.fecha
                document.getElementById('total_factura').innerHTML = data.valor
                document.getElementById('abonado').innerHTML = data.tota_pagado
                document.getElementById('saldo_pendiente').innerHTML = data.saldo
                document.getElementById('valor_total_a_pagar').value = data.pendiente_de_pago
                document.getElementById('id_factura_a_pagar').value = data.id_factura
                document.getElementById('estado_factura_a_pagar').value = data.id_estado

                $("#finalizar_venta").modal("show");


            } else {
                alert("Error: " + data.message);
            }

        } catch (error) {
            console.error("Error en la petición:", error);
            alert("Ocurrió un error al consultar el saldo");
        }
    }
</script> -->