<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <input type="hidden" value="<?php echo base_url() ?>" id="url">
    <div class="row">
        <div class="col-4 mb-3">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#conf_ped" aria-expanded="false" aria-controls="collapseThree">
                            Configuración pedido
                        </button>
                    </h2>
                    <div id="conf_ped" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <label for="" class="form-label">Mostrar código en pantalla</label>
                            <select class="form-select" aria-label="Default select example" onchange="actualizar_codigo(this.value)">
                                <option value="true" <?= $codigo_pantalla == "t" ? 'selected' : '' ?>>Si</option>
                                <option value="false" <?= $codigo_pantalla == "f" ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Altura de pantalla
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label for="altura_de_mesa" class="form-label">Altura de la pantalla</label>
                                        <input type="text" class="form-control" id="altura_de_pantalla" name="altura_de_pantalla" value="<?php echo $altura ?>">
                                    </div>
                                    <div class="ms-3"><br>
                                        <button type="button" class="btn btn-outline-primary" onclick="actualizar_altura()">Actualizar</button>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="accordion" id="accordionMitadPrecio">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingMitadPrecio">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMitadPrecio" aria-expanded="false" aria-controls="collapseMitadPrecio">
                            Mostrar botón para aplicar descuento del 50%
                        </button>
                    </h2>
                    <div id="collapseMitadPrecio" class="accordion-collapse collapse" aria-labelledby="headingMitadPrecio">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label for="mostrar_mitad_precio" class="form-label">¿Desea mostrar el botón para aplicar descuento del 50%?</label>

                                        <select id="mostrar_mitad_precio" class="form-select" onchange="actualizarBoton(this.value)">
                                            <option value="t" <?= $mostrar_boton_mitad === 't' ? 'selected' : '' ?>>Sí</option>
                                            <option value="f" <?= $mostrar_boton_mitad === 'f' ? 'selected' : '' ?>>No</option>
                                        </select>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-4">
            <div class="accordion" id="accordionMesero">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingMesero">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMesero" aria-expanded="false" aria-controls="collapseMesero">
                            Usuario que registró el producto en el pedido
                        </button>
                    </h2>
                    <div id="collapseMesero" class="accordion-collapse collapse" aria-labelledby="headingMesero">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <label for="mostrar_mesero" class="form-label">¿Desea mostrar el usuario que registró el producto en el pedido?</label>

                                        <select id="mostrar_mesero" class="form-select" onchange="actualizarMesero(this.value)">
                                            <option value="t" <?= $mesero === 't' ? 'selected' : '' ?>>Sí</option>
                                            <option value="f" <?= $mesero === 'f' ? 'selected' : '' ?>>No</option>
                                        </select>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-4">
            <div class="accordion" id="accordionNota">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingNota">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseNota"
                            aria-expanded="false"
                            aria-controls="collapseNota">
                            En la mesas mostrar mesero o nota
                        </button>
                    </h2>

                    <div id="collapseNota" class="accordion-collapse collapse " aria-labelledby="headingNota" data-bs-parent="#accordionNota">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col d-flex align-items-center">
                                    <div class="flex-grow-1">

                                        <label for="mostrar_mesero" class="form-label">
                                            ¿En la vista general de las mesas mostrar el mesero o la nota?
                                        </label>

                                        <select id="mostrar_mesero" class="form-select" onchange="actualizarNota(this.value)">
                                            <option value="f" <?= $nota === 'f' ? 'selected' : '' ?>>Mesero</option>
                                            <option value="t" <?= $nota === 't' ? 'selected' : '' ?>>Nota</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>


</div>

<script>
    async function actualizarMesero(valor) {

        try {
            let url = document.getElementById("url").value;
            const response = await fetch(url + '/actualizacion/actualizar_mesero', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    mostrar_boton_mitad: valor
                })
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire('Actualizado', 'La configuración fue guardada correctamente', 'success');
            } else {
                Swal.fire('Error', data.mensaje || 'No se pudo guardar la configuración', 'error');
            }
        } catch (error) {
            console.error('Error al actualizar:', error);
            Swal.fire('Error', 'Ocurrió un problema al comunicarse con el servidor', 'error');
        }
    }
</script>

<script>
    async function actualizarNota(valor) {

        try {
            let url = document.getElementById("url").value;
            const response = await fetch(url + '/actualizacion/actualizar_nota', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    mostrar_boton_mitad: valor
                })
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire('Actualizado', 'La configuración fue guardada correctamente', 'success');
            } else {
                Swal.fire('Error', data.mensaje || 'No se pudo guardar la configuración', 'error');
            }
        } catch (error) {
            console.error('Error al actualizar:', error);
            Swal.fire('Error', 'Ocurrió un problema al comunicarse con el servidor', 'error');
        }
    }
</script>

<script>
    async function actualizarMesero(valor) {

        try {
            let url = document.getElementById("url").value;
            const response = await fetch(url + '/actualizacion/actualizar_mesero', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    mostrar_boton_mitad: valor
                })
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire('Actualizado', 'La configuración fue guardada correctamente', 'success');
            } else {
                Swal.fire('Error', data.mensaje || 'No se pudo guardar la configuración', 'error');
            }
        } catch (error) {
            console.error('Error al actualizar:', error);
            Swal.fire('Error', 'Ocurrió un problema al comunicarse con el servidor', 'error');
        }
    }
</script>
<script>
    async function actualizarBoton(valor) {

        try {
            let url = document.getElementById("url").value;
            const response = await fetch(url + '/actualizacion/actualizar_boton_mitad', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    mostrar_boton_mitad: valor
                })
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire('Actualizado', 'La configuración fue guardada correctamente', 'success');
            } else {
                Swal.fire('Error', data.mensaje || 'No se pudo guardar la configuración', 'error');
            }
        } catch (error) {
            console.error('Error al actualizar:', error);
            Swal.fire('Error', 'Ocurrió un problema al comunicarse con el servidor', 'error');
        }
    }
</script>

<script>
    function actualizar_codigo(opcion) {
        let url = document.getElementById("url").value;
        $.ajax({
            data: {
                opcion,
            },
            url: url + "/" + "actualizacion/actualizar_codigo",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-start',
                        showConfirmButton: false,
                        timer: 900,
                        timerProgressBar: false,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Visualización de código en pantalla '
                    });

                }
            },
        });
    }
</script>

<script>
    function actualizar_altura() {
        let url = document.getElementById("url").value;
        let altura = document.getElementById("altura_de_pantalla").value;
        $.ajax({
            data: {
                altura,
            },
            url: url + "/" + "actualizacion/actualizar_altura",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-start',
                        showConfirmButton: false,
                        timer: 900,
                        timerProgressBar: false,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Altura de pantalla actualizada  '
                    });

                }
            },
        });
    }
</script>

<?= $this->endSection('content') ?>