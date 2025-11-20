<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
Configuración
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<p class="text-center h3 text-primary">Gestión de comanda</p><br>
<div class="card container">
    <input type="hidden" value="<?php echo base_url() ?>" id="url">


    <div class="card-body">



        <div class="container">
            <div class="row">
                <div class="col">
                    <div id="grupoImpresion">
                        <?= $this->include('configuracion/grupo_impresion') ?>
                    </div>
                </div>
            </div>
            <p class="text-center h3 text-primary">Parametrización de comanda</p>
            <div class="row mt-3"> <!-- Añadí un margen superior para mejor separación visual -->
                <div class="col-3">
                    <label class="form-label">Reimprimir comanda</label>
                    <select name="" id="" class="form-select" onchange="reImpresionComanda(this.value)">
                        <option value="true" <?= $reimpresion === 't' ? 'selected' : '' ?>>Si</option>
                        <option value="false" <?= $reimpresion === 'f' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-3">
                    <label for="" class="form-label">Espacios en el encabezado</label>
                    <input
                        type="number"
                        class="form-control"
                        id="inputEncabezado"
                        value="<?= $encabezado ?>"
                        oninput="validarEncabezadoNumero(this, 'actualizarEncabezado')"
                        min="0"
                        step="1">

                    <span class="text-danger " id="errorEncabezado"></span>
                </div>

                <div class="col-3">
                    <label for="" class="form-label">Espacios en el pie de comanda</label>
                    <input
                        type="number"
                        class="form-control"
                        id="inputPie"
                        value="<?= $pie ?>"
                        oninput="validarSoloNumeros(this, 'actualizarPie')"
                        min="0"
                        step="1">

                    <span class="text-danger " id="errorPie"></span>
                </div>
                <div class="col-3">
                    <label class="form-label">Tamaño de nota de la comanda</label>
                    <select name="" id="tamanocomanda" class="form-select" onchange="tamanocomanda(this.value)">
                        <option value="grande" <?= $tamano == 'grande' ? 'selected' : '' ?>>Grande</option>
                        <option value="mediana" <?= $tamano == 'mediana' ? 'selected' : '' ?>>Mediana</option>
                        <option value="pequena" <?= $tamano == 'pequena' ? 'selected' : '' ?>>Pequeña</option>
                    </select>

                </div>
                <div class="col-3">
                    <label class="form-label">Impresión de precios en comanda </label>
                    <select name="" id="imprimirPrecios" class="form-select" onchange="impresionPrecios(this.value)">
                        <option value="t" <?= $precios == 't' ? 'selected' : '' ?>>Si</option>
                        <option value="f" <?= $precios == 'f' ? 'selected' : '' ?>>No</option>
                    </select>

                </div>

                <div class="col-3">
                    <label class="form-label">Beep impresora  </label>
                    <select name="" id="imprimirPrecios" class="form-select" onchange="beepImpresora(this.value)">
                        <option value="t" <?= $precios == 't' ? 'selected' : '' ?>>Si</option>
                        <option value="f" <?= $precios == 'f' ? 'selected' : '' ?>>No</option>
                    </select>

                </div>
                <div class="col-3">
                    <label class="form-label">Reimpresion comanda mesero   </label>
                    <select name="" id="reImprimirMesero" class="form-select" onchange="reImprimirMesero(this.value)">
                        <option value="t" <?= $reimprimir_meseros == 't' ? 'selected' : '' ?>>Si</option>
                        <option value="f" <?= $reimprimir_meseros == 'f' ? 'selected' : '' ?>>No</option>
                    </select>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>



<script>
    async function beepImpresora(valor) {

        try {
            let respuesta = await fetch("<?= base_url('configuracion/beep') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // para detectar llamadas AJAX
                },
                body: JSON.stringify({
                    valor: valor
                })
            });

            const resultado = await respuesta.json();

            if (resultado.response == "success") {
                sweet_alert_centrado('success', 'Se imprimen los precios en la comanda');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resultado.message || 'No se pudo actualizar.'
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de red o del servidor.'
            });
        }
    }
</script>

<script>
    async function reImprimirMesero(valor) {

        try {
            let respuesta = await fetch("<?= base_url('configuracion/reImprimirMesero') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // para detectar llamadas AJAX
                },
                body: JSON.stringify({
                    valor: valor
                })
            });

            const resultado = await respuesta.json();

            if (resultado.response == "success") {
                sweet_alert_centrado('success', 'Se imprimen los precios en la comanda');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resultado.message || 'No se pudo actualizar.'
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de red o del servidor.'
            });
        }
    }
</script>

<script>
    async function impresionPrecios(valor) {

        try {
            let respuesta = await fetch("<?= base_url('configuracion/precios_comanda') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // para detectar llamadas AJAX
                },
                body: JSON.stringify({
                    valor: valor
                })
            });

            const resultado = await respuesta.json();

            if (resultado.response == "success") {
                sweet_alert_centrado('success', 'Se imprimen los precios en la comanda');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resultado.message || 'No se pudo actualizar.'
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de red o del servidor.'
            });
        }
    }
</script>
<script>
    async function tamanocomanda(valor) {

        try {
            let respuesta = await fetch("<?= base_url('configuracion/actualizar_tamano') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // para detectar llamadas AJAX
                },
                body: JSON.stringify({
                    tamano: valor
                })
            });

            const resultado = await respuesta.json();

            if (resultado.response == "success") {
                sweet_alert_centrado('success', 'Tamaño actualizado');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resultado.message || 'No se pudo actualizar.'
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de red o del servidor.'
            });
        }
    }
</script>



<script>
    function validarEncabezadoNumero(input, funcionCallback) {
        const valorOriginal = input.value;

        // Eliminar todo lo que no sea número
        const valorLimpio = valorOriginal.replace(/[^0-9]/g, '');

        // Reasignar el valor filtrado al input
        input.value = valorLimpio;

        const errorSpan = document.getElementById('errorEncabezado');
        errorSpan.innerHTML = "";

        if (valorLimpio.trim() === "") {
            errorSpan.innerHTML = "El valor no puede estar vacío";
            return;
        }

        // Si todo está correcto, llama la función
        if (typeof window[funcionCallback] === 'function') {
            window[funcionCallback](valorLimpio);
        }
    }
</script>


<script>
    function validarSoloNumeros(input, funcionCallback) {
        const valorOriginal = input.value;

        // Eliminar caracteres no numéricos
        const valorLimpio = valorOriginal.replace(/[^0-9]/g, '');

        // Reemplaza en el campo lo ingresado con lo limpio
        input.value = valorLimpio;

        // Referencia al span de error (solo si es el inputPie)
        const errorSpan = input.id === 'inputPie' ? document.getElementById('errorPie') : null;
        if (errorSpan) errorSpan.innerHTML = "";

        // Validar si quedó vacío
        if (valorLimpio.trim() === "") {
            if (errorSpan) errorSpan.innerHTML = "El valor no puede estar vacío";
            return;
        }

        // Validación correcta → llamar función deseada
        if (typeof window[funcionCallback] === 'function') {
            window[funcionCallback](valorLimpio);
        }
    }
</script>



<script>
    async function actualizarPie(valor) {
        // Validar que el valor no esté vacío, nulo o solo espacios
        document.getElementById('errorPie').innerHTML = ""

        console.log(valor)

        if (!valor || valor.trim() === "") {
            document.getElementById('errorPie').innerHTML = "El valor no puede esta vacio "
            return;
        }

        try {
            let response = await fetch("<?= base_url('actualizacion/pieComanda') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valor: valor,
                })
            });

            if (response.ok) {
                const resultado = await response.json();
                if (resultado.response === "success") {
                    sweet_alert_centrado('success', 'Datos actualizados');
                }
            } else {
                const error = await response.text();
                alert("Error al crear el grupo: " + error);
            }
        } catch (error) {
            console.error("Error de red:", error);
            alert("Ocurrió un error al intentar crear el grupo.");
        }
    }
</script>

<script>
    async function actualizarEncabezado(valor) {

        document.getElementById('errorEncabezado').innerHTML = ""

        if (!valor || valor.trim() === "") {
            document.getElementById('errorEncabezado').innerHTML = "El valor no puede esta vacio "
            return;
        }

        try {
            let response = await fetch("<?= base_url('actualizacion/encabezadoComanda') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valor: valor,
                })
            });

            if (response.ok) {
                const resultado = await response.json();
                if (resultado.response = "success") {
                    sweet_alert_centrado('success', 'Datos actualizados')
                }
            } else {
                const error = await response.text();
                alert("Error al crear el grupo: " + error);
            }
        } catch (error) {
            console.error("Error de red:", error);
            alert("Ocurrió un error al intentar crear el grupo.");
        }
    }
</script>
<script>
    async function reImpresionComanda(valor) {

        try {
            let response = await fetch("<?= base_url('configuracion/reimpresionComanda') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valor: valor,
                })
            });

            if (response.ok) {
                const resultado = await response.json();
                if (resultado.response = "success") {
                    sweet_alert_centrado('success', 'Datos actualizados')
                }
            } else {
                const error = await response.text();
                alert("Error al crear el grupo: " + error);
            }
        } catch (error) {
            console.error("Error de red:", error);
            alert("Ocurrió un error al intentar crear el grupo.");
        }
    }
</script>

<script>
    function limpiarSpan() {
        document.getElementById('errorNombre').innerHtml = ""
    }
</script>

<script>
    async function actualizarGrupo(id) {
        const nombre = document.getElementById("nombre" + id).value.trim();
        const impresora = document.getElementById("impresora" + id).value.trim();



        try {
            let response = await fetch("<?= base_url('configuracion/updateDatosGrupoImpresion') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre: nombre,
                    idImpresora: impresora,
                    idGrupo: id
                })
            });

            if (response.ok) {
                const resultado = await response.json();
                if (resultado.response = "success") {
                    sweet_alert_centrado('success', 'Datos actualizados')
                }
            } else {
                const error = await response.text();
                alert("Error al crear el grupo: " + error);
            }
        } catch (error) {
            console.error("Error de red:", error);
            alert("Ocurrió un error al intentar crear el grupo.");
        }
    }
</script>

<script>
    async function actualizarNumeroCopias(value) {

        try {
            let response = await fetch("<?= base_url('configuracion/actualizarNumeroCopias') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    numero: value,

                })
            });

            if (response.ok) {
                const resultado = await response.json();
                if (resultado.response = "success") {
                    sweet_alert_centrado('success', 'Datos actualizados')
                }
            } else {
                const error = await response.text();
                //alert("Error al crear el grupo: " + error);
            }
        } catch (error) {
            console.error("Error de red:", error);
            //alert("Ocurrió un error al intentar crear el grupo.");
        }
    }
</script>


<script>
    async function eliminacioGrupo(id) {

        try {
            let response = await fetch("<?= base_url('configuracion/deleteDatosGrupoImpresion') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idGrupo: id
                })
            });

            if (response.ok) {
                const resultado = await response.json();
                if (resultado.response = "success") {
                    document.getElementById('grupo' + resultado.id).remove();
                    sweet_alert_centrado('success', 'Grupo borrado')
                }
            } else {
                const error = await response.text();
                alert("Error al crear el grupo: " + error);
            }
        } catch (error) {
            console.error("Error de red:", error);
            alert("Ocurrió un error al intentar crear el grupo.");
        }
    }
</script>

<script>
    async function generarGrupo() {
        const nombre = document.getElementById("nombreGrupo").value.trim();
        const idImpresora = document.getElementById("idImpresora").value.trim();
        const copias = document.getElementById("numeroImpresiones").value.trim();

        if (!nombre) {
            document.getElementById('errorNombre').innerHTML = "Debe haber un nombre"
            return;
        }

        try {
            let response = await fetch("<?= base_url('configuracion/crearGrupoImpresion') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre: nombre,
                    idImpresora: idImpresora,
                    copias: copias
                })
            });

            if (response.ok) {
                const resultado = await response.json();
                if (resultado.response == 'success') {
                    document.getElementById('nombreGrupo').value = ""
                    document.getElementById('gruposImpresion').innerHTML = resultado.grupos
                    $('#newGroup').modal('hide')
                    sweet_alert_centrado('success', 'Grupo creado ')
                }
            } else {
                const error = await response.text();
                alert("Error al crear el grupo: " + error);
            }
        } catch (error) {
            console.error("Error de red:", error);
            alert("Ocurrió un error al intentar crear el grupo.");
        }
    }
</script>

<script>
    async function impresionComanda(valor) {
        try {
            // Enviar el valor al servidor usando fetch
            let response = await fetch("<?= base_url('configuracion/updateCriterioComanda') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    valor: valor
                })
            });

            // Verificar si la respuesta fue exitosa
            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }

            // Procesar la respuesta del servidor
            const resultado = await response.json();
            if (resultado.response = "success") {
                document.getElementById('grupoImpresion').innerHTML = resultado.grupo
                sweet_alert_start('success', 'Criterio de impresión actualizado ');
            }

            // Puedes mostrar un mensaje al usuario

        } catch (error) {
            console.error("Error en impresionComanda:", error);
            alert("Ocurrió un error al enviar la comanda.");
        }
    }
</script>


<script>
    const switchInput = document.getElementById('flexSwitchCheckDefault');
    const label = document.querySelector('.form-check-label');

    switchInput.addEventListener('change', function() {
        if (this.checked) {
            label.textContent = 'Sí';
            this.value = true;
        } else {
            label.textContent = 'No';
            this.value = false;
        }
    });
</script>


<script>
    function actualizar_comanda() {

        const temp_valor = document.getElementById('flexSwitchCheckDefault').value;

        if (temp_valor == "false") {
            valor = "true"
        }

        if (temp_valor == "true") {
            valor = "false"
        }


        let url = document.getElementById("url").value;

        $.ajax({
            data: {
                valor
            },
            url: url + "/" + "configuracion/actualizar_comanda",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    // Aquí puedes agregar lo que necesitas hacer si resultado.resultado es 1

                    sweet_alert_start('success', 'Configuración de propina actualizada   ');


                }
            },
        });

    }
</script>


<?= $this->endSection('content') ?>