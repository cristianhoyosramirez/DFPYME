<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    #loader-overlay {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.3s ease;
    }

    .loader-hidden {
        opacity: 0;
        pointer-events: none;
    }

    .spinner {
        width: 45px;
        height: 45px;
        border: 4px solid #dee2e6;
        border-top: 4px solid #0d6efd;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<div class="container-fluid">

    <div id="main-content">

    </div>

    <div id="loader-overlay" style="display:none;">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

</div>




<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {

        const content = document.getElementById("main-content");

        function showLoader() {
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function hideLoader() {
            Swal.close();
        }

        async function loadPage(url, pushState = true) {
            try {
                showLoader();

                const response = await fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                if (!response.ok) {
                    throw new Error("Error en la carga");
                }

                const html = await response.text();

                content.style.opacity = 0;

                setTimeout(() => {
                    content.innerHTML = html;
                    content.style.transition = "opacity 0.3s ease";
                    content.style.opacity = 1;
                }, 150);

                if (pushState) {
                    history.pushState({
                        url
                    }, "", url);
                }

            } catch (error) {
                content.innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar la vista.
                </div>
            `;
            } finally {
                hideLoader();
            }
        }

        document.addEventListener("click", function(e) {
            const link = e.target.closest(".ajax-link");
            if (link) {
                e.preventDefault();
                const url = link.getAttribute("href");
                loadPage(url);
            }
        });

        window.addEventListener("popstate", function(e) {
            if (e.state && e.state.url) {
                loadPage(e.state.url, false);
            }
        });

    });
</script> -->

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const content = document.getElementById("main-content");

        /* =========================
           LOADER
        ========================== */
        function showLoader() {
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }

        function hideLoader() {
            Swal.close();
        }

        /* =========================
           INICIALIZADOR DE VISTAS
        ========================== */
        function initView(url) {

            // 🔥 aquí decides qué ejecutar según la vista
            if (url.includes("reportes/ventas")) {
                if (typeof initVentas === "function") {
                    initVentas();
                }
            }

            // puedes seguir agregando:
            // if (url.includes("clientes")) { initClientes(); }
        }

        /* =========================
           CARGA AJAX
        ========================== */
        async function loadPage(url, pushState = true) {
            try {
                showLoader();

                const response = await fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                if (!response.ok) throw new Error("Error en la carga");

                const html = await response.text();

                content.style.opacity = 0;

                setTimeout(() => {
                    content.innerHTML = html;
                    content.style.transition = "opacity 0.3s ease";
                    content.style.opacity = 1;

                    // 🔥 inicializa scripts de la vista
                    initView(url);

                }, 150);

                if (pushState) {
                    history.pushState({
                        url
                    }, "", url);
                }

            } catch (error) {
                content.innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar la vista.
                </div>
            `;
                console.error(error);
            } finally {
                hideLoader();
            }
        }

        /* =========================
           CLICK EN LINKS
        ========================== */
        document.addEventListener("click", function(e) {
            const link = e.target.closest(".ajax-link");

            if (!link) return;

            e.preventDefault();

            const url = link.getAttribute("href");
            const currentPath = window.location.pathname;
            const newPath = new URL(url, window.location.origin).pathname;

            // 🔥 si es la misma URL → recargar contenido sin pushState
            if (currentPath === newPath) {
                loadPage(url, false);
                return;
            }

            loadPage(url);
        });

        /* =========================
           BOTÓN ATRÁS / ADELANTE
        ========================== */
        window.addEventListener("popstate", function(e) {
            if (e.state && e.state.url) {
                loadPage(e.state.url, false);
            }
        });

        /* =========================
           PRIMERA CARGA (IMPORTANTE)
        ========================== */
        initView(window.location.pathname);

    });
</script>





<?= $this->endSection() ?>