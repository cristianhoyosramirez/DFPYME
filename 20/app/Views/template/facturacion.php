<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= $this->renderSection('title') ?>&nbsp;-&nbsp;DF PYME</title>
    <!-- CSS files -->
    <link href="<?= base_url() ?>/Assets/css/tabler.min.css" rel="stylesheet" />
    <!-- Select 2 -->
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Data tables -->
    <link href="<?= base_url() ?>/Assets/plugin/data_tables/bootstrap.min.css" />
    <link href="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.css" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
    <!-- Jquery-ui -->
    <link href="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.css" rel="stylesheet">

    <!-- Data tables -->
    <link href="<?= base_url() ?>/Assets/plugin/data_tables/bootstrap.min.css" />
    <link href="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.css" />
</head>
<?php $session = session(); ?>

<body>
    <div class="wrapper">
        <?= $this->include('layout/header_mesas') ?>


        <div class="page-wrapper">
            <div class="container-xl">
            </div>
            <div class="page-body">
                <?= $this->renderSection('content') ?>
   

            </div>
            <?= $this->include('layout/footer') ?>
        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!-- J QUERY -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>

        <!-- jQuery-ui -->
        <script src="<?php echo base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>


        <!-- Data tables -->
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.js"></script>

    
</body>

</html>