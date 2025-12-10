<!DOCTYPE html>
<html lang="es">

<head>
    <?= $this->include('layouts/header'); ?>
</head>

<!-- FLEXBOX PARA QUE EL FOOTER SE QUEDE ABAJO -->
<body class="d-flex flex-column min-vh-100">

    <div id="app" class="d-flex flex-grow-1">

        <!-- SIDEBAR -->
        <?= $this->include('layouts/sidebar'); ?>

        <!-- CONTENEDOR PRINCIPAL -->
        <div id="main" class="d-flex flex-column flex-grow-1">

            <!-- HEADER SUPERIOR -->
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <!-- CONTENIDO DE LA PÁGINA -->
            <div class="page-content flex-grow-1">
                <?= $this->renderSection('content'); ?>
            </div>

            <!-- FOOTER FIJO ABAJO -->
            <footer class="mt-auto">
                <?= $this->include('layouts/footer'); ?>
            </footer>

        </div>
    </div>

</body>

</html>
