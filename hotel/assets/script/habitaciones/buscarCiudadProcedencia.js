async function buscarCiudadProcedencia(valor) {
    try {
        
        //const response = await fetch(base_url + '/habitaciones/buscarCliente', {
        const response = await fetch(`${BASE_URL}/habitaciones/buscarCiudadProcedencia`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                valor: valor
            })
        });

        const data = await response.json();



        const lista = document.getElementById('listaMunicipiosProcedencia');

        if (data.success) {
            // Limpiar y mostrar resultados
            lista.innerHTML = data.ciudades; // suponiendo que data.clientes son <li class="list-group-item">
        } else {

            document.getElementById('listaMunicipiosProcedencia').innerHTML = '';
            lista.innerHTML = `
        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
            No hay resultados 😢
        </div>
    `;

            // Desaparecer automáticamente después de 3 segundos
            setTimeout(() => {
                // Opción 1: quitar clase 'show' para que fade haga efecto
                const alert = lista.querySelector('.alert');
                if (alert) alert.classList.remove('show');

                // Opción 2 (más drástico): eliminar el alert del DOM
                // lista.innerHTML = '';
            }, 3000);
        }

        // aquí puedes llenar la lista, mostrar resultados, etc.

    } catch (error) {
        console.error('Error en la búsqueda:', error);
    }
}