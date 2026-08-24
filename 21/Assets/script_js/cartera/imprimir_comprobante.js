 async function imprimir_comprobante(id) {

    alert(id)

            try {

                const response = await fetch(
                    url + '/consultas_y_reportes/imprimir_comprobante_ingreso', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    }
                );

                if (!response.ok) {
                    throw new Error('No fue posible generar el comprobante');
                }

               

            } catch (error) {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });

            }

        }