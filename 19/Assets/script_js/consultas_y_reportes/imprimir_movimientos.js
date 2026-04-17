function imprimir_movimientos() {
    var url = document.getElementById("url").value;
    let id_apertura = document.getElementById("id_apertura").value;

    Swal.fire({
        title: 'Procesando impresión...',
        html: 'Por favor espere <b></b>',
        timerProgressBar: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();

            const b = Swal.getHtmlContainer().querySelector('b');
            let timerInterval = setInterval(() => {
                if (Swal.getTimerLeft()) {
                    b.textContent = Math.ceil(Swal.getTimerLeft() / 1000) + " segundos";
                }
            }, 100);
        }
    });

    $.ajax({
        data: {
            id_apertura
        },
        url: url + "/" + "pedidos/imprimir_movimiento_caja",
        type: "POST",
        success: function (resultado) {
            Swal.close(); // cerrar loader

            var resultado = JSON.parse(resultado);

            if (resultado.resultado == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Impresión correcta',
                    text: 'Movimientos de caja impresos correctamente',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            }

            if (resultado.resultado == 2) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo imprimir'
                });
            }
        },
        error: function () {
            Swal.close();

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en la comunicación con el servidor'
            });
        }
    });
}