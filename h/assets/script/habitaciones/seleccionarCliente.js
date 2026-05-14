
    function seleccionarCliente(id, valor,telefono) {
        // Pegar nombre/valor en el input visible
        document.getElementById('listaClientes').innerHTML = "";
        document.getElementById('nombre_completo').value = valor;
        document.getElementById('id_cliente').value = id;
        document.getElementById('telefono_cliente').value = telefono;
        document.getElementById('huesped').value = "";
    }
