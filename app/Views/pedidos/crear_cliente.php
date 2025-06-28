<!-- Modal -->
<div class="modal fade" id="crear_cliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">
          Crear cliente
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" id="creacion_cliente_electronico" method="POST" action="<?= base_url() ?>/clientes/agregar">
          <?php $datos_empresa = model('empresaModel')->datosEmpresa(); //var_dump($datos_empresa);   
          ?>
          <!-- Tipo de persona -->
          <div class="col-md-3">
            <label for="tipo_persona" class="form-label">Tipo de persona</label>
            <select class="form-select" id="tipo_persona" name="tipo_persona" onchange="selectTipoPersona(this.value)">
              <option value="2" selected>Natural</option>
              <option value="1">Jurídica</option>
            </select>
            <span class="text-danger error-text tipo_persona_error"></span>
          </div>

          <!-- Tipo de documento -->
          <div class="col-md-3">
            <label class="form-label">Tipo de documento</label>
            <select class="form-select" id="tipo_documento" name="tipo_documento">
              <option value="13" selected>CC</option>
              <option value="31">NIT </option>
            </select>
          </div>

          <!--  
          <div class="col-md-3">
            <label class="form-label">Número de identificación</label>
            <input type="text" class="form-control" id="identificacion" name="identificacion" onkeyup="calcularYMostrarDV(this.value);">
            <span class="text-danger error-text identificacion_error"></span>
          </div>

          <div class="col-md-3">
            <label class="form-label">DV</label>
            <input type="text" class="form-control" id="dv" name="dv" onkeyup="saltar_factura_pos(event,'nombres');">
            <span class="text-danger error-text dv_error"></span>
          </div> -->

          <!-- Identificación y DV en un solo grupo -->
          <div class="col-md-3">
            <label class="form-label">Número de identificación y DV</label>
            <div class="input-group">
              <input type="text" class="form-control"
                id="identificacion"
                name="identificacion"
                placeholder="Identificación"
                onkeyup="calcularYMostrarDV(this.value);"
                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                inputmode="numeric"
                autocomplete="off"
                autocorrect="off"
                autocapitalize="off"
                spellcheck="false"
                required
                maxlength="15">

              <input type="text" readonly class="form-control" id="dv" name="dv" placeholder="DV" style="max-width: 80px;" onkeyup="saltar_factura_pos(event,'nombres');">
            </div>
            <span class="text-danger error-text identificacion_error"></span>
            <span class="text-danger error-text dv_error"></span>
          </div>


          <div class="col-md-3">
            <label for="inputCity" class="form-label">Régimen</label>
            <?php $regimen = model('regimenModel')->findALL(); ?>
            <select class="form-select" aria-label="Default select example" id="regimen" name="regimen">
              <?php foreach ($regimen as $detalle) : ?>

                <option value="<?php echo $detalle['idregimen'] ?>"><?php echo $detalle['nombreregimen'] ?></option>

              <?php endforeach  ?>
            </select>
          </div>

          <!-- Nombre y apellidos (Natural) -->
          <div id="nombreApellidos" class="row">
            <div class="col-md-6">
              <label class="form-label">Nombres</label>
              <input type="text" class="form-control" id="nombres" name="nombres" onkeyup="saltar_factura_pos(event,'apellidos')" required>
              <span class="text-danger error-text nombres_error"></span>
            </div>
            <div class="col-md-6">
              <label class="form-label">Apellidos</label>
              <input type="text" class="form-control" id="apellidos" name="apellidos" onkeyup="saltar_factura_pos(event,'direccion')" required>
              <span class="text-danger error-text apellidos_error"></span>
            </div>
          </div>

          <!-- Nombre comercial (Jurídica) -->
          <div id="nombreComercial" class="row d-none">
            <div class="col-md-12">
              <label for="inputCity" class="form-label">Razón social
              </label>
              <input type="text" class="form-control" id="razon_social" name="razon_social" onkeyup="saltar_factura_pos(event,'nombre_comercial')">
              <span class="text-danger error-text razon_social_error"></span>
            </div>
          </div>

          <!-- Dirección -->
          <div class="col-md-4">
            <label class="form-label">Dirección</label>
            <input value="<?php echo $datos_empresa[0]['direccionempresa']; ?>" type="text" class="form-control" id="direccion" name="direccion" onkeyup="saltar_factura_pos(event,'departamento')">
            <span class="text-danger error-text direccion_error"></span>
          </div>

          <!-- Departamento -->
          <div class="col-md-4">
            <label class="form-label">Departamento</label>
            <?php $departamento = model('departamentoModel')->where('idpais', 49)->findAll(); ?>
            <select class="form-select" name="departamento" id="departamento" onchange="departamentoCiudad()">
              <?php foreach ($departamento as $detalle) : ?>
                <option value="<?= $detalle['iddepartamento'] ?>" <?= ($detalle['iddepartamento'] == $datos_empresa[0]['iddepartamento']) ? 'selected' : '' ?>>
                  <?= $detalle['nombredepartamento'] . " - " . $detalle['code'] ?>
                </option>
              <?php endforeach; ?>
            </select>

            <span class="text-danger error-text departamento_error"></span>
          </div>


          <?php
          $ciudades = model('ciudadModel')
            ->select('*')
            ->where('iddepartamento', $datos_empresa[0]['iddepartamento'])
            ->findAll();

          ?>




          <!-- Ciudad -->
          <div class="col-md-4">
            <label class="form-label">Ciudad</label>
            <select class="form-select" id="ciudad" name="ciudad">
              <option value="<?php echo $datos_empresa[0]['idciudad'];  ?>"><?php echo $datos_empresa[0]['nombreciudad'];  ?></option>
              <?php foreach ($ciudades as $detalleCiudades): ?>
                <option value="<?php $detalleCiudades['idciudad'] ?>"><?php echo $detalleCiudades['nombreciudad'];  ?></option>
              <?php endforeach ?>
            </select>
            <span class="text-danger error-text ciudad_error"></span>
          </div>

          <!-- Confirmar ciudad 
          <div class="col-md-3">
            <label class="form-label">Confirmar ciudad</label>
            <select class="form-select" name="municipios" id="municipios">
              <option value="<?php echo $datos_empresa[0]['idciudad'];  ?>"><?php echo $datos_empresa[0]['nombreciudad'];  ?></option>
            </select>
            <span class="text-danger error-text municipios_error"></span>
          </div>-->
          <?php
          $consumidorFinal = model('clientesModel')->select('direccioncliente,emailcliente,telefonocliente')->where('nitcliente', '222222222222')->first();
          ?>
          <!-- Correo y Teléfono -->
          <div class="col-md-4">
            <label class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo $consumidorFinal['emailcliente']; ?>" required>
            <span class="text-danger error-text correo_electronico_error"></span>
          </div>

          <div class="col-md-4">
            <label class="form-label">Teléfono / Celular</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $consumidorFinal['telefonocliente']; ?>">
            <span class="text-danger error-text telefono_error"></span>
          </div>

          <!-- Botones -->
          <div class="modal-footer">
            <div class="row w-100">
              <div class="col">
                <button type="submit" class="btn btn-outline-success w-100" id="btn_crear_cliente">Crear</button>
              </div>
              <div class="col">
                <button type="button" class="btn btn-outline-danger w-100" data-bs-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>

<!-- <script>
  function selectTipoPersona(tipo) {
    const divNombreComercial = document.getElementById("nombreComercial");
    const divNombreApellidos = document.getElementById("nombreApellidos");

    if (tipo === '1') { // Jurídica
      divNombreApellidos.classList.add("d-none");
      divNombreComercial.classList.remove("d-none");
      divNombreComercial.classList.add("d-flex");
    } else { // Natural
      divNombreComercial.classList.add("d-none");
      divNombreComercial.classList.remove("d-flex");
      divNombreApellidos.classList.remove("d-none");
    }
  }

  // Ejecutar al cargar para aplicar el valor actual del selector
  document.addEventListener("DOMContentLoaded", function() {
    const tipo = document.getElementById("tipo_persona").value;
    selectTipoPersona(tipo);
  });
</script> -->

<script>
  function selectTipoPersona(tipo) {
    const divNombreComercial = document.getElementById("nombreComercial");
    const divNombreApellidos = document.getElementById("nombreApellidos");
    const selectTipoDocumento = document.getElementById("tipo_documento");

    console.log(tipo)

    if (tipo === '1') { // Jurídica
      divNombreApellidos.classList.add("d-none");
      divNombreComercial.classList.remove("d-none");
      divNombreComercial.classList.add("d-flex");
      document.getElementById('nombres').value = '';
      document.getElementById('apellidos').value = '';

      document.getElementById('nombres').removeAttribute('required');
      document.getElementById('apellidos').removeAttribute('required');
      //document.getElementById('razon_social').addAttribute('required');

      /* const razonSocialElement = document.getElementById('razon_social');
      // Check if the element exists
      if (razonSocialElement) {
        // If the element exists, add the 'required' attribute
        razonSocialElement.setAttribute('required', 'true'); // Or simply 'required', '' for boolean attributes
      } */

      // Cambiar a NIT
      selectTipoDocumento.value = '31';
      $('#tipo_documento').trigger('change'); // para que Select2 actualice el valor visualmente
    } else if (tipo === '2') { // Natural
      divNombreComercial.classList.add("d-none");
      divNombreComercial.classList.remove("d-flex");
      divNombreApellidos.classList.remove("d-none");

      document.getElementById('razon_social').value = '';

      //document.getElementById('nombres').addAttribute('required');
      //document.getElementById('apellidos').addAttribute('required');
      //document.getElementById('razon_social').removeAttribute('required');

      // Get a reference to the element


      // Cambiar a CC
      selectTipoDocumento.value = '13';
      $('#tipo_documento').trigger('change');
    }
  }

  // Ejecutar al cargar para aplicar el valor actual del selector
  document.addEventListener("DOMContentLoaded", function() {
    const tipo = document.getElementById("tipo_persona").value;
    selectTipoPersona(tipo);
  });
</script>









<script>
  function calcularDigitoVerificacion(myNit) {
    var vpri, x, y, z;

    // Limpieza del NIT
    myNit = myNit.replace(/\s/g, "").replace(/,/g, "").replace(/\./g, "").replace(/-/g, "");

    if (isNaN(myNit)) return "";

    vpri = new Array(16);
    vpri[1] = 3;
    vpri[2] = 7;
    vpri[3] = 13;
    vpri[4] = 17;
    vpri[5] = 19;
    vpri[6] = 23;
    vpri[7] = 29;
    vpri[8] = 37;
    vpri[9] = 41;
    vpri[10] = 43;
    vpri[11] = 47;
    vpri[12] = 53;
    vpri[13] = 59;
    vpri[14] = 67;
    vpri[15] = 71;

    x = 0;
    z = myNit.length;
    for (var i = 0; i < z; i++) {
      y = myNit.substr(i, 1);
      x += (y * vpri[z - i]);
    }

    y = x % 11;
    return (y > 1) ? 11 - y : y;
  }

  function calcularYMostrarDV(nit) {
    const dv = calcularDigitoVerificacion(nit);
    document.getElementById('dv').value = dv;
  }
</script>





<!-- <script>
  $(document).ready(function() {
    $('#departamento').on('change', function() {
      var valorSelect1 = $(this).val();

      var url = document.getElementById("url").value;
      //var code_departamento = document.getElementById("departamento").value;


      $.ajax({
        url: url +
          "/" +
          "eventos/municipios",
        type: "post",
        method: 'POST',
        dataType: 'json',
        data: {
          valorSelect1: valorSelect1
        },
        success: function(data) {
          //$('#municipios').empty();
          //$('#ciudad').empty();

          var resultado = JSON.parse(data);

          

          /*   $.each(data, function(key, value) {
              $('#municipios').append($('<option>', {
                value: value.value,
                text: value.text
              }));
            }); */

        
        },
        error: function(xhr, textStatus, errorThrown) {
          console.log('Error: ' + errorThrown);
        }
      });
    });
  });
</script>  -->

<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/departamentoCiudad.js"></script>