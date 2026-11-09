
async function solicitar_descuento_porcentaje() {

    let porcentaje = document.getElementById("descuento_por_porcentaje").value.trim();
    let id_mesa = document.getElementById("id_mesa_pedido").value;

    // Validar que exista un pedido.
    if (id_mesa === "") {
        sweet_alert('warning', 'No hay pedido');
        return;
    }

    // Validar que el porcentaje no esté vacío.
    if (porcentaje === "") {
        document.getElementById('error_descuento').innerHTML =
            "Ingrese un porcentaje de descuento";
        return;
    }

    porcentaje = Number(porcentaje);

    // Validar el porcentaje.
    if (!Number.isFinite(porcentaje) || porcentaje < 0 || porcentaje > 100) {
        document.getElementById('error_descuento').innerHTML =
            "Valor porcentual no permitido";
        return;
    }

    document.getElementById('error_descuento').innerHTML = "";

    try {

        // Mostrar spinner.
        Swal.fire({
            title: 'Aplicando descuento',
            text: 'Por favor, espera...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // URL base de DFpyme.
        const url = document.getElementById('url').value;

        // IMPORTANTE:
        // Reemplazar la ruta y los parámetros por los reales
        // de tu controlador de descuentos.
        const respuesta = await fetch(url + '/actualizacion/descuento_porcentaje', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id_mesa: id_mesa,
                porcentaje: porcentaje
            })
        });

        if (!respuesta.ok) {
            throw new Error('Error HTTP: ' + respuesta.status);
        }

        const result = await respuesta.json();

        Swal.close();

        // Adaptar a la estructura real de respuesta del controlador.
        if (result.response) {


            
            /* $('#valor_pedido').html(resultado.total_pedido)
            $('#val_pedido').html(resultado.total_pedido) */

            document.getElementById('mesa_productos').innerHTML=result.productos
            document.getElementById('valor_pedido').innerHTML=result.total
            document.getElementById('subtotal_pedido').value = result.sub_total;
            document.getElementById('descuento_dinero').value = result.descuento;
            mostrar_mensaje_descuento(result.message, 'success');
            return;
        }

        sweet_alert('success', 'Descuento aplicado correctamente');

        // Aquí puedes actualizar los totales del pedido.

    } catch (error) {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No fue posible aplicar el descuento.'
        });

        console.error('Error al aplicar descuento:', error);
    }
}


let temporizador_descuento;
let temporizador_ocultar_descuento;

function mostrar_mensaje_descuento(mensaje, tipo = 'success') {

    const contenedor = document.getElementById('mensaje_descuento');

    // Cancelar temporizadores anteriores.
    clearTimeout(temporizador_descuento);
    clearTimeout(temporizador_ocultar_descuento);

    // Tipos de alerta permitidos.
    const tipos = ['success', 'danger', 'warning', 'info'];

    if (!tipos.includes(tipo)) {
        tipo = 'info';
    }

    // Crear la alerta Bootstrap.
    contenedor.innerHTML = '';

    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} py-2 mb-0`;
    alerta.setAttribute('role', 'alert');
    alerta.textContent = mensaje;

    contenedor.appendChild(alerta);

    // Desaparecer después de 3 segundos.
    temporizador_descuento = setTimeout(function () {

        alerta.classList.add('ocultar');

        temporizador_ocultar_descuento = setTimeout(function () {
            alerta.remove();
        }, 600);

    }, 3000);
}

