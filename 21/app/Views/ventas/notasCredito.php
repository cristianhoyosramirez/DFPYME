<?php foreach ($notas_credito as $detalle): ?>

    <?php
    $fecha = date('d/m/Y', strtotime($detalle['fecha']));
    $hora  = date('h:i A', strtotime($detalle['fecha']));

    switch ($detalle['id_status']) {
        case 1:
            $estado = '<span class="badge bg-yellow-lt">Borrador</span>';
            break;
        case 2:
            $estado = '<span class="badge bg-green-lt">Aceptada</span>';
            break;
        case 3:
            $estado = '<span class="badge bg-red-lt">Rechazada</span>';
            break;
        case 4:
            $estado = '<span class="badge bg-orange-lt">Error</span>';
            break;
        default:
            $estado = '<span class="badge bg-secondary">N/A</span>';
            break;
    }
    ?>

    <tr id="filaNc<?= $detalle['id'] ?>" class="align-middle">

        <!-- Fecha -->
        <td class="py-2">
            <div class="fw-bold"><?= $fecha ?></div>
            <small class="text-muted"><?= $hora ?></small>
        </td>

        <!-- Cliente -->
        <td class="py-2">
            <div class="fw-semibold"><?= esc($detalle['nit_cliente']) ?></div>
        </td>

        <td class="py-2">
            <?= esc($detalle['nombrescliente']) ?>
        </td>

        <!-- Nota Crédito -->
        <td class="py-2">
            <span class="fw-semibold">
                <?= $detalle['numero_nota_credito'] ?>
            </span>
        </td>

        <!-- Factura -->
        <td class="py-2">
            <?= $detalle['numero_factura'] ?>
        </td>

        <!-- Razón -->
        <td class="py-2">
            <span class="text-truncate d-inline-block" style="max-width: 180px;">
                <?= esc($detalle['razon']) ?>
            </span>
        </td>



        <!-- Estado -->
        <td class="py-2">
            <?= $estado ?>
        </td>

        <!-- Usuario -->
        <td class="py-2">
            <?= esc($detalle['usuario']) ?>
        </td>

        <td class="py-2">
            <div class="d-flex justify-content-end align-items-center gap-1">

                <!-- Imprimir -->
                <button type="button"
                    class="btn btn-outline-success btn-icon d-flex align-items-center justify-content-center"
                    title="Imprimir"
                    onclick="imprimirNotaCredito(<?= $detalle['id'] ?>)">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                        <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                        <rect x="7" y="13" width="10" height="8" rx="2" />

                    </svg>

                </button>

                <!-- Ver -->
                <button type="button"
                    class="btn btn-outline-dark btn-icon d-flex align-items-center justify-content-center"
                    title="Ver detalle"
                    onclick="detalleNotaCredito(<?= $detalle['id'] ?>)">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <circle cx="12" cy="12" r="2" />
                        <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />

                    </svg>

                </button>

                <!-- Enviar DIAN -->
                <?php if ($detalle['id_status'] == 1): ?>
                    <button type="button"
                        class="btn btn-outline-dark btn-icon d-flex align-items-center justify-content-center"
                        title="Enviar DIAN"
                        onclick="enviarNotaCredito(<?= $detalle['id'] ?>)">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">

                            <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                            <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M17 21l4 -4" />
                            <path d="M3 7l9 6l9 -6" />

                        </svg>

                    </button>
                <?php endif; ?>

                <!-- PDF -->
                <?php if ($detalle['id_status'] == 2): ?>
                    <a href="<?= $detalle['pdf_url'] ?? '#' ?>"
                        target="_blank"
                        class="btn btn-outline-danger btn-icon d-flex align-items-center justify-content-center"
                        title="PDF">

                        <img src="<?= base_url('Assets/img/pdf.png') ?>"
                            width="20"
                            height="20"
                            alt="PDF">

                    </a>
                <?php endif; ?>



                <?php if ($detalle['id_status'] == 4): ?>
                    <button type="button"
                        class="btn btn-outline-dark btn-icon d-flex align-items-center justify-content-center"
                        title="Enviar DIAN"
                        onclick="reEnviarNotaCredito(<?= $detalle['id'] ?>)">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">

                            <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                            <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M17 21l4 -4" />
                            <path d="M3 7l9 6l9 -6" />

                        </svg>

                    </button>
                <?php endif; ?>



                <!-- Eliminar -->
                <?php if ($detalle['id_status'] == 1): ?>
                    <button type="button"
                        class="btn btn-outline-danger btn-icon d-flex align-items-center justify-content-center"
                        title="Eliminar"
                        onclick="eliminarNotaCredito(<?= $detalle['id'] ?>)">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="currentColor">

                            <path d="M20 6H4l1 14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2l1-14z" />
                            <path d="M14 2a2 2 0 0 1 2 2H8a2 2 0 0 1 2-2h4z" />

                        </svg>

                    </button>
                <?php endif; ?>

            </div>
        </td>

    </tr>

<?php endforeach; ?>



<script>
    async function imprimirNotaCredito(id) {

        try {

            // 🟡 Spinner de carga
            Swal.fire({
                title: 'Generando impresión...',
                text: 'Por favor espera un momento',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // 📡 Petición al backend
            const response = await fetch("<?= base_url('comanda/imprimir_nc') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id
                })
            });

            const data = await response.json();

            Swal.close();

            // ✅ Validación de respuesta
            if (data.status === true) {



                Swal.fire({
                    icon: 'success',
                    title: 'Listo',
                    text: 'Documento generado correctamente',
                    timer: 1500,
                    showConfirmButton: false
                });

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo generar el documento'
                });

            }

        } catch (error) {

            Swal.close();

            Swal.fire({
                icon: 'error',
                title: 'Error inesperado',
                text: 'Ocurrió un problema al imprimir la nota crédito'
            });

            console.error(error);
        }
    }
</script>


<script>
    async function detalleNotaCredito(id) {

        try {

            Swal.fire({
                title: 'Cargando detalle...',
                text: 'Por favor espera un momento',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch("<?= base_url('actualizacion/detalleNc') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id
                })
            });

            const data = await response.json();

            Swal.close();

            if (data.status === true) {

                document.getElementById('numero_factura_nota_credito').innerHTML = data.numero_factura
                document.getElementById('numero_nota_credito').innerHTML = data.numero_nota_credito
                document.getElementById('fecha_de_factura').innerHTML = data.fecha_factura
                document.getElementById('fecha_nota_credito').innerHTML = data.fecha_nota_credito
                document.getElementById('cliente_nota_credito').innerHTML = data.cliente
                document.getElementById('nit_cliente').innerHTML = data.nit
                document.getElementById('motivoAnulacion').innerHTML = data.razon
                document.getElementById('notaCredito').innerHTML = data.nota
                document.getElementById('detalle_nota_credito').innerHTML = data.nc

                let modal = new bootstrap.Modal(document.getElementById('detalleNc'));
                modal.show();

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo cargar el detalle'
                });

            }

        } catch (error) {

            Swal.close();

            Swal.fire({
                icon: 'error',
                title: 'Error inesperado',
                text: 'No se pudo obtener el detalle'
            });

            console.error(error);
        }
    }
</script>