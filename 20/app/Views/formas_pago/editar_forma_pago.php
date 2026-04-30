 <div class="row">
     <div class="col">
         <label for="inputEmail4" class="form-label">Efectivo</label>
         <input type="text" class="form-control"
             oninput="formatearMiles(this)"
             name="efectivo_factura" id="efectivo_factura" value="<?php echo $efectivo; ?>" onclick="seleccionar()">
     </div>
     <?php if ($transferencia == 0): ?>
         <div class="col">
             <!-- <label class="form-label">Transferencia</label>
             <input type="text" class="form-control"
                 name="transferencia_factura"
                 id="transferencia_factura"
                 value="0"
                 onclick="seleccionar('transferencia_factura')"> -->
             <label for="transferencia_factura" class="form-label">Transferencia</label>

             <div class="input-group mb-3">
                 <input type="text"
                     class="form-control"
                     oninput="formatearMiles(this)"
                     name="transferencia_factura"
                     id="transferencia_factura"
                     value="<?= $transferencia ?>"
                     onclick="seleccionar('transferencia_factura')">

                 <select class="form-select" id="forma_pago" name="forma_pago" onchange="validarSelect(this.value)">
                     <option value="">Selecciona un banco</option>
                     <?php foreach ($tipos_pago as $detalle): ?>
                         <option value="<?= $detalle['id'] ?>">
                             <?= $detalle['nombre'] ?>
                         </option>
                     <?php endforeach ?>
                 </select>
             </div>

         </div>
     <?php else: ?>
         <div class="col">
             <label class="form-label">Transferencia </label>
             <div class="input-group mb-3">
                 <input type="text" class="form-control"
                     name="transferencia_factura"
                     id="transferencia_factura"
                     value="<?= $transferencia ?>"
                     onclick="seleccionar('transferencia_factura')">

                 <select class="form-select"
                     id="forma_pago"
                     name="forma_pago"
                     onchange="validarSelect(this.value)">

                     <option value="">Selecciona un banco</option>

                     <?php foreach ($tipos_pago as $detalle): ?>
                         <option value="<?= $detalle['id'] ?>">
                             <?= $detalle['nombre'] ?>
                         </option>
                     <?php endforeach ?>

                 </select>

             </div>
         </div>
     <?php endif; ?>
 </div>