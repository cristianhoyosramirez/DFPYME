<!-- Modal -->
<div class="modal fade" id="modalAtributos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel"><span class="h3" id="productoAddAtri">Asignación de componentes</span></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="asigCompo"></div>
        <input type="text" id="id_tabla_producto" hidden>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" onclick="finalizarAtributos()">Aceptar</button>
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>

      </div>
    </div>
  </div>
</div>