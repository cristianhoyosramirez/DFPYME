<!-- Modal Crear producto-->
<div class="modal fade " id="crear_producto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">

      <div class="modal-body">
        <div class="hr-text text-primary ">
          <p class="h3 text-primary">Crear producto</p>
        </div>
        <form class="row g-1" action="<?= base_url('producto/creacion_producto'); ?>" method="post" id="producto_agregar" autocomplete="off">
          <div class="hr-text hr-text-left">
            <p class="h4 text-green">Información general</p>
          </div>



          <div class="col-md-2">
            <label for="inputEmail4" class="form-label">Códido</label>
            <input type="text" class="form-control" id="crear_producto_codigo_interno" name="crear_producto_codigo_interno" readonly>
            <span class="text-danger error-text crear_producto_codigo_interno_error"></span>
          </div>

          <div class="col-md-4">
            <label for="inputEmail4" class="form-label">Código de barras</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">
                <!-- Download SVG icon from http://tabler-icons.io/i/barcode -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M4 7v-1a2 2 0 0 1 2 -2h2" />
                  <path d="M4 17v1a2 2 0 0 0 2 2h2" />
                  <path d="M16 4h2a2 2 0 0 1 2 2v1" />
                  <path d="M16 20h2a2 2 0 0 0 2 -2v-1" />
                  <rect x="5" y="11" width="1" height="2" />
                  <line x1="10" y1="11" x2="10" y2="13" />
                  <rect x="14" y="11" width="1" height="2" />
                  <line x1="19" y1="11" x2="19" y2="13" />
                </svg>
              </span>
              <input type="text" class="form-control" name="crear_producto_codigo_de_barras" id="crear_producto_codigo_de_barras" onkeyup="saltar_creacion_producto(event,'crear_producto_nombre')">
            </div>
          </div>

          <div class="col-md-6">
            <label for="inputPassword4" class="form-label">Nombre producto</label>
            <input type="text" class="form-control" id="crear_producto_nombre" name="crear_producto_nombre" onkeyup="saltar_creacion_producto(event,'categoria_producto'),minusculasAmayusculas()">
            <span class="text-danger error-text crear_producto_nombre_error"></span>
          </div>




          <div class="col-md-3">
            <label for="">Categoria</label>
            <select class="form-select" id="categoria_producto" name="categoria_producto" onclick="categorias_productos()" onkeyup="saltar_creacion_producto(event,'marca_producto')">

            </select>

            <span class="text-danger error-text categoria_producto_error"></span>
          </div>
          <div class="col-sm">
            <br>
            <button type="button" class="btn btn-success btn-icon" title="Agregar categoria" data-bs-toggle="modal" data-bs-target="#agregar_categoria">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
              </svg></button>
          </div>


          <div class="col-md-3">
            <label for="">Marca</label>
            <select class="form-select" id="marca_producto" name="marca_producto">

            </select>
            <span class="text-danger error-text marca_producto_error"></span>
          </div>
          <div class="col-sm">
            <br>
            <button type="button" class="btn btn-success btn-icon" title="Agregar marca"><!-- Download SVG icon from http://tabler-icons.io/i/plus -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
              </svg></button>
          </div>

          <div class="col-md-2"><br>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="impresion_en_comanda" name="impresion_en_comanda" onkeyup="saltar_creacion_producto(event,'permitir_descuento')">
              <label class="form-check-label" for="flexSwitchCheckDefault">Imprimir comanda</label>
            </div>
          </div>
          <div class="col-md-2"><br>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="permitir_descuento" name="permitir_descuento" onkeyup="saltar_creacion_producto(event,'informacion_tributaria')">
              <label class="form-check-label" for="flexSwitchCheckDefault">Descuento</label>
            </div>
          </div>

          <div class="hr-text hr-text-left">
            <p class="h4 text-green">Información de precio</p>
          </div>

          <div class="col-md-4">
            <label for="inputPassword4" class="form-label">Información tributaria</label>
            <select class="form-select" id="informacion_tributaria" name="informacion_tributaria" onchange="mostrar_informacion_tributaria()">
              <option value="1" selected>Impuesto Nacional al Consumo (ICO)</option>
              <option value="2">Impuesto al Valor Agregado (IVA)</option>
            </select>
            <span class="text-danger error-text informacion_tributaria_error"></span>
          </div>

          <div class="col-md-4" style="display: none" id="informacion_triburaria_iva">
            <label for="inputPassword4" class="form-label">Valor IVA </label>
            <select class="form-select" id="valor_iva" name="valor_iva">
              <?php foreach ($iva as $detalle) { ?>
                <option value="<?php echo $detalle['idiva'] ?>"><?php echo $detalle['valoriva'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-4" style="display: block" id="informacion_tributaria_ico">
            <label for="inputPassword4" class="form-label">Valor ICO</label>
            <select class="form-select" id="valor_ico" name="valor_ico">
              <?php foreach ($ico as $detalle) { ?>
                <option value="<?php echo $detalle['id_ico'] ?>"><?php echo $detalle['valor_ico'] ?></option>
              <?php } ?>

            </select>
          </div>


          <div class="col-4">
            <label for="inputAddress2" class="form-label">Valor costo</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">
                <!-- Download SVG icon from http://tabler-icons.io/i/coin -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <circle cx="12" cy="12" r="9" />
                  <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                  <path d="M12 6v2m0 8v2" />
                </svg>
              </span>
              <input type="text" class="form-control" id="valor_costo_producto" name="valor_costo_producto" onkeyup="saltar_creacion_producto(event,'valor_venta_producto')">
            </div>
            <span class="text-danger error-text valor_costo_producto_error"></span>
          </div>

          <div class="col-4">
            <label for="inputAddress2" class="form-label">Precio 1 </label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">
                <!-- Download SVG icon from http://tabler-icons.io/i/coin -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <circle cx="12" cy="12" r="9" />
                  <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                  <path d="M12 6v2m0 8v2" />
                </svg>
              </span>
              <input type="text" class="form-control" id="valor_venta_producto" name="valor_venta_producto" onkeyup="saltar_creacion_producto(event,'precio_2')">
            </div>
            <span class="text-danger error-text valor_venta_producto_error"></span>
          </div>

          <div class="col-4">
            <label for="inputAddress2" class="form-label">Precio 2 </label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">
                <!-- Download SVG icon from http://tabler-icons.io/i/coin -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <circle cx="12" cy="12" r="9" />
                  <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                  <path d="M12 6v2m0 8v2" />
                </svg>
              </span>
              <input type="text" class="form-control" id="precio_2" name="precio_2" onkeyup="saltar_creacion_producto(event,'btn_crear_producto'),hablilitar_boton(event)" value=0>
            </div>
            <span class="text-danger error-text precio_2_error"></span>
          </div>

          <div class="col-8">
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-success" id="btn_crear_producto">Guardar </button>
            <button type="button" class="btn btn-danger" onclick="cancelar_creacion_producto()">Cancelar</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>


<script>
  function mostrar_informacion_tributaria() {
    var informacion_tribuitaria = document.getElementById("informacion_tributaria").value;
    ico = document.getElementById('informacion_tributaria_ico');
    iva = document.getElementById('informacion_triburaria_iva');

    if (informacion_tribuitaria == 1) {

      ico.style.display = 'block';
      iva.style.display = 'none';
    }

    if (informacion_tribuitaria == 2) {
      ico.style.display = 'none';
      iva.style.display = 'block';
    }

  }
</script>