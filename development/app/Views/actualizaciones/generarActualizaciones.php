<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= $this->renderSection('title') ?>&nbsp;-&nbsp;DF PYME</title>
    <!-- CSS files -->
    <link href="<?= base_url() ?>/Assets/css/tabler.min.css" rel="stylesheet" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
</head>
<?php $session = session(); ?>

<body>
    <div class="wrapper">


        <div class="page-wrapper">
            <div class="container-xl">
            </div>
            <div class="page-body">
                <?= $this->renderSection('content') ?>

                <div class="container">
                    <div style="background-color: #ffeb3b; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-weight: bold;">
                        📢 Hay actualizaciones disponibles.
                    </div>

                    <?= $this->include('actualizaciones/tabla_actualizaciones') ?>
                    <input type="text" id="url" value="<?= base_url() ?>" hidden>
                </div>


            </div>
            <?= $this->include('layout/footer') ?>
        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>

        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>


        <script>
            async function generarActulizacion(actualizacion) {



                try {
                    // Mostrar SweetAlert con spinner
                    Swal.fire({
                        title: 'Aplicando cambios...',
                        html: 'Por favor espera',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const url = document.getElementById('url').value.trim();
                    const response = await fetch(url + '/generarActualizacion', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            actualizacion: actualizacion,
                        })
                    });

                    const data = await response.json();


                    if (data.response == "success") {
                        // Cerrar spinner y mostrar éxito
                        Swal.fire({
                            title: 'Cambios aplicados',
                            text: 'La versión se guardó correctamente',
                            icon: 'success',
                            confirmButtonText: 'Ir al login',
                            showCancelButton: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url  // Ajusta la URL según tu ruta real
                            }
                        });

                        // Aquí puedes cerrar modal, limpiar formulario, etc.
                    } else {
                        // Error del servidor (respuesta no OK)
                        Swal.fire('Error', data.mensaje || 'No se pudo crear la versión', 'error');
                    }

                } catch (error) {
                    console.error('Error en crearVersion:', error);
                    Swal.fire('Error', 'Hubo un problema de red o del servidor', 'error');
                }

            }
        </script>

</body>

</html>