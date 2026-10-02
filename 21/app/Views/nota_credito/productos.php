 <!-- Productos facturados -->
     <div class="card border-0 shadow-sm mb-3">

         <div class="card-header bg-primary text-white">

             <i class="fas fa-box me-2"></i>

             <strong>
                 Productos Facturados
             </strong>

         </div>

         <div class="card-body p-0">

             <div class="table-responsive">

                 <table class="table table-hover align-middle mb-0">

                     <thead class="table-dark">

                         <tr>

                             <td>
                                 Código
                             </td>

                             <td>
                                 Descripción
                             </td>

                             <td>
                                 Cant.
                             </td>


                             <td>
                                 IVA.
                             </td>
                             <td>
                                 ICO.
                             </td>
                             <td>
                                 Vlr Unit.
                             </td>

                             <td>
                                 Total
                             </td>

                         </tr>

                     </thead>

                     <tbody id="detalleFactura">

                         <!-- Productos cargados dinámicamente -->

                         <?php foreach ($productos as $detalleProductos): ?>
                             <tr>
                                 <td>
                                     <?php echo $detalleProductos['codigo'];  ?>
                                 </td>
                                 <td>
                                     <?php echo $detalleProductos['descripcion'];  ?>
                                 </td>
                                 <td>
                                     <?php echo $detalleProductos['cantidad'];  ?>
                                 </td>
                                 <td>
                                     <?php echo number_format($detalleProductos['iva'], 0, ',', '.'); ?>
                                 </td>
                                 <td>
                                     <?php echo number_format($detalleProductos['icn'], 0, ',', '.'); ?>
                                 </td>
                                 <td>
                                     <?php echo number_format($detalleProductos['total'], 0, ',', '.'); ?>
                                 </td>
                                 <td>
                                     <?php echo number_format($detalleProductos['total'], 0, ',', '.'); ?>
                                 </td>
                             </tr>
                         <?php endforeach ?>

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