<div class="row g-3">

    <!-- FILA 1 -->
    <input type="text" name="id_habitacion_reserva" id="id_habitacion_reserva" hidden>

    <div class="col-12 col-sm-6 col-lg-4 mb-3">
        <label class="form-label small fw-semibold">Fecha</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            <input type="date" class="form-control custom-input" name="fecha_reserva" id="fecha_reserva" required>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4 mb-3">
        <label class="form-label small fw-semibold">Habitación</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-bed"></i></span>
            <input type="text" class="form-control custom-input" name="habitacion_reserva" id="habitacion_reserva" required disabled>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4 mb-3">
        <label class="form-label small fw-semibold">Valor hospedaje</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
            <input type="text" class="form-control custom-input" name="valor_hospedaje" id="valor_hospedaje"
                inputmode="numeric" pattern="[0-9]*" placeholder="0" oninput="formatCurrency(this)" required>
        </div>
    </div>

    <div class="mb-3 position-relative">
        <label class="form-label fw-semibold small">Huésped</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-user"></i>
            </span>
            <input type="text"
                class="form-control"
                name="huesped"
                id="huesped"
                placeholder="Buscar por nombre o apellidos"
                autocomplete="off"
                onkeyup="buscarCliente(event,this.value)"
                autofocus>
            <button type="button" class="btn btn-outline-secondary" onclick="limpiarHuesped()">
                <i class="fas fa-times"></i>
            </button>
        </div>


        <div id="listaClientes"></div>

    </div>

    <div class="col-12 col-md-12">
        <label class="form-label fw-semibold small">Nombres y apellidos</label>
        <input type="text"
            class="form-control"
            name="nombre_completo"
            placeholder="Nombre completo del huésped"
            id="nombre_completo"
            readonly>
    </div>

    <!-- FILA 2 -->
    <div class="col-12 col-sm-6 col-lg-4 mb-3">
        <label class="form-label small fw-semibold">Identificación</label>
        <div class="input-group">
            <span class="input-group-text" id="tipo_documento">CC</span>
            <input type="text" class="form-control" id="identificacion" name="identificacion" readonly placeholder="Número de documento">
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4 mb-3">
        <label class="form-label small fw-semibold">Nombres y Apellidos</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" class="form-control custom-input" name="nombres" id="nombres" readonly placeholder="Nombre completo">
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4 mb-3">
        <label class="form-label small fw-semibold">Vehículo</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-car"></i></span>
            <input type="text" id="id_placa" name="id_placa" hidden>
            <input type="text" class="form-control text-uppercase custom-input" name="placaVehiculo" id="placavehiculo" onkeypress="buscarPlaca(this.value)" placeholder="Ej: ABC123">
            <span class="input-group-text">
                <i class="fas fa-plus"></i>
            </span>
        </div>
        <span id="id_placaError" class="text-danger"></span>
        <div id="listaVehiculos"></div>
    </div>


    <!-- FILA 3 -->
    <div class="col-12 col-sm-6 col-lg-4 mb-3">
        <label class="form-label small fw-semibold">Procedencia</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            <input type="text" id="id_procedencia" name="id_procedencia" hidden>
            <input type="text" class="form-control custom-input" id="procedencia" name="procedencia" onkeypress="buscarCiudadProcedencia(this.value)">
            <span class="input-group-text" style="cursor:pointer;"
                onclick="document.getElementById('procedencia').value=''; document.getElementById('id_procedencia').value='';">
                <i class="fas fa-times"></i>
            </span>
        </div>
        <span id="id_procedenciaError" class="text-danger"></span>
        <div id="listaMunicipiosProcedencia"></div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4 mb-3">
        <label class="form-label small fw-semibold">Destino</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
            <input type="text" id="id_destino" name="id_destino" hidden>
            <input type="text" class="form-control custom-input" id="destino" name="destino" onkeypress="buscarCiudadDestino(this.value)">
            <span class="input-group-text" style="cursor:pointer;"
                onclick="document.getElementById('destino').value=''; document.getElementById('id_destino').value='';">
                <i class="fas fa-times"></i>
            </span>
        </div>
        <span id="id_destinoError" class="text-danger"></span>
        <div id="listaMunicipiosDestino"></div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4 mb-3">
        <label class="form-label small fw-semibold">Notas</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
            <textarea class="form-control custom-input" rows="2" id="notas_reserva" name="notas_reserva" placeholder="Comentarios adicionales..."></textarea>
        </div>
    </div>

</div>