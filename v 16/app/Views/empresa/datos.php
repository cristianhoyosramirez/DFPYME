<?= $this->extend('template/producto') ?>
<?= $this->section('title') ?>
PRODUCTO
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>



<p class="text-primary h3 text-center">Datos generales de la empresa </p>

<div class=" container col-md-12">
  <div class="card">
    <div class="card-body">
      <form class="row g-3" action="<?= base_url('empresa/actualizar_datos') ?>" method="POST">
        <input type="hidden" id="url" value="<?php echo base_url() ?>">

        <div class="col-md-2">
          <label for="inputEmail4" class="form-label">Tipo empresa</label>
          <select class="form-select" id="tipo_empresa" name="tipo_empresa">
            <?php foreach ($tipo_empresa as $detalle) : ?>
              <option value="<?php echo $detalle['id'] ?>" <?php if ($detalle['id'] == $datos_empresa[0]['fk_tipo_empresa']) : ?>selected <?php endif; ?>><?php echo $detalle['nombre'] ?> </option>
            <?php endforeach ?>
          </select>
        </div>

        <div class="col-2">
          <label for="inputAddress" class="form-label">Régimen</label>
          <select class="form-select" aria-label="Default select example" name="id_regimen">

            <?php foreach ($regimen as $detalle) { ?>
              <option value="<?php echo $detalle['idregimen'] ?>" <?php if ($detalle['idregimen'] == $datos_empresa[0]['idregimen']) : ?>selected <?php endif; ?>><?php echo $detalle['descripcion'] ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="col-md-3">
          <label for="nit_empresa" class="form-label">NIT</label>
          <div class="input-group">
            <input
              type="text"
              class="form-control"
              name="nit_empresa"
              id="nit_empresa"
              value="<?php echo esc($datos_empresa[0]['nitempresa'] ?? ''); ?>"
              placeholder="Ingrese el NIT"
              maxlength="15"
              pattern="[0-9]{5,15}"
              title="Solo números (mínimo 5, máximo 15 dígitos)"
              oninput="soloNumeros(this); calcularDV();"
              required
              autofocus>

            <span class="input-group-text">-</span>

            <input
              type="text"
              class="form-control text-center"
              name="digito_verificacion"
              id="digito_verificacion"
              value="<?php echo esc($datos_empresa[0]['dv'] ?? ''); ?>"
              placeholder="DV"
              readonly
              required>
          </div>
        </div>


        <div class="col-md-2">
          <label for="inputCity" class="form-label">Razon social</label>
          <input type="text" class="form-control" name="razonSocial" value="<?php echo $datos_empresa[0]['nombrejuridicoempresa'] ?>">
        </div>
        <div class="col-md-2">
          <label for="inputCity" class="form-label">Nombre comercial</label>
          <input type="text" class="form-control" name="nombre_comercial" value="<?php echo $datos_empresa[0]['nombrecomercialempresa'] ?>">
        </div>

        <div class="col-md-2">
          <label for="inputZip" class="form-label">Departamento</label>
          <select class="form-select" aria-label="Default select example" name="departamento" id="departamento" onchange="buscar_municipios(this.options[this.selectedIndex].value)">
            <?php foreach ($departamentos as $detalle) { ?>
              <option value="<?php echo $detalle['iddepartamento'] ?>" <?php if ($detalle['iddepartamento'] == $datos_empresa[0]['iddepartamento']) : ?>selected <?php endif; ?>><?php echo $detalle['nombredepartamento'] ?></option>
            <?php  } ?>
          </select>
        </div>
        <div class="col-md-2">
          <label for="inputZip" class="form-label">Municipio</label>
          <select class="form-select" aria-label="Default select example" name="municipio" id="municipio">

            <?php foreach ($municipios as $detalle) { ?>
              <option value="<?php echo $detalle['idciudad'] ?>" <?php if ($detalle['idciudad'] == $datos_empresa[0]['idciudad']) : ?>selected <?php endif; ?>><?php echo $detalle['nombreciudad'] ?></option>
            <?php  } ?>

          </select>
        </div>
        <div class="col-md-3">
          <label for="inputZip" class="form-label">Dirección</label>
          <input type="text" class="form-control" name="direccion" name="direccion" value="<?php echo $datos_empresa[0]['direccionempresa'] ?>">
        </div>

        <div class="col-md-2">
          <label for="inputState" class="form-label">Email</label>
          <input type="text" class="form-control" name="email" value="<?php echo $datos_empresa[0]['telefonoempresa'] ?>">
        </div>

        <div class="col-md-2">
          <label for="inputState" class="form-label">Télefono</label>
          <input type="text" class="form-control" name="telefono" value="<?php echo $datos_empresa[0]['telefonoempresa'] ?>">
        </div>

        <div class="col-md-2">
          <label for="inputZip" class="form-label">Cód actividad económica </label>
          <input
            type="text"
            class="form-control"
            name="actividadEconomica"
            id="actividadEconomica"
            value="<?php echo esc($actividadEconomica); ?>"
            maxlength="4"
            pattern="[0-9]+"
            title="Solo se permiten números (máximo 4 dígitos)"
            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
            required>


        </div>

        <div class="row mt-3">
          <div class="col text-end">
            <button type="submit" class="btn btn-outline-success px-4">
              Guardar
            </button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  // Solo permitir números
  function soloNumeros(input) {
    input.value = input.value.replace(/\D/g, '');
  }

  // Calcular DV según algoritmo oficial DIAN
  function calcularDV() {
    const nit = document.getElementById('nit_empresa').value;
    if (!nit) {
      document.getElementById('digito_verificacion').value = '';
      return;
    }

    // Pesos oficiales DIAN (de derecha a izquierda, máximo 15 posiciones)
    const pesos = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];
    let suma = 0;
    const nitReverso = nit.split('').reverse();

    for (let i = 0; i < nitReverso.length; i++) {
      suma += parseInt(nitReverso[i]) * pesos[i];
    }

    const residuo = suma % 11;
    const dv = residuo > 1 ? 11 - residuo : residuo;

    document.getElementById('digito_verificacion').value = dv;
  }
</script>

<?= $this->endSection('content') ?>