<style>
    .mesero-item {
        margin-bottom: 20px;
        /* Agrega un espacio de 20 píxeles entre los elementos */
    }
</style>
<!-- Modal -->

</div>

<script>
    function cerrar_modal_creacion_mesero() {
        var lista_meseros = document.getElementById("meseros");
        lista_meseros.style.display = "block";

        var crear_meseros = document.getElementById("crear_meseros");
        crear_meseros.style.display = "none";
    }
</script>


<script>
    function agregar_mesero() {
        var lista_meseros = document.getElementById("meseros");
        lista_meseros.style.display = "none";

        var crear_meseros = document.getElementById("crear_meseros");
        crear_meseros.style.display = "block";
        $('#texto_mesero').html('Crear mesero')
    }
</script>

<script>
    function crear_mesero() {
        let url = document.getElementById("url").value;
        let nombre = document.getElementById("nombre").value;
        let id_mesa = document.getElementById("id_mesa_pedido").value;
        if (nombre != "") {
            $.ajax({
                data: {
                    nombre,
                    id_mesa
                },
                url: url + "/" + "pedidos/crear_mesero",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        $("#nombre").val("");
                        $("#modal_meseros").modal("hide");
                        $("#nombre_mesero").html('Mesero: ' + resultado.nombre);
                        $("#lista_meseros").html(resultado.meseros);

                        var lista_meseros = document.getElementById("meseros");
                        lista_meseros.style.display = "block";

                        var crear_meseros = document.getElementById("crear_meseros");
                        crear_meseros.style.display = "none";



                    }
                    if (resultado.resultado == 0) {

                        $('#error_nombre').html('Nombre ya existe ')

                    }

                },
            });
        }
        if (nombre == "") {
            $('#error_nombre').html('Dato necessario ')
        }
    }
</script>

<script>
    function limpiar_campo() {
        $('#error_nombre').html('');
    }
</script>