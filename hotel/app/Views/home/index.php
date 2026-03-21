<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    /* Contenedor general de mesas */
    .mesas-wrapper {
        height: calc(100vh - 140px);
        /* ajusta según tu header */
        overflow-y: auto;
        padding-right: 5px;
    }

    .mesa-card {
        border-radius: 14px;
        transition: all .25s ease;
    }

    .mesa-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
    }
</style>

<div class="container-fluid">

    <div id="contenido_principal">


    </div>

</div>


<script>
    // 🔥 Función universal para formatear miles
    function formatearMiles(valor) {
        valor = valor.toString().replace(/\D/g, ""); // quitar todo lo que no sea número
        return valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // 🔥 Función para obtener valor real (sin puntos)
    function quitarMiles(valor) {
        return valor.toString().replace(/\./g, "");
    }

    // ✅ Evento para formatear mientras escribe
    document.addEventListener("input", function(e) {
        if (!e.target.classList.contains("miles")) return;

        const inputMiles = e.target;
        const form = inputMiles.closest("form");
        const inputReal = form.querySelector(".miles-real");

        let valorLimpio = quitarMiles(inputMiles.value);

        inputMiles.value = formatearMiles(valorLimpio);
        inputReal.value = valorLimpio;
    });

    // ✅ Guardar movimiento
    document.addEventListener("submit", async function(e) {

        const form = e.target;

        if (form.id !== "formMovimiento") return;

        e.preventDefault();

        const inputMiles = form.querySelector(".miles");
        const inputReal = form.querySelector(".miles-real");

        let monto = inputReal.value;

        if (monto === "" || parseFloat(monto) <= 0) {
            alert("El monto debe ser mayor a 0");
            return;
        }

        const formData = new FormData(form);

        // ⚠️ aseguramos que el valor real sea el que se envía
        formData.set("monto", monto);

        try {
            const response = await fetch("<?= base_url('movimientos/guardar') ?>", {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            if (data.status === "ok") {
                alert("Movimiento guardado correctamente");

                form.reset();
                inputMiles.value = "";
                inputReal.value = "";

            } else {
                alert(data.msg);
            }

        } catch (error) {
            console.error(error);
            alert("Error al guardar el movimiento");
        }

    });
</script>




<script>
    document.addEventListener("click", async function(e) {

        const link = e.target.closest(".ajax-link");

        if (!link) return;

        e.preventDefault();

        const url = link.getAttribute("href");
        const title = link.dataset.title;
        const icon = link.dataset.icon;

        // Si tienes un título en tu layout
        const pageTitle = document.getElementById("page-title");
        if (pageTitle) {
            pageTitle.innerHTML = `<i class="${icon}"></i> ${title}`;
        }

        const contenedor = document.getElementById("contenido_principal");

        contenedor.innerHTML = `
        <div style="padding:40px;text-align:center;">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Cargando...</p>
        </div>
    `;

        try {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error("Error HTTP: " + response.status);
            }

            const html = await response.text();
            contenedor.innerHTML = html;

        } catch (error) {
            contenedor.innerHTML = `
            <div class="alert alert-danger">
                Error al cargar el formulario: ${error.message}
            </div>
        `;
        }

    });
</script>

<?= $this->endSection() ?>