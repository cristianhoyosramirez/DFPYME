<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/clear') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-primary">Productos categoria - subcategoria</h3>
        </div>
        <div class="card-body">
            <?php foreach ($categorias as $KeyCategorias): ?>
                <div class="mb-3">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= $KeyCategorias['id']; ?>">
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse<?= $KeyCategorias['id']; ?>"
                                    aria-expanded="false"
                                    aria-controls="collapse<?= $KeyCategorias['id']; ?>">
                                    Categoria: <?= $KeyCategorias['nombrecategoria']; ?>
                                </button>
                            </h2>
                            <div
                                id="collapse<?= $KeyCategorias['id']; ?>"
                                class="accordion-collapse collapse"
                                aria-labelledby="heading<?= $KeyCategorias['id']; ?>"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <?php $sub_categoria = model('categoriasModel')->select('subcategoria,id')->where('codigocategoria', $KeyCategorias['codigocategoria'])->first(); ?>

                                    <?php if ($sub_categoria['subcategoria'] == 't'): ?>


                                        <?php $nombre_sub_categoria = model('subCategoriaModel')->select('nombre,id')->where('id_categoria', $KeyCategorias['codigocategoria'])->findAll(); ?>

                                        <?php foreach ($nombre_sub_categoria as $KeySubCategoria):  ?>



                                            <div class="row mb-3">
                                                <div class="col-2">
                                                    <label for="">Subcategoria</label>
                                                </div>
                                                <div class="col-3">
                                                    <input type="text" class="form-control" value="<?php echo $KeySubCategoria['nombre']; ?>" onkeyup="actualizarSuCategoria(this.value,<?php echo $KeySubCategoria['id']  ?>)">
                                                </div>
                                            </div>

                                            <?php $productos_subcategoria = model('productoModel')->select('id,nombreproducto,valorventaproducto')->where('id_subcategoria', $KeySubCategoria['id'])->find(); ?>


                                            <?php foreach ($productos_subcategoria as $keyProductoSubCategoria): ?>



                                                <div class="row mb-3">
                                                    <div class="col-2">
                                                        <label for=""></label>
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="text" class="form-control" value="<?php echo $keyProductoSubCategoria['nombreproducto']; ?>" onkeyup="actualizarNombreProductoSub(this.value,<?php echo $keyProductoSubCategoria['id'] ?>)">
                                                    </div>
                                                    <div class="col-3">
                                                        <input type="text" class="form-control" value="<?php echo $keyProductoSubCategoria['valorventaproducto']; ?>">
                                                    </div>
                                                </div>



                                            <?php endforeach ?>

                                        <?php endforeach ?>

                                    <?php endif ?>


                                    <?php if ($sub_categoria['subcategoria'] == 'f'): ?>

                                        <?php $productos_subcategoria = model('productoModel')->select('id,nombreproducto,valorventaproducto')->where('codigocategoria', $KeyCategorias['codigocategoria'])->find(); ?>

                                        <?php foreach ($productos_subcategoria as $keySubCategoria): ?>
                                            <div class="row mb-3">
                                                <div class="col-3">
                                                    <input type="text" class="form-control" onkeyup="actualizacionProducto(this.value,<?php echo $keySubCategoria['id']; ?>)" value="<?php echo $keySubCategoria['nombreproducto']; ?>">
                                                </div>
                                            </div>
                                        <?php endforeach ?>


                                    <?php endif ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<script>
    async function actualizarNombreProductoSub(valor, id) {
        try {
            const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
            const url = `${baseUrl}/categoria/actualizar_productos`; // Construye la URL dinámica

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id,
                    valor: valor
                })
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();
            //alert(`Producto actualizado: ${data.message}`);
        } catch (error) {
            console.error('Hubo un problema al actualizar el producto:', error);
            alert('No se pudo actualizar el producto. Por favor, intenta de nuevo.');
        }
    }
</script>



<script>
    async function actualizacionProducto(valor, id) {
        try {
            const baseUrl = "<?php echo base_url(); ?>"; // Obtiene el base_url desde PHP
            const url = `${baseUrl}/categoria/actualizacion_productos`; // Construye la URL dinámica

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id,
                    valor: valor
                })
            });

            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }

            const data = await response.json();
            //alert(`Producto actualizado: ${data.message}`);
        } catch (error) {
            console.error('Hubo un problema al actualizar el producto:', error);
            alert('No se pudo actualizar el producto. Por favor, intenta de nuevo.');
        }
    }
</script>

<?= $this->endSection('content') ?>