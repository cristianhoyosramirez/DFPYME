<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>

<?= $this->section('title') ?>
Configuración
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h4 class="mb-4 text-center h3 text-primary">Configuración de Pedidos</h4>

    <!-- Configuración principal -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">


            <div class="mb-3">
                <label for="salonSelect" class="form-label">Indique si desea habilitar la gestión de pedidos vía WhatsApp.</label>
                <select id="salonSelect" class="form-select" onchange="salonCambiado(this.value)">
                    <option value="true" <?= ($consultar === 't') ? 'selected' : '' ?>>Si</option>
                    <option value="false" <?= ($consultar === 'f') ? 'selected' : '' ?>>No</option>
                </select>

            </div>
        </div>
    </div>

    <!-- Configuración de salón -->
    <div class="accordion" id="accordionSalones">
        <div class="accordion-item shadow-sm">
            <h2 class="accordion-header" id="headingSalon">
                <button class="accordion-button collapsed d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSalon" aria-expanded="false" aria-controls="collapseSalon">
                    <i class="bi bi-house-door me-2"></i> Configuración pedidos WhatsApp
                </button>
            </h2>
            <div id="collapseSalon" class="accordion-collapse collapse" aria-labelledby="headingSalon" data-bs-parent="#accordionSalones">
                <div class="accordion-body">

                    <!-- Nombre del salón -->
                    <div class="mb-3 d-flex align-items-center">
                        <label for="salonName" class="form-label me-2">Zona:</label>
                        <input type="text" id="salonName" class="form-control me-2" value="<?php echo $nombre; ?>">
                        <button class="btn btn-outline-success btn-icon" type="button" title="Editar Zona" onclick="crearSalon()">
                            <!-- Download SVG icon from http://tabler-icons.io/i/refresh -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                            </svg>
                        </button>
                    </div>


                    <!-- Número de mesas -->
                    <div class="mb-3 d-flex align-items-center">
                        <label for="numMesas" class="form-label me-2">Número de mesas:</label>

                        <input type="number"
                            id="numMesas"
                            class="form-control me-2"
                            value="30"
                            min="1"
                            max="99"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); 
                if(this.value > 99) this.value = 99;">


                        <button class="btn btn-outline-success btn-icon" type="button" title="Crear Mesas" onclick="crearMesas()">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </button>
                    </div>


                    <!-- Mesas -->
                    <h6 class="mb-3">Mesas del salón</h6>
                    <div class="row g-2">
                        <div class="" id="mesasWhatsApp">
                            <div class="row g-3">

                            <?php if (!empty($mesas)): ?>

                                <?php foreach ($mesas as $index => $mesa): ?>
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="card shadow-sm h-100" id="card<?php echo $mesa['id']; ?>">
                                            <div class="card-body d-flex flex-column justify-content-between">

                                                <!-- Nombre editable -->
                                                <input type="text"
                                                    class="form-control text-center mb-3"
                                                    value="<?php echo  esc($mesa['nombre']) ?>" id="nombreMesa<?php echo $mesa['id']; ?>">

                                                <!-- Botones -->
                                                <div class="d-flex justify-content-center gap-2">
                                                    <!-- Eliminar -->
                                                    <button class="btn btn-icon btn-outline-danger" onclick="eliminarMesa(<?php echo $mesa['id']; ?>)"
                                                        title="Eliminar Mesa <?= esc($mesa['nombre']) ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <line x1="4" y1="7" x2="20" y2="7" />
                                                            <line x1="10" y1="11" x2="10" y2="17" />
                                                            <line x1="14" y1="11" x2="14" y2="17" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                    </button>

                                                    <!-- Editar -->
                                                    <button class="btn btn-icon btn-outline-success" onclick="editarMesa(<?php echo $mesa['id']; ?>)"
                                                        title="Editar <?= esc($mesa['nombre']) ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                            <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                                        </svg>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <?php  endif ?>


                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="text" id="url" value="<?php echo base_url() ?>" hidden>

<style>
    .hover-shadow:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: box-shadow 0.2s ease-in-out;
    }
</style>

<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>

<script>
    async function salonCambiado(valor) {
        try {
            const url = document.getElementById('url').value;

            const response = await fetch(url + "/whatsapp/consultar", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    valor: valor
                })
            });

            if (!response.ok) {
                throw new Error("Error en la petición: " + response.status);
            }

            const data = await response.json();

            if (data.response === "success") {
                Swal.fire({
                    icon: "success",
                    title: "Salón cambiado",
                    text: data.message || "El salón fue actualizado correctamente.",
                    confirmButtonText: "Aceptar"
                });

                // Ejemplo: recargar mesas en pantalla si viene en la respuesta
                if (data.mesasHtml) {
                    document.getElementById("mesasWhatsApp").innerHTML = data.mesasHtml;
                }

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.message || "No se pudo cambiar el salón.",
                    confirmButtonText: "Cerrar"
                });
            }
        } catch (error) {
            console.error("Error en salonCambiado:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Hubo un problema al cambiar el salón.",
                confirmButtonText: "Cerrar"
            });
        }
    }
</script>




<script>
    // Eliminar mesa
    async function eliminarMesa(id) {
        const confirmacion = await Swal.fire({
            title: "¿Estás seguro?",
            text: "Se eliminará la mesa seleccionada",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
            buttonsStyling: false, // desactiva estilos por defecto
            customClass: {
                confirmButton: "btn btn-outline-success me-2", // botón verde outline
                cancelButton: "btn btn-outline-danger" // botón rojo
            }
        });

        if (!confirmacion.isConfirmed) return;

        try {
            const url = document.getElementById('url').value;
            const response = await fetch(url + "/mesas/eliminar", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id
                })
            });

            if (!response.ok) throw new Error("Error en la eliminación");

            const result = await response.json();

            if (result.response == "success") {
                Swal.fire("Eliminada", "La mesa fue eliminada correctamente", "success");
                document.getElementById('mesasWhatsApp').innerHTML = result.mesas;
            } else {
                Swal.fire("Error", result.message || "No se pudo eliminar la mesa", "error");
            }
        } catch (error) {
            console.error("Error:", error);
            Swal.fire("Error", "Ocurrió un error en la petición", "error");
        }
    }

    // Editar mesa
    async function editarMesa(id) {
        const nombre = document.getElementById('nombreMesa' + id).value;
        const url = document.getElementById('url').value;

        try {
            const response = await fetch(`${url}/mesas/edicionMesa`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id,
                    nombre: nombre
                })
            });

            if (!response.ok) throw new Error("Error en la edición");

            const result = await response.json();

            if (result.response == "success") {
                Swal.fire("Actualizada", "El nombre de la mesa se actualizó correctamente", "success");
            } else {
                Swal.fire("Error", result.message || "No se pudo actualizar la mesa", "error");
            }
        } catch (error) {
            console.error(error);
            Swal.fire("Error", "Hubo un error al intentar editar la mesa", "error");
        }
    }
</script>



<script>
    async function crearMesas() {
        try {
            const prefijo = document.getElementById("salonName").value;
            const numMesas = document.getElementById("numMesas").value;
            const url = document.getElementById("url").value;

            if (!prefijo || !numMesas) {
                Swal.fire({
                    icon: "warning",
                    title: "Campos incompletos",
                    text: "⚠️ Debes ingresar el ID del salón y el número de mesas."
                });
                return;
            }

            // Mostrar spinner mientras carga
            Swal.fire({
                title: "Creando mesas...",
                text: "Por favor espera",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch(url + "/salones/crearMesas", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    numMesas: numMesas,
                    prefijoMesa: prefijo
                })
            });

            if (!response.ok) {
                throw new Error("Error en la petición: " + response.status);
            }

            const data = await response.json();

            // Cerrar spinner y mostrar resultado
            if (data.response === "success") {

                document.getElementById('mesasWhatsApp').innerHTML = data.mesas
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: data.message,
                    timer: 2500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.message
                });
            }

            console.log(data);

        } catch (error) {
            console.error("Error al crear mesas:", error);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "❌ Ocurrió un error al crear mesas. Revisa la consola."
            });
        }
    }
</script>


<script>
    // función asíncrona para crear un salón
    async function crearSalon() {
        const salonName = document.getElementById("salonName").value;
        const url = document.getElementById("url").value;

        if (!salonName.trim()) {
            alert("El nombre del salón no puede estar vacío.");
            return;
        }

        try {
            const response = await fetch(url + "/salones/whatsApp", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    nombre: salonName
                })
            });

            if (!response.ok) {
                throw new Error("Error en la petición: " + response.status);
            }

            const data = await response.json();

            if (data.response == "success") {
                //alert("Salón creado con éxito: " + data.message);
                sweet_alert_centrado('success', 'Salon actualizado')
            } else {
                sweet_alert_centrado('error', 'No se pudo crear el salón')
            }

        } catch (error) {
            console.error("Error al crear el salón:", error);
            alert("Hubo un problema al crear el salón.");
        }
    }

    // asociar el click al botón
    document.querySelector("#crearSalonBtn").addEventListener("click", crearSalon);
</script>

<?= $this->endSection('content') ?>