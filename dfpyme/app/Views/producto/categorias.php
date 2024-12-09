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
                                    class="accordion-button collapsed "
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse<?= $KeyCategorias['id']; ?>"
                                    aria-expanded="false"
                                    aria-controls="collapse<?= $KeyCategorias['id']; ?>">
                                    <span class="text-success ">Categoria: <?= $KeyCategorias['nombrecategoria']; ?></span>

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
                                                    <label for="" class="text-primary h3">Subcategoria</label>
                                                </div>
                                                <div class="col-3">
                                                    <!--   <input type="text" class="form-control" value="<?php #echo $KeySubCategoria['nombre']; 
                                                                                                            ?>" onkeyup="actualizarSuCategoria(this.value,<?php echo $KeySubCategoria['id']  ?>)"> -->


                                                    <p class="text-primary h3"><?php echo $KeySubCategoria['nombre']; ?></p>


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

                                                        <div class="input-icon mb-3">
                                                            <span class="input-icon-addon">
                                                                <!-- Ícono de dólar -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                                    <path d="M12 3v3m0 12v3" />
                                                                </svg>
                                                            </span>
                                                            <input id="valor<?php echo $keyProductoSubCategoria['id'];  ?>" type="text" class="form-control" value="<?php echo number_format($keyProductoSubCategoria['valorventaproducto'], 0, ',', '.'); ?>">
                                                            <span class="input-icon-addon" onclick="clearInput()" style="cursor: pointer;">
                                                                <!-- Ícono de "X" -->
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M18 6l-12 12" />
                                                                    <path d="M6 6l12 12" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>


                                            <?php endforeach ?>

                                            <div class="text-end">
                                                <button type="button" class="btn btn-outline-primary">Agregar otro porducto a Estofadas porcion </button>
                                            </div>
                                        <?php endforeach ?>

                                    <?php endif ?>


                                    <?php if ($sub_categoria['subcategoria'] == 'f'): ?>

                                        <?php $productos_subcategoria = model('productoModel')->select('id,nombreproducto,valorventaproducto')->where('codigocategoria', $KeyCategorias['codigocategoria'])->find(); ?>

                                        <?php foreach ($productos_subcategoria as $keySubCategoria): ?>
                                            <div class="row mb-3">
                                                <div class="col-3">
                                                    <input type="text" class="form-control" onkeyup="actualizacionProducto(this.value, <?php echo $keySubCategoria['id']; ?>)" value="<?php echo $keySubCategoria['nombreproducto']; ?>">
                                                </div>
                                                <div class="col-3">
                                                    <div class="input-icon mb-3">
                                                        <span class="input-icon-addon">
                                                            <!-- Icono de dólar -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 1 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                                <path d="M12 3v3m0 12v3" />
                                                            </svg>
                                                        </span>
                                                        <input type="text" class="form-control" id="valor<?php echo $keySubCategoria['id']; ?>" placeholder="Valor de venta" value="<?php echo number_format($keySubCategoria['valorventaproducto'], 0, ',', '.'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-2 d-flex justify-content-end">
                                                    <button class="btn btn-outline-danger btn-icon" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Borrar producto" onclick="eliminarProducto(<?php echo $keySubCategoria['id']; ?>)">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <line x1="4" y1="7" x2="20" y2="7" />
                                                            <line x1="10" y1="11" x2="10" y2="17" />
                                                            <line x1="14" y1="11" x2="14" y2="17" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>

                                                    </button>
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