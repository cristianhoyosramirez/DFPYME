 <?php foreach ($mesas as $mesa): ?>
     <tr>
         <th><?= $mesa['nombre'] ?></th>
         <td>
             <div class="d-flex">

                 <form action="<?= base_url('mesas/editar') ?>" method="POST">

                     <input type="hidden"
                         value="<?= $mesa['id'] ?>"
                         name="id">

                     <button type="submit"
                         class="btn btn-primary ">

                         Editar
                     </button>
                 </form>

                 &nbsp;

                 <button type="submit"
                     class="btn btn-danger " onclick="eliminarMesa(<?= $mesa['id'] ?>)">
                     Eliminar
                 </button>


             </div>
         </td>

     </tr>

 <?php endforeach ?>