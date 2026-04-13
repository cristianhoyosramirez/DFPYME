 

 <div class="row mb-2">
     <div class="col-sm-12">
         <input type="hidden" value="<?php echo $nitCliente; ?>" id="nit_del_cliente">
         <input type="hidden" value="<?php echo $id_factura; ?>" id="id_de_factura">
         <input type="text" class="form-control" id="nombre_del_cliente" name="nombre_cliente" value="<?php echo $nombreCliente; ?>" readonly>
     </div>
 </div>

 <div class="col-sm-12">
     <?php $medios_pago = model('medioPagoModel')->where('estado', 'true')->findAll();  ?>
     <div class="input-group mb-3">
         <label class="input-group-text" for="inputGroupSelect01">Medio de pago </label>
         <select class="form-select" id="medio_de_pagoEdit" name="medio_de_pago">
    
                 <?php foreach ($medios_pago as $detalle_me): ?>
                     <option value="<?= $detalle_me['codigo']; ?>"
                         <?= ($medio_pago == $detalle_me['codigo']) ? 'selected' : ''; ?>>
                         <?= $detalle_me['nombre_comercial']; ?>
                     </option>
                 <?php endforeach; ?>

         </select>
     </div>
 </div>

 <div class="col-sm-12">
     <div class="input-group mb-3">
         <label class="input-group-text" for="inputGroupSelect01">Forma de pago </label>
         <select class="form-select" id="formaPagoEdit" name="formaPago">
             <option value="1" <?= ($metodo_pago == 1) ? 'selected' : ''; ?>>Contado</option>
             <option value="2" <?= ($metodo_pago == 2) ? 'selected' : ''; ?>>Crédito</option>
         </select>

     </div>
 </div>

 <div class="row mb-2">
     <div class="input-group mb-3">
         <label class="input-group-text" for="inputGroupSelect01">Fecha limite </label>
         <input type="date" class="form-control" value="<?php echo $fecha_limite; ?>" id="fechaLimiteEdit" name="fechaLimite">
     </div>
 </div>


 