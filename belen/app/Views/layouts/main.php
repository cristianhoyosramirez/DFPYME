<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?= esc($title ?? 'FACTURACIÓN') ?></title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="<?= base_url('assets/img/kaiadmin/favicon.ico') ?>" type="image/x-icon" />

    <!-- WebFont -->
    <script src="<?= base_url('assets/js/plugin/webfont/webfont.min.js') ?>"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ["<?= base_url('assets/css/fonts.min.css') ?>"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/kaiadmin.min.css') ?>">

    <!-- CSS por vista -->
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <div class="wrapper">

        <!-- SIDEBAR -->
        <?= $this->include('partials/sidebar') ?>

        <div class="main-panel">

            <!-- HEADER / NAVBAR -->
            <?= $this->include('partials/navbar') ?>

            <!-- CONTENIDO -->
            <div class="container">
                <div class="page-inner">

                    <div id="ajax-content">
                        <?= $this->renderSection('content') ?>
                    </div>

                </div>
            </div>

            <!-- FOOTER -->
            <?= $this->include('partials/footer') ?>

        </div>
    </div>

    <!-- Core JS Files -->
    <script src="<?= base_url('assets/js/core/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/core/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/core/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugin/sweetalert/sweetalert2@11.js') ?>"></script>



    <!-- jQuery Scrollbar -->
    <script src="<?= base_url('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>

    <!-- KaiAdmin JS -->
    <script src="<?= base_url('assets/js/kaiadmin.min.js') ?>"></script>


    <script>
        document.addEventListener('click', function(e) {

            const link = e.target.closest('a');
            if (!link) return;

            const li = link.closest('li');
            if (!li) return;

            /* =========================
               LIMPIAR TODOS LOS ACTIVE
            ========================= */
            document.querySelectorAll('.nav li.active')
                .forEach(el => el.classList.remove('active'));

            /* =========================
               MANEJO SUBMENÚ
            ========================= */
            const submenuLi = link.closest('.nav-item.submenu');

            if (submenuLi) {
                // Activar submenú
                li.classList.add('active');

                // Activar menú padre
                submenuLi.classList.add('active');

                // Mostrar collapse
                const collapse = submenuLi.querySelector('.collapse');
                if (collapse) collapse.classList.add('show');
            }
            /* =========================
               MENÚ NORMAL
            ========================= */
            else {
                li.classList.add('active');

                // Cerrar cualquier collapse abierto
                document.querySelectorAll('.nav-item.submenu .collapse.show')
                    .forEach(c => c.classList.remove('show'));
            }

            /* =========================
               AJAX SOLO PARA ajax-link
            ========================= */
            if (!link.classList.contains('ajax-link')) return;

            e.preventDefault();

            const url = link.getAttribute('href');
            const title = link.dataset.title || link.textContent.trim();
            const icon = link.dataset.icon || '';

            const pageTitle = document.getElementById('page-title');
            if (pageTitle) {
                pageTitle.innerHTML = icon ?
                    `<i class="${icon} me-2 text-primary"></i>${title}` :
                    title;
            }

            Swal.fire({
                title: 'Cargando',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.text())
                .then(html => {
                    document.getElementById('ajax-content').innerHTML = html;
                    Swal.close();
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudo cargar el contenido', 'error');
                });

        });
    </script>







    <script>
        document.addEventListener('change', function(e) {

            if (e.target.id !== 'pro_tipo') return;

            const url = e.target.dataset.url;
            const contenedor = document.getElementById('bloqueComponentes');

            if (!url || !contenedor) return;

            if (e.target.value !== 'compuesto') {
                contenedor.innerHTML = '';
                contenedor.classList.add('d-none');
                return;
            }

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        tipo: e.target.value
                    })
                })
                .then(res => res.text())
                .then(html => {
                    contenedor.innerHTML = html;
                    contenedor.classList.remove('d-none');
                })
                .catch(() => {
                    alert('Error cargando componentes');
                });

        });
    </script>






</body>

</html>