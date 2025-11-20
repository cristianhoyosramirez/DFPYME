/* function retiro_dinero() {

    let id_mesa = document.getElementById("id_mesa_pedido").value;

    // Agrega un event listener para cuando se muestra el modal
    document.getElementById('modal_retiro_dinero').addEventListener('shown.bs.modal', function () {
        // Selecciona el input y pon el foco en él
        document.getElementById('valor_retiro').focus();
    })
    $("#modal_retiro_dinero").modal("show");
} */


async function retiro_dinero() {
    try {
        const url = document.getElementById("url").value; // Obtiene la base URL
        const response = await fetch(`${url}/devolucion/consultarApertura`, {
            method: "GET"
        });

        if (!response.ok) {
            throw new Error("Error en la respuesta del servidor");
        }

        const data = await response.json();

        if (data.response ==false) {
            await Swal.fire({
                icon: "info",
                title: "No se puede retirar dinero",
                text: "No hay apertura de caja válida.",
                confirmButtonText: "Aceptar"
            });
            return;
        }

        if (data.response == true) {
            // Abre el modal y enfoca el campo de valor
            $("#modal_retiro_dinero").modal("show");

            document.getElementById("modal_retiro_dinero").addEventListener("shown.bs.modal", () => {
                document.getElementById("valor_retiro").focus();
            });
        }

    } catch (error) {
        console.error("Error al verificar la apertura de caja:", error);
        await Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ocurrió un problema al verificar la apertura de caja. Inténtalo nuevamente.",
            confirmButtonText: "Aceptar"
        });
    }
}


