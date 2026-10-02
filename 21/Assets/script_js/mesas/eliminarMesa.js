async function eliminarMesa(id) {

    const confirmar = await Swal.fire({
        title: '¿Eliminar mesa?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (!confirmar.isConfirmed) {
        return;
    }

    try {
        const url = document.getElementById("url").value;

        const response = await fetch(url+"/mesas/eliminarMesa", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: id
            })
        });

        const data = await response.json();

        if (data.status) {

            await Swal.fire({
                icon: 'success',
                title: 'Mesa eliminada correctamente',
                timer: 1500,
                showConfirmButton: false
            });

            location.reload();

        } else {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.mensaje
            });

        }

    } catch (error) {

        console.error(error);

        Swal.fire({
            icon: 'error',
            title: 'Error del sistema',
            text: 'No fue posible eliminar la mesa'
        });

    }
}