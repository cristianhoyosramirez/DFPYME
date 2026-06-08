<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>

<?= $this->section('title') ?>
Borrado
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Ingresar PIN</h4>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="pin">PIN</label>
                        <input type="password" class="form-control" id="pin" name="pin" placeholder="Ingrese su PIN" required>
                        <span class="text-danger" id="error_pin"></span>
                    </div>
                    <button type="button" id="btnEnviar" class="btn btn-primary w-100" onclick="validar_pin()">Enviar</button>
                    <input type="hidden" value="<?php echo base_url() ?>" id="url">
                    <input type="hidden" value="<?php echo $user_session->id_usuario ?>" id="id_usuario">
                </div>
            </div>
        </div>
        <div class="container" style="display: none;" id="borrado">
            <div class="row">
                <div class="col">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <label for="" class="form-label">Realizando cambios </label>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function validar_pin() {
            let url = document.getElementById("url").value;
            let pin = document.getElementById("pin").value;
            $.ajax({
                data: {
                    pin
                },
                url: url + "/" + "configuracion/validar_pin",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    // Aquí puedes manejar el resultado como desees
                    if (resultado.resultado == 1) {

                        Swal.fire({
                            title: 'Procesando...',
                            text: 'Por favor espera',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: url + "/" + "configuracion/eliminacion_masiva",
                            type: "POST",
                            success: function(resultado) {

                                var resultado = JSON.parse(resultado);

                                if (resultado.resultado == 1) {

                                    Swal.close(); // 👈 cerrar spinner

                                    const input = document.getElementById('pin');
                                    input.value = '';
                                    input.focus();

                                    sweet_alert_start('success', 'Registros actualizados');
                                }
                            },
                            error: function(xhr, status, error) {

                                Swal.close();

                                console.error("Error en la solicitud AJAX:", status, error);

                                sweet_alert_start('error', 'Ocurrió un error');
                            }
                        });
                    }

                    if (resultado.resultado == 0) {

                        $('#error_pin').html('Error de pin ')

                    }
                },

            });
        }
    </script>

    <?= $this->endSection('content') ?>