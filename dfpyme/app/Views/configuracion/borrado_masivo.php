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

                    <form action="<?php echo base_url() ?>/configuracion/eliminacion_masiva" method="POST" id="formulario">
                        <div class="form-group mb-3">
                            <label for="pin">PIN</label>
                            <input type="password" class="form-control" id="pin" name="pin" placeholder="Ingrese su PIN" required>
                            <span class="text-danger" id="error_pin"></span>
                        </div>
                        <button type="button" id="btnEnviar" class="btn btn-primary w-100" onclick="validar_pin()">Enviar</button>
                        <input type="hidden" value="<?php echo base_url() ?>" id="url">

                    </form>
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

                    $('#btnEnviar').attr('type', 'submit');
                    $('#formulario').submit(); // Envía el formular

                }
                if (resultado.resultado == 1) {



                }
                if (resultado.resultado == 0) {

                    $('#error_pin').html('Error de pin ')

                }
            },

        });
    }
</script>

<?= $this->endSection('content') ?>