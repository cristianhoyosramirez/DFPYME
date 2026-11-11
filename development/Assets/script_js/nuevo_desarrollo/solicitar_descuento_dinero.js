
async function solicitar_descuento_dinero() {

    
    let descuento_dinero = Number(document.getElementById("descuento_dinero").value.trim().replace(/\./g, ''));
    let id_mesa = document.getElementById("id_mesa_pedido").value;

    // Validar que exista un pedido.
    if (id_mesa === "") {
        sweet_alert('warning', 'No hay pedido');
        return;
    }

   

    // Validar que el descuento no esté vacío.
    if (descuento_dinero === "") {
        document.getElementById('error_descuento').innerHTML =
            "Ingrese un monto de descuento";
        return;
    }

    // Convertir los valores a números.
   
    const descuento = Number(descuento_dinero);



    // Validar el descuento monetario.
    if (!Number.isFinite(descuento) || descuento <= 0) {
        document.getElementById('error_descuento').innerHTML =
            "Ingrese un valor de descuento válido";
        return;
    }

    // Validar que el descuento no supere el total a pagar.
    if (descuento > total) {
        document.getElementById('error_descuento').innerHTML =
            "El descuento no puede ser mayor al total a pagar";
        return;
    }

    document.getElementById('error_descuento').innerHTML = "";

    try {

        Swal.fire({
            title: 'Aplicando descuento',
            text: 'Por favor, espera...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const url = document.getElementById('url').value;

        const respuesta = await fetch(url + '/actualizacion/descuento_dinero', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id_mesa: id_mesa,
                descuento: descuento
            })
        });

        if (!respuesta.ok) {
            throw new Error('Error HTTP: ' + respuesta.status);
        }

        const result = await respuesta.json();

        Swal.close();

        if (result.response) {

            document.getElementById('mesa_productos').innerHTML = result.productos;
            document.getElementById('valor_pedido').innerHTML = result.total;
            document.getElementById('subtotal_pedido').value = result.sub_total;
            document.getElementById('descuento_dinero').value = result.descuento;
            document.getElementById('descuento_por_porcentaje').value = result.porcentaje_descuento;

            mostrar_mensaje_descuento(result.message, 'success');

            return;
        }

        sweet_alert(
            'warning',
            result.message || 'No fue posible aplicar el descuento.'
        );

    } catch (error) {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No fue posible aplicar el descuento.'
        });

        console.error('Error al aplicar descuento:', error);
    }
}
