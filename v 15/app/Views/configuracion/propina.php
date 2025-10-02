<?php $session = session(); ?>
<?= $this->extend('template/template_boletas') ?>
<?= $this->section('title') ?>
Configuración
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<p class="text-primary text-center h3 bold"> Configuración de propina </p>
<div class="card container">
    
    <div class="card-body">
        <form action="<?= base_url('configuracion/configuracion_propina') ?>" method="POST">
            <input type="hidden" value="<?php echo base_url() ?>" id="url">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="inputState" class="form-label">Tipo propina </label>
                    <select id="inputState" class="form-select" name="propina" id="propina">
                        <option value="0">Sin redondeo </option>
                        <option value="1">Con redondeo </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="inputState" class="form-label">Porcentaje por defecto </label>
                    <input type="number" class="form-control" id="porcentaje" name="porcentaje" value="<?php echo $porcetaje_propina ?>">
                    <div class="text-danger"><?= session('errors.porcentaje') ?></div>
                </div>

                <div class="col-md-4">
                    <label for="inputState" class="form-label">Calculo áutomatico </label>
                    <select id="inputState" class="form-select" name="calculo_automatico" id="calculo_automatico">
                        <?php $propina = model('configuracionPedidoModel')->select('calculo_propina')->first(); ?>

                        <?php if ($propina['calculo_propina'] === 't'): ?>
                            <option value="true" selected>Si </option>
                            <option value="false">No </option>
                        <?php endif ?>
                        <?php if ($propina['calculo_propina'] === 'f'): ?>
                            <option value="true">Si </option>
                            <option value="false" selected>No </option>
                        <?php endif ?>

                    </select>
                </div>

            </div>

            <div class="row">
                <div class="col-4">
                    <label for="" class="form-label">Texto propina </label>
                    <textarea name="" id="" class="form-control" oninput="actualiarTextoPropina(this.value)"><?php echo $texto ?></textarea>
                </div>
                <div class="col-4">
                    <label for="" class="form-label">Permitir impresion de texto propina en orden de pedido , prefactura y factura </label>
                    <select name="imprimirTexto" id="imprimirTexto" class="form-select" onchange="permitirImprimirTexto(this.value)">
                        <option value="t" <?= ($imprimirTexto === 't') ? 'selected' : '' ?>>Si</option>
                        <option value="f" <?= ($imprimirTexto === 'f') ? 'selected' : '' ?>>No</option>
                    </select>

                </div>

            </div>
            <br>
            <div class="row">
                <div class="col">
                    <div class="row text-end">
                        <div class="col-6">
                        </div>
                        <div class="col-3">
                            <a href="#" class="btn btn-outline-red active w-100">
                                Cancelar
                            </a>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-outline-green active w-100">
                                Actualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
  async function permitirImprimirTexto(valor) {
    try {
      let baseUrl = document.getElementById("url").value;
      let url = `${baseUrl}/configuracion/update_imprimir_texto`;

      let response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({ valor: valor })
      });

      if (!response.ok) {
        throw new Error("Error en la petición: " + response.status);
      }

      let data = await response.json();
      console.log("Respuesta del servidor:", data);

      if (data.response=="success") {
        Swal.fire({
          icon: "success",
          title: "Configuración actualizada ✅",
          text: data.message || "Se guardó correctamente",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#198754"
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "No se pudo guardar ❌",
          text: data.message || "Ocurrió un error en la actualización",
          confirmButtonText: "Entendido",
          confirmButtonColor: "#dc3545"
        });
      }
    } catch (error) {
      console.error("Error en permitirImprimirTexto():", error);
      Swal.fire({
        icon: "error",
        title: "Error inesperado",
        text: "No se pudo procesar la petición.",
        confirmButtonText: "Cerrar",
        confirmButtonColor: "#dc3545"
      });
    }
  }
</script>


<script>
    async function actualizarImprimirTexto(valor) {
        try {
            const response = await fetch("<?= base_url('configuracion/actualizar_imprimir') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest", // útil para CI4
                },
                body: JSON.stringify({
                    valor: valor
                })
            });

            if (!response.ok) {
                throw new Error("Error en la solicitud");
            }

            const data = await response.json();
        

        } catch (error) {
            console.error("Error:", error);
            alert("❌ Ocurrió un error al actualizar");
        }
    }
</script>


<script>
    async function actualiarTextoPropina(texto) {

        try {



            let response = await fetch("<?= base_url('/configuracion/actualizar_texto_propina') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({
                    texto: texto
                })
            });

            if (!response.ok) throw new Error("Error en la petición");

            let data = await response.json();

            if (data.response == "success") {

            }

        } catch (error) {
            console.error("Error:", error);
            document.getElementById("texto-propina").innerText = "No se pudo actualizar la propina";
        }
    }
</script>






<script>
    function actualizar_mesero(valor) {
        var url = document.getElementById("url").value;
        $.ajax({
            data: {
                valor,
            },
            url: url + "/" + "configuracion/actualizar_mesero",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Cambio exitoso'
                    })

                }
            },
        });

    }
</script>


<?= $this->endSection('content') ?>