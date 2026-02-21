<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<form class="row g-3" id="edicion_cliente_electronico" method="POST" action="<?php echo base_url() ?>/clientes/actualizar_datos_cliente">

    <input type="hidden" id="id_cliente" value="<?php echo $id_cliente ?>" name="id_cliente">
    <input type="text" class="form-control" id="name" value="<?php echo $datos_cliente['name'] ?>" hidden>
    <input type="text" class="form-control" id="nombresCliente" value="<?php echo $datos_cliente['nombrescliente'] ?>" hidden>
    <input type="text" class="form-control" id="apellidosCliente" value="<?php echo $datos_cliente['last_name'] ?>" hidden>


    <div class="col-md-3">
        <label for="inputEmail4" class="form-label">Tipo de persona</label>
        <select class="form-select" id="tipo_of_persona" name="tipo_depersona" onchange="selectTipoPersonaEdicion(this.value)">
            <option value="2" <?= ($datos_cliente['type_person'] == 2) ? 'selected' : '' ?>>Natural</option>
            <option value="1" <?= ($datos_cliente['type_person'] == 1) ? 'selected' : '' ?>>Jurídica</option>
        </select>
        <span class="text-danger error-text tipo_persona_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputPassword4" class="form-label">
            Tipo de documento
        </label>
        <?php $identificacion = model('TiposDocumento')->findALL(); ?>

        <select class="form-select" id="tipo_de_documento" name="tipo_documento">
            <option value="13" <?= ($datos_cliente['type_document'] == 13) ? 'selected' : '' ?>>CC</option>
            <option value="31" <?= ($datos_cliente['type_document'] == 31) ? 'selected' : '' ?>>NIT</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Número de identificación y DV</label>
        <div class="input-group">
            <input type="text" class="form-control"
                id="identificacion"
                name="identificacion"
                placeholder="Identificación"
                autocomplete="off"
                autocorrect="off"
                autocapitalize="off"
                spellcheck="false"
                onkeyup="calcularYMostrarDVEdicion(this.value);"
                value="<?php echo $datos_cliente['nitcliente']; ?>"

                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                inputmode="numeric"
                autocomplete="off"
                autocorrect="off"
                autocapitalize="off"
                spellcheck="false"
                required
                maxlength="15"
                >

            <input type="text"
                value="<?php echo $datos_cliente['dv']; ?>"
                class="form-control" id="dvEdicion" name="dv" placeholder="DV" style="max-width: 80px;" onkeyup="saltar_factura_pos(event,'nombres');" readonly id="dvEdicion">
        </div>
        <span class="text-danger error-text identificacion_error"></span>
        <span class="text-danger error-text dv_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Régimen</label>
        <?php $regimen = model('regimenModel')->findALL(); ?>
        <select class="form-select" aria-label="Default select example" id="regimen_cliente" name="regimen">
            <?php foreach ($regimen as $detalle) : ?>

                <option value="<?php echo $detalle['idregimen'] ?>" <?php if ($detalle['idregimen'] == $datos_cliente['idregimen']) : ?>selected <?php endif; ?>><?php echo $detalle['nombreregimen'] ?> </option>

            <?php endforeach  ?>
        </select>

    </div>


    <div id="tipoPersona">
        <!-- Nombre y apellidos (Natural) -->
        <?php if ($datos_cliente['type_person'] == 2): ?>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">nombres</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" onkeyup="saltar_factura_pos(event,'apellidos')" value="<?php echo ($datos_cliente['name']); ?>" required>
                    <span class="text-danger error-text nombres_error"></span>
                </div>
                <div class="col-md-6">
                    <label class="form-label">apellidos</label>
                    <input type="text" class="form-control" id="apellidos_edicion" name="apellidos_edicion" onkeyup="saltar_factura_pos(event,'direccion')" value="<?php echo ($datos_cliente['last_name']); ?>" required>
                    <span class="text-danger error-text apellidos_error"></span>
                </div>
            </div>
        <?php endif ?>


        <?php if ($datos_cliente['type_person'] == 1): ?>
            <div class="col-md-12">
                <label for="inputCity" class="form-label">Razón social
                </label>
                <input type="text" class="form-control" id="razon_social_edicion" name="razon_social_edicion" onkeyup="saltar_factura_pos(event,'nombre_comercial')" value="<?php echo  $datos_cliente['nombrescliente'] ?>">
                <span class="text-danger error-text razon_social_error"></span>
            </div>
        <?php endif ?>
    </div>


    <div class="col-md-4">
        <label for="inputCity" class="form-label">Dirección
        </label>
        <input type="text" class="form-control" id="direccion" name="direccion" onkeyup="saltar_factura_pos(event,'departamento')" value="<?php echo  $datos_cliente['direccioncliente'] ?>">
        <span class="text-danger error-text direccion_error"></span>
    </div>
    <div class="col-md-4">
        <label for="inputCity" class="form-label">Departamento
        </label>
        <?php $departamento = model('departamentoModel')->where('idpais', 49)->findAll(); ?>
        <select class="form-select" name="departamento_cliente" id="departamento_cliente" onchange="departamentoCiudadEditar()">
            <?php foreach ($departamento as $detalle) : ?>

                <option value="<?php echo $detalle['iddepartamento'] ?>" <?php if ($detalle['iddepartamento'] == $id_departamento) : ?>selected <?php endif; ?>><?php echo $detalle['nombredepartamento'] . "-" . $detalle['code']  ?> </option>
            <?php endforeach  ?>
        </select>
        <span class="text-danger error-text departamento_error"></span>
    </div>
    <div class="col-md-4">
        <label for="inputCity" class="form-label">Ciudad
        </label>
        <?php $ciudad = model('ciudadModel')->where('iddepartamento', $id_departamento)->orderBy('nombreciudad', 'asc')->findAll();  ?>

        <select class="form-select" id="ciudad_cliente_edicion" name="ciudad_edicion">
            <?php foreach ($ciudad as $detalle) : ?>

                <option value="<?php echo $detalle['idciudad'] ?>" <?php if ($detalle['idciudad'] == $datos_cliente['idciudad']) : ?>selected <?php endif; ?>><?php echo $detalle['nombreciudad']   ?> </option>
            <?php endforeach ?>

        </select>
        <span class="text-danger error-text ciudad_error"></span>
    </div>

    <div class="col-md-4">
        <label for="inputCity" class="form-label">Correo electrónico
        </label>
        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo  $datos_cliente['emailcliente'] ?>" required>
        <span class="text-danger error-text correo_electronico_error"></span>
    </div>
    <div class="col-md-4">
        <label for="inputCity" class="form-label">Teléfono /celular
        </label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo  $datos_cliente['celularcliente'] ?>">
        <span class="text-danger error-text telefono_error"></span>
    </div>

    <!-- Botones -->
    <div class="modal-footer">
        <div class="row w-100">
            <div class="col">
                <button type="submit" class="btn btn-outline-success w-100" id="btn_editar_cliente">Actualizar </button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-outline-danger w-100" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>

</form>

<!-- JQuery 
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>-->
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/departamentoCiudad.js"></script>
<!--select2 -->
<script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/select_2.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/departamentoCiudad.js"></script>


<script>
    function calcularYMostrarDVEdicion(nit) {
        console.log(nit)
        const dv = calcularDigitoVerificacion(nit);
        document.getElementById('dvEdicion').value = dv;
    }
</script>


<script>
    function selectTipoPersonaEdicion(tipo) {
        let nombre = document.getElementById('name').value;
        let nombresCliente = document.getElementById('nombresCliente').value;
        let apellidosCliente = document.getElementById('apellidosCliente').value;
        let razonSocial = document.getElementById('razonSocialOriginal')?.value || ''; // opcional

        let contenedor = document.getElementById('tipoPersona');

        const selecciontTipoDocumento = document.getElementById("tipo_of_persona");
        const contenedorSelect = document.getElementById('tipo_de_documento');

        if (tipo == 2) {
            // Persona Natural: mostrar nombres y apellidos
            contenedor.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nombres</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" 
                           onkeyup="saltar_factura_pos(event,'apellidos')" 
                           value="${nombresCliente}" required>
                    <span class="text-danger error-text nombres_error"></span>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellidos_edicion" name="apellidos_edicion" 
                           onkeyup="saltar_factura_pos(event,'direccion')" 
                           value="${apellidosCliente}" required>
                    <span class="text-danger error-text apellidos_error"></span>
                </div>
            </div>
        `;
            // para que Select2 actualice el valor visualmente
            contenedorSelect.innerHTML = `
                    <select class="form-select" id="tipo_of_persona" name="tipo_depersona" onchange="selectTipoPersonaEdicion(this.value)">
                        <option value="13" selected>CC</option>
                        <option value="31" >NIT</option>
                    </select>
    `;
        } else if (tipo == 1) {
            // Persona Jurídica: mostrar razón social
            contenedor.innerHTML = `
            <div class="col-md-12">
                <label for="razon_social_edicion" class="form-label">Razón social</label>
                <input type="text" class="form-control" id="razon_social_edicion" name="razon_social_edicion" 
                       onkeyup="saltar_factura_pos(event,'nombre_comercial')" 
                       value="${razonSocial}">
                <span class="text-danger error-text razon_social_error"></span>
            </div>
        `;

            contenedorSelect.innerHTML = `
                    <select class="form-select" id="tipo_of_persona" name="tipo_depersona" onchange="selectTipoPersonaEdicion(this.value)">
                        <option value="13" >CC</option>
                        <option value="31" selected>NIT</option>
                    </select>
    `;
        } else {
            // Limpiar el contenedor si tipo no es válido
            contenedor.innerHTML = '';
        }
    }
</script>



<script>
    function departamentoCiudadEditar() {

        var url = document.getElementById("url").value;
        var valorSelect1 = document.getElementById("departamento_cliente").value;


        $.ajax({
            data: {
                valorSelect1

            },
            url: url +
                "/" +
                "eventos/municipios",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    $('#ciudad_cliente_edicion').html(resultado.ciudad)
                    $('#confirmar_municipios_cliente').html(resultado.ciudad)
                    //$('#municipios').html(resultado.municipios)

                }

                if (resultado.resultado == 0) {
                    alert('No se puede actualizar ')
                }
            },
        });



    }
</script>


<script>
    $("#tipo_of_persona").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true,
        minimumResultsForSearch: Infinity
    });
    $("#tipo_de_documento").select2({
        width: "100%",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true,
        minimumResultsForSearch: -1 // <--- Oculta la caja de búsqueda
    });
    $("#regimen_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#departamento_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#regimen_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#ciudad_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#confirmar_municipios_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#codigo_postal_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#impuestos_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#responsabilidad_fiscal_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#tipo_ventas_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#clasificacion_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
</script>



<script>
    $('#edicion_cliente_electronico').submit(function(e) {
        e.preventDefault();
        var form = this;
        let button = document.querySelector("#btn_editar_cliente");
        button.disabled = true;
        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: new FormData(form),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function() {
                $(form).find('span.error-text').text('');
                button.disabled = false;
            },
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    if (data.code == 1) {
                        $(form)[0].reset();

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            icon: 'success',
                            title: 'Datos de cliente actualizado correctamente  '
                        });

                        // Recargar la página después de 3 segundos (3000 milisegundos)
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(data.msg);
                    }
                } else {
                    $.each(data.error, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            }
        });
    });
</script>