<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<script>
  document.addEventListener("DOMContentLoaded", async function () {
    try {
      // Llama a tu endpoint (ajusta la URL según tu API)
      const response = await fetch("http://localhost/dfpyme/whatsapp/index");

      if (!response.ok) {
        throw new Error("Error en la petición: " + response.status);
      }

      const data = await response.json();

      // Mostrar datos en el DOM
      const contenedor = document.getElementById("clientes");
      contenedor.innerHTML = "";
      data.forEach(cliente => {
        const item = document.createElement("p");
        item.textContent = `${cliente.nombrescliente} - ${cliente.emailcliente}`;
        contenedor.appendChild(item);
      });

    } catch (error) {
      console.error("Error al cargar los clientes:", error);
    }
  });
</script>




<?= $this->endSection('content') ?>