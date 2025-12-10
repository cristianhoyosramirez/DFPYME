
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">

        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="<?= base_url('/') ?>">
                        <img src="<?= base_url('assets/images/logo/logo.svg') ?>" alt="Logo">
                    </a>
                </div>

                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item">
                    <a href="#" onclick="loadComponent('seleccionar')" class="sidebar-link">
                        <i class="bi bi-pencil"></i>
                        <span>Renombrar</span>
                    </a>
                </li>

                <li class="sidebar-item ">
                    <a href="#" onclick="loadComponent('json')" class='sidebar-link'>
                        <i class="bi bi-file-code"></i>
                        <span>
                            Json
                        </span>
                    </a>
                </li>

            </ul>
        </div>

    </div>
</div>

<script>
    function loadComponent(nombre) {
        fetch("<?= base_url('component') ?>/" + nombre)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error al cargar el componente");
                }
                return response.text();
            })
            .then(html => {
                document.getElementById("content").innerHTML = html;
            })
            .catch(error => console.error("Error:", error));
    }
</script>


<script>
    async function cargarArchivos() {
        const ruta = document.getElementById('ruta').value;
        const BASE_URL = "<?= base_url() ?>";

        try {
            const response = await fetch(`${BASE_URL}getFiles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    ruta
                })
            });

            const res = await response.json();

            if (res.status === 'success') {
                document.getElementById('contenedorArchivos').innerHTML = res.html;
            } else {
                alert(res.message);
            }

        } catch (error) {
            console.error('Error:', error);
            alert('No se pudo contactar el servidor.');
        }
    }
</script>


<script>
    async function guardarJson(ruta, textareaId) {

        // Obtener el contenido del textarea
        const contenido = document.getElementById(textareaId).value;

        const BASE_URL = "<?= base_url() ?>";

        try {

            const response = await fetch(`${BASE_URL}guardarJson`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    ruta: ruta,
                    contenido: contenido
                })
            });

            const res = await response.json();

            if (res.status === "success") {
                alert("Archivo guardado correctamente.");
            } else {
                alert("Error: " + res.message);
            }

        } catch (error) {
            alert("Error al conectar con el servidor.");
        }
    }
</script>



<script>
    async function guardarArchivo(e, ruta, textareaID, msgID) {
        e.preventDefault();

        const contenido = document.getElementById(textareaID).value;
        const msg = document.getElementById(msgID);

        msg.innerHTML = "Guardando...";

        // Concatenamos la carpeta donde están los JSON
        const rutaCompleta =  ruta; // Ajusta "data/" según tu carpeta
        
        try {
            const response = await fetch('save', {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "ruta=" + encodeURIComponent(rutaCompleta) +
                    "&contenido=" + encodeURIComponent(contenido)
            });

            const data = await response.json();

            if (data.status === "ok") {
                msg.innerHTML = `<span style="color:green">${data.mensaje}</span>`;
            } else {
                msg.innerHTML = `<span style="color:red">${data.mensaje}</span>`;
            }

        } catch (error) {
            console.error(error);
            msg.innerHTML = `<span style="color:red">Error al guardar.</span>`;
        }
    }
</script>