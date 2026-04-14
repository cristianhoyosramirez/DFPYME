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
</body>

</html>