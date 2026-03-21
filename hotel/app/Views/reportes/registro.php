<form id="formRegistroHuesped" class="container-fluid p-2 p-md-4">
  <div class="card border-0 shadow-lg rounded-4">
    <div class="card-body p-3 p-md-4">

      <h5 class="mb-4 fw-bold text-primary">Registro de Huésped</h5>

      <!-- FILA 1 -->
      <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-4">
          <label class="form-label small fw-semibold">Fecha</label>
          <input type="date" class="form-control form-control-sm custom-input" name="fecha" required>
        </div>

        <div class="col-6 col-sm-3 col-lg-4">
          <label class="form-label small fw-semibold">Habitación</label>
          <input type="text" class="form-control form-control-sm custom-input" name="habitacion" placeholder="101" required>
        </div>

        <div class="col-6 col-sm-3 col-lg-4">
          <label class="form-label small fw-semibold">Tipo Documento</label>
          <select class="form-select form-select-sm custom-input" name="tipoDocumento" required>
            <option value="">Seleccione</option>
            <option value="CC">Cédula</option>
            <option value="TI">Tarjeta Identidad</option>
            <option value="CE">C. Extranjería</option>
          </select>
        </div>
      </div>

      <!-- FILA 2 -->
      <div class="row g-3 mt-1">
        <div class="col-12 col-md-6 col-lg-4">
          <label class="form-label small fw-semibold">Nombres y Apellidos</label>
          <input type="text" class="form-control form-control-sm custom-input" name="nombres" placeholder="Juan Pérez" required>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <label class="form-label small fw-semibold">N° Documento</label>
          <input type="text" class="form-control form-control-sm custom-input" name="numeroDocumento" placeholder="Ingrese número" required>
        </div>

        <div class="col-6 col-md-6 col-lg-4">
          <label class="form-label small fw-semibold">Tipo Vehículo</label>
          <select class="form-select form-select-sm custom-input" name="tipoVehiculo">
            <option value="">Seleccione</option>
            <option>Carro</option>
            <option>Moto</option>
            <option>Camioneta</option>
          </select>
        </div>
      </div>

      <!-- FILA 3 -->
      <div class="row g-3 mt-1">
        <div class="col-6 col-md-6 col-lg-4">
          <label class="form-label small fw-semibold">Placa Vehículo</label>
          <input type="text" class="form-control form-control-sm text-uppercase custom-input" name="placaVehiculo" placeholder="ABC123">
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <label class="form-label small fw-semibold">Procedencia</label>
          <input type="text" class="form-control form-control-sm custom-input" name="procedencia" placeholder="Ciudad origen">
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <label class="form-label small fw-semibold">Destino</label>
          <input type="text" class="form-control form-control-sm custom-input" name="destino" placeholder="Ciudad destino">
        </div>
      </div>

      <!-- FILA 4 -->
      <div class="row g-3 mt-1">
        <div class="col-12 col-md-4">
          <label class="form-label small fw-semibold">Hora Salida</label>
          <input type="time" class="form-control form-control-sm custom-input" name="horaSalida">
        </div>

        <div class="col-12 col-md-8">
          <label class="form-label small fw-semibold">Notas</label>
          <textarea class="form-control form-control-sm custom-input" rows="2" placeholder="Comentarios adicionales..."></textarea>
        </div>
      </div>

      <!-- BOTONES -->
      <div class="d-flex justify-content-end mt-4 gap-2 flex-wrap">
        <button type="reset" class="btn btn-light btn-sm px-4">Limpiar</button>
        <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
          Guardar
        </button>
      </div>

    </div>
  </div>
</form>

<style>
  .custom-input {
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    transition: all 0.2s ease;
  }

  .custom-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 2px rgba(13,110,253,0.15);
  }

  .card {
    background: #ffffff;
  }

  @media (max-width: 576px) {
    h5 {
      font-size: 1rem;
    }
  }
</style>