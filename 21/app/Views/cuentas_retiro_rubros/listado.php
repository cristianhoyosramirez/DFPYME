<?php foreach ($rubros as $detalleRubro): ?>
    <tr id="fila<?= $detalleRubro['id']; ?>">
        <td>
            <div class="input-group">

                <input
                    type="text"
                    class="form-control"
                    value="<?= $detalleRubro['nombre_rubro']; ?>"
                    id="rubro<?= $detalleRubro['id']; ?>">

                <button
                    type="button"
                    class="btn btn-outline-success btn-icon"
                    title="Editar"
                    onclick="editarRubro(<?= $detalleRubro['id']; ?>)">
                    <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                        <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                    </svg>
                </button>

                <button
                    type="button"
                    class="btn btn-outline-danger btn-icon"
                    title="Eliminar"
                    onclick="eliminarRubro(<?= $detalleRubro['id']; ?>)">
                    <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="4" y1="7" x2="20" y2="7" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </button>

            </div>
        </td>

    </tr>
<?php endforeach; ?>


<!--  <script>
     async function eliminarBadge(id, nombre) {
         const confirmacion = await Swal.fire({
             title: "¿Estás seguro de eliminar ? " + nombre,
             text: "Esta acción no se puede deshacer.",
             icon: "warning",
             showCancelButton: true,
             confirmButtonColor: "#d33",
             cancelButtonColor: "#3085d6",
             confirmButtonText: "Sí, eliminar",
             cancelButtonText: "Cancelar"
         });

         if (!confirmacion.isConfirmed) {
             return; // Si el usuario cancela, no hace nada
         }

         try {
             const response = await fetch("<?= base_url('devolucion/deleteRubro') ?>", {
                 method: "DELETE",
                 headers: {
                     "Content-Type": "application/json"
                 },
                 body: JSON.stringify({
                     id: id
                 }) // Enviar el ID en JSON
             });


             const resultado = await response.json(); // Esperar la respuesta JSON

             if (resultado.response = "success") {
                 // Eliminar el elemento del DOM
                 document.getElementById('badge' + resultado.id).remove();

                 // Mostrar alerta de éxito
                 sweet_alert_centrado('success', 'Rubro eliminando')
             } else {
                 Swal.fire({
                     title: "Error",
                     text: "No se pudo eliminar el componente.",
                     icon: "error"
                 });
             }
         } catch (error) {
             console.error("Error en la petición:", error);
             Swal.fire({
                 title: "Error",
                 text: "Ocurrió un problema con la eliminación.",
                 icon: "error"
             });
         }
     }
 </script> -->


<!-- end row -->