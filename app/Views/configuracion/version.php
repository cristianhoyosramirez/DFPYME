<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
VERSIÓN
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <p class="text-center text-primary h3 "><?php echo"Version: ". $version ?></p>
</div>



<?= $this->endSection('content') ?>