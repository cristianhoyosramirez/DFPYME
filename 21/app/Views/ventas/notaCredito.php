 <form id="formNotaCredito">

     <input
         type="hidden"
         id="id_factura_nota_credito"
         name="id_factura_nota_credito"
         value=<?= $id ?>>


     <div class="card border-0 shadow-sm mb-3">

         <div class="card-body">

             <div class="row g-3 align-items-end">

                 <!-- Factura -->
                 <div class="col-md-4">

                     <label class="form-label text-muted small mb-1">
                         Factura
                     </label>

                     <div
                         id="numero_factura"
                         class="fw-bold text-primary fs-5">
                         <?= $factura[0]['numero'] ?>
                     </div>

                 </div>
                 <!-- Fecha factura -->
                 <div class="col-md-4">

                     <label class="form-label text-muted small mb-1">
                         Fecha factura
                     </label>

                     <div
                         id="numero_factura"
                         class="fw-bold text-primary fs-5">

                         <?php
                            $fecha = new DateTime($factura[0]['fecha']);

                            $dias = [
                                'Sunday'    => 'Domingo',
                                'Monday'    => 'Lunes',
                                'Tuesday'   => 'Martes',
                                'Wednesday' => 'Miércoles',
                                'Thursday'  => 'Jueves',
                                'Friday'    => 'Viernes',
                                'Saturday'  => 'Sábado'
                            ];

                            $meses = [
                                'January'   => 'Enero',
                                'February'  => 'Febrero',
                                'March'     => 'Marzo',
                                'April'     => 'Abril',
                                'May'       => 'Mayo',
                                'June'      => 'Junio',
                                'July'      => 'Julio',
                                'August'    => 'Agosto',
                                'September' => 'Septiembre',
                                'October'   => 'Octubre',
                                'November'  => 'Noviembre',
                                'December'  => 'Diciembre'
                            ];

                            echo $dias[$fecha->format('l')] . ', ' .
                                $fecha->format('d') . ' de ' .
                                $meses[$fecha->format('F')] . ' de ' .
                                $fecha->format('Y');
                            ?>
                     </div>

                 </div>
                 <!-- Fecha factura -->
                 <div class="col-md-4">

                     <label class="form-label text-muted small mb-1">
                         Fecha Nota credito
                     </label>

                     <div
                         id="numero_factura"
                         class="fw-bold text-primary fs-5">
                         <?php
                            echo $dias[date('l')] . ', ' .
                                date('d') . ' de ' .
                                $meses[date('F')] . ' de ' .
                                date('Y');
                            ?>
                     </div>

                 </div>



                 <!-- Cliente -->
                 <div class="col-md-6">

                     <label class="form-label text-muted small mb-1">
                         Cliente
                     </label>

                     <div
                         id="cliente_factura"
                         class="fw-bold text-primary fs-5">
                         <?= $factura[0]['nombrescliente'] ?>
                     </div>

                 </div>

                 <!-- NIT -->
                 <div class="col-md-6">

                     <label class="form-label text-muted small mb-1">
                         NIT
                     </label>

                     <div
                         id="nit_cliente"
                         class="fw-bold text-primary fs-5">
                         <?= $factura[0]['nit_cliente'] ?>
                     </div>

                 </div>


                 <!-- Motivo -->
                 <div class="col-md-6">

                     <label
                         for="razon"
                         class="form-label fw-semibold">

                         Motivo Nota Crédito
                         <span class="text-danger">*</span>

                     </label>

                     <select
                         class="form-select"
                         id="razon"
                         name="razon"
                         required onchange="cambiarColor(this)">



                         <?php foreach ($motivos as $detalle): ?>

                             <option value="<?= $detalle['reason'] ?>"><?= $detalle['descripcion'] ?></option>

                         <?php endforeach ?>

                     </select>

                 </div>
                 <div class="col-md-6">

                     <label for="nota" class="form-label text-muted small mb-1">Nota</label>

                     <textarea

                         id="notaCredito"

                         name="notaCredito"

                         class="form-control form-control-sm"

                         rows="2"

                         placeholder="Observación..."></textarea>

                 </div>

             </div>

         </div>

     </div>

     <!-- Productos facturados -->
     <div class="card border-0 shadow-sm mb-3">

     

         <div class="card-body p-0">

             <div class="table-responsive">

                 <table class="table table-hover align-middle mb-0">

                   
                     <tbody id="detalleFactura">

                         <!-- Productos cargados dinámicamente -->

                         <?= $this->include('nota_credito/productos') ?>

                     </tbody>

                     <tfoot>

                         <tr class="table-light ">

                             <th
                                 colspan="6"
                                 class="text-end border-0">

                                 SUB TOTAL

                             </th>

                             <th
                                 class="text-end text-success border-0">

                                 <span id="totalFacturaDetalle">

                                     <?= $sub_total ?>
                                 </span>

                             </th>

                         </tr>

                         <tr class="table-light ">

                             <th
                                 colspan="6"
                                 class="text-end border-0">

                                 PROPINA

                             </th>

                             <th
                                 class="text-end text-success border-0">

                                 <span id="totalFacturaDetalle">

                                     <?= $factura[0]['propina'] ?>
                                 </span>

                             </th>

                         </tr>

                         <tr class="table-light">

                             <th
                                 colspan="6"
                                 class="text-end border-0">

                                 INC

                             </th>

                             <th
                                 class="text-end text-success border-0">

                                 <span id="totalFacturaDetalle">
                                     <?= $icn ?>
                                 </span>

                             </th>

                         </tr>
                         <tr class="table-light  border-0">

                             <th
                                 colspan="6"
                                 class="text-end  border-0">

                                 IVA

                             </th>

                             <th
                                 class="text-end text-success  border-0">

                                 <span id="totalFacturaDetalle">
                                     <?= $iva ?>
                                 </span>

                             </th>

                         </tr>
                         <tr class="table-light  border-0">

                             <th
                                 colspan="6"
                                 class="text-end  border-0">

                                 TOTAL

                             </th>

                             <th
                                 class="text-end text-success  border-0">

                                 <span id="totalFacturaDetalle">
                                     <?= number_format($factura[0]['total'], 0, ',', '.') ?>
                                 </span>

                             </th>

                         </tr>

                     </tfoot>

                 </table>

             </div>

         </div>

     </div>
 </form>