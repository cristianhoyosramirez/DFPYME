<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
Configuración
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<p class="text-center h3 text-primary">Gestión de comanda</p><br>
<div class="card container">
    <input type="hidden" value="<?php echo base_url() ?>" id="url">


    <div class="card-body">




        <div class="container row">

            <div class="col-3">
                <label class="form-label">Reimprimir comanda</label>
                <select name="" id="" class="form-select" onchange="reImpresionComanda(this.value)">
                    <option value="true" <?= $reimpresion === 't' ? 'selected' : '' ?>>Si</option>
                    <option value="false" <?= $reimpresion === 'f' ? 'selected' : '' ?>>No</option>
                </select>

            </div>
            <!--<div class="col-3">
                <label class="form-label">Criterio impresión comanda</label>
                <select name="" id="" class="form-select" onchange="impresionComanda(this.value)">
                    <option value="1">Categoria</option>
                    <option value="2">Producto</option>
                    <option value="3">Grupo</option>
                </select>
            </div> -->
            <div class="col">
                <div id="grupoImpresion">

                    <?= $this->include('configuracion/grupo_impresion') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>

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