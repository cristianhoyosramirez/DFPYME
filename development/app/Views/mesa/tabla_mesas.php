 <?php foreach ($mesas as $mesa): ?>
     <tr>
         <td><?= $mesa['nombre'] ?></th>
         <td>
             <div class="d-flex">

                 <form action="<?= base_url('mesas/editar') ?>" method="POST">

                     <input type="hidden"
                         value="<?= $mesa['id'] ?>"
                         name="id">

                     <button type="submit"
                         class="btn btn-outline-primary ">

                         Editar
                     </button>
                 </form>

                 &nbsp;

                 <button type="submit"
                     class="btn btn-outline-danger " onclick="eliminarMesa(<?= $mesa['id'] ?>)">
                     Eliminar
                 </button>


             </div>
         </td>

     </tr>

 <?php endforeach ?>