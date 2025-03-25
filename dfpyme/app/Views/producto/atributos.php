<?php foreach ($atributos as $detalle): ?>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header d-flex align-items-center justify-content-between px-3" id="headingThree">
                <button class="accordion-button collapsed flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <?php echo $detalle['nombre']; ?>
                </button>
                <button class="btn  btn-outline-success ms-2 me-3 btn-icon" type="button" title="Agregar un componente "><!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg></button>
                <button class="btn  btn-outline-danger ms-2 me-3 btn-icon" type="button" title="Agregar un componente "><!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="4" y1="7" x2="20" y2="7" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg></button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <span class="badge rounded-pill bg-primary d-flex align-items-center">
                        Primary
                        <button type="button" class="btn-close ms-2" aria-label="Close" onclick="eliminarBadge(this)"></button>
                    </span>

                    <p>Crema de verduras</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3"></div>

<?php endforeach ?>