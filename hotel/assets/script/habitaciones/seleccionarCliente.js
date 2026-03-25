
    function seleccionarCliente(id, valor) {
        // Pegar nombre/valor en el input visible
        document.getElementById('listaClientes').innerHTML = "";
        document.getElementById('nombre_completo').value = valor;
        document.getElementById('id_cliente').value = id;
        document.getElementById('huesped').value = "";
    }
