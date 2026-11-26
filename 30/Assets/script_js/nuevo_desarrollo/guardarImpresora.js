async function guardarImpresora(id_impresora) {
    try {
        let id_mesa = document.getElementById("id_mesa_pedido").value;
        let tem_propina = document.getElementById("propina_del_pedido").value;
        let propina = tem_propina.replace(/\./g, '');
        const url = document.getElementById("url").value.replace(/\/$/, '');

        // ✅ Validar que se haya seleccionado una impresora válida
        if (!id_impresora || id_impresora.trim() === "") {
            Swal.fire({
                icon: "warning",
                title: "Impresora no seleccionada",
                text: "Debe seleccionar una impresora válida antes de continuar.",
                confirmButtonText: "Entendido",
                focusConfirm: true
            });
            return;
        }

        let response = await fetch(`${url}/pedidos/imprimir_op`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({
                idImpresora: id_impresora,
                id_mesa: id_mesa,
                propina: propina
            })
        });

        let data = await response.json();

        if (data.response == "success") {
            // ✅ Mostrar alerta de éxito
            sweet_alert_centrado('success', 'Impresión exitosa');

            // ✅ Cerrar modal
            $("#modalConfOp").modal("hide");


        } else {
            alert("⚠️ Error al guardar la impresora");
        }
    } catch (error) {
        console.error("Error en la petición:", error);
    }
}