// ========== FORMATEO DE TRANSACCIÓN ==========
const number = document.querySelector("#transaccion_cierre");

function formatNumber(n) {
  // Quitamos todo excepto dígitos
  n = String(n).replace(/\D/g, "");
  if (n === "") return "";

  // ⚠️ No quitamos los ceros iniciales
  // (así si escribes "0" se mantiene)

  // Formateamos con puntos cada tres dígitos
  return n.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

number.addEventListener("keyup", (e) => {
  const element = e.target;
  let value = element.value;
  element.value = formatNumber(value);
});

// ========== FORMATEO DE EFECTIVO ==========
const number_efectivo = document.querySelector("#efectivo_de_cierre");

number_efectivo.addEventListener("keyup", (e) => {
  const element = e.target;
  let value = element.value;
  element.value = formatNumber(value);
});

// ========== FUNCIÓN DE CIERRE DE CAJA ==========
/* function cierre_caja_1() {
  // Obtener valores de los inputs
  var cierre_efectivo = document.getElementById("efectivo_de_cierre").value;
  var cierre_transaccion = document.getElementById("transaccion_cierre").value;

  // Quitar puntos existentes para evitar errores
  var efectivoFormat = cierre_efectivo.replace(/\./g, "");
  var transaccionFormat = cierre_transaccion.replace(/\./g, "");

  // Evitar NaN cuando los inputs están vacíos
  var efectivoVal = efectivoFormat === "" ? 0 : parseInt(efectivoFormat);
  var transaccionVal = transaccionFormat === "" ? 0 : parseInt(transaccionFormat);

  // Sumar los valores
  var sub_total = efectivoVal + transaccionVal;

  // Función para formatear con punto siempre
  function formatearConPunto(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  // Resultado final con puntos
  var resultado = formatearConPunto(sub_total);

  console.log(resultado); // Ej: "12.345.678"

  Swal.fire({
    icon: "question",
    title:
      "!Va a realizar el cierre de caja! con un valor de " + "$" + resultado,
    showCancelButton: true,
    confirmButtonText: "Aceptar",
    confirmButtonColor: "#2AA13D",
    cancelButtonText: "Cancelar",
    cancelButtonColor: "#C13333",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      var url = document.getElementById("url").value;
      var usuario_cierre = document.getElementById("usuario_cierre").value;
      var efectivo_de_cierre = document.getElementById("efectivo_de_cierre").value;
      var transaccion_de_cierre = document.getElementById("transaccion_cierre").value;

      var efectivoFormat = efectivo_de_cierre.replace(/[.]/g, "");
      var transaccionFormat = transaccion_de_cierre.replace(/[.]/g, "");
      var numero_caja = document.getElementById("numero_caja").value;

      $("#error_apertura_caja").html("");
      $.ajax({
        data: {
          usuario_cierre,
          efectivoFormat,
          transaccionFormat,
          numero_caja
        },
        url: url + "/" + "caja/generar_cierre",
        type: "POST",
        success: function (resultado) {
          var resultado = JSON.parse(resultado);

          if (resultado.resultado == 1) {
            var id_cierre = resultado.id_cierre;
            Swal.fire({
              icon: "question",
              title:
                "El cierre de caja fue éxitoso ¿Desea imprimir el comprobante? ",
              showCancelButton: false,
              denyButtonText: `No imprimir`,
              showDenyButton: true,
              confirmButtonText: "Imprimir",
              confirmButtonColor: "#2AA13D",
              cancelButtonText: "Cancelar",
              cancelButtonColor: "#C13333",
              denyButtonColor: "#C13333",
              reverseButtons: true,
            }).then((result) => {
              if (result.isConfirmed) {
                console.log(id_cierre)
                $.ajax({
                  data: { id_cierre },
                  url: url + "/" + "caja/imprimir_cierre",
                  type: "POST",
                  success: function (resultado) {
                    var resultado = JSON.parse(resultado);

                    if (resultado.resultado == 1) {
                      let id_cierre = resultado.id_cierre
                      Swal.fire({
                        icon: "question",
                        title: "¿Desea imprimir el movimiento de caja ? ",
                        showCancelButton: false,
                        denyButtonText: `No imprimir`,
                        showDenyButton: true,
                        confirmButtonText: "Imprimir",
                        confirmButtonColor: "#2AA13D",
                        cancelButtonText: "Cancelar",
                        cancelButtonColor: "#C13333",
                        denyButtonColor: "#C13333",
                        reverseButtons: true,
                      }).then((result) => {
                        if (result.isConfirmed) {
                          $.ajax({
                            data: { id_cierre },
                            url: url + "/" + "caja/imp_movimiento_caja",
                            type: "POST",
                            success: function (resultado) {
                              var resultado = JSON.parse(resultado);

                              if (resultado.resultado == 1) {
                                $("#creacion_cliente_factura_pos").modal("hide");
                                Swal.fire({
                                  title: "Impresión de movimiento de caja correcto",
                                  showDenyButton: true,
                                  showCancelButton: false,
                                  confirmButtonText: "Aceptar",
                                  denyButtonText: `Imprimir reporte de ventas `,
                                  confirmButtonColor: "#2AA13D",
                                  denyButtonColor: "#0d6efd",
                                }).then((result) => {
                                  if (result.isConfirmed) {
                                    Swal.fire({
                                      title: "Cierre de caja finalizado",
                                      icon: "success",
                                      confirmButtonText: "Aceptar",
                                      confirmButtonColor: "#2AA13D",
                                    });
                                  } else if (result.isDenied) {
                                    $.ajax({
                                      data: { id_cierre },
                                      url: url + "/" + "pedidos/reporte_ventas",
                                      type: "POST",
                                      success: function (resultado) {
                                        var resultado = JSON.parse(resultado);
                                        if (resultado.resultado == 1) {
                                          Swal.fire({
                                            icon: "success",
                                            title: "Impresión de reporte de ventas correcto ",
                                            confirmButtonText: "Aceptar",
                                            confirmButtonColor: "#2AA13D",
                                          });
                                        }
                                      },
                                    });
                                  }
                                });
                              }
                            },
                          });
                        } else if (result.isDenied) {
                          Swal.fire({
                            icon: "info",
                            title: "No se imprime el movimiento de caja ",
                            showCancelButton: false,
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#2AA13D",
                          });
                          document.getElementById("efectivo_de_cierre").value = "";
                          document.getElementById("transaccion_cierre").value = "";
                        }
                      });
                    }
                  },
                });
              }
            });
          }

          if (resultado.resultado == 0) {
            Swal.fire({
              icon: "warning",
              title: "Caja no tiene apertura ",
              showCancelButton: true,
              confirmButtonText: "Aceptar",
              confirmButtonColor: "#2AA13D",
              cancelButtonText: "Cancelar",
              cancelButtonColor: "#C13333",
              reverseButtons: true,
            });
          }
        },
      });
    }
  });
} */


  function cierre_caja_1() {
  // Obtener valores de los inputs
  var cierre_efectivo = document.getElementById("efectivo_de_cierre").value;
  var cierre_transaccion = document.getElementById("transaccion_cierre").value;

  // Quitar puntos y comas
  var efectivoFormat = cierre_efectivo.replace(/[.,]/g, "");
  var transaccionFormat = cierre_transaccion.replace(/[.,]/g, "");

  // Evitar NaN cuando los inputs están vacíos
  var efectivoVal = efectivoFormat === "" ? 0 : parseInt(efectivoFormat);
  var transaccionVal = transaccionFormat === "" ? 0 : parseInt(transaccionFormat);

  // Sumar los valores
  var sub_total = efectivoVal + transaccionVal;

  // Función para formatear con punto
  function formatearConPunto(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  // Resultado final con puntos
  var resultado = formatearConPunto(sub_total);

  Swal.fire({
    icon: "question",
    title:
      "!Va a realizar el cierre de caja! con un valor de $" + resultado,
    showCancelButton: true,
    confirmButtonText: "Aceptar",
    confirmButtonColor: "#2AA13D",
    cancelButtonText: "Cancelar",
    cancelButtonColor: "#C13333",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      var url = document.getElementById("url").value;
      var usuario_cierre = document.getElementById("usuario_cierre").value;
      var efectivo_de_cierre = document.getElementById("efectivo_de_cierre").value;
      var transaccion_de_cierre = document.getElementById("transaccion_cierre").value;

      // Quitar puntos y comas otra vez antes de enviar
      var efectivoFormat = efectivo_de_cierre.replace(/[.,]/g, "");
      var transaccionFormat = transaccion_de_cierre.replace(/[.,]/g, "");

      var numero_caja = document.getElementById("numero_caja").value;

      $("#error_apertura_caja").html("");

      $.ajax({
        data: {
          usuario_cierre,
          efectivoFormat,
          transaccionFormat,
          numero_caja
        },
        url: url + "/" + "caja/generar_cierre",
        type: "POST",
        success: function (resultado) {
          var resultado = JSON.parse(resultado);

          if (resultado.resultado == 1) {
            var id_cierre = resultado.id_cierre;

            Swal.fire({
              icon: "question",
              title:
                "El cierre de caja fue éxitoso ¿Desea imprimir el comprobante? ",
              showCancelButton: false,
              denyButtonText: `No imprimir`,
              showDenyButton: true,
              confirmButtonText: "Imprimir",
              confirmButtonColor: "#2AA13D",
              denyButtonColor: "#C13333",
              reverseButtons: true,
            }).then((result) => {
              if (result.isConfirmed) {
                $.ajax({
                  data: { id_cierre },
                  url: url + "/" + "caja/imprimir_cierre",
                  type: "POST",
                  success: function (resultado) {
                    var resultado = JSON.parse(resultado);

                    if (resultado.resultado == 1) {
                      let id_cierre = resultado.id_cierre;

                      Swal.fire({
                        icon: "question",
                        title: "¿Desea imprimir el movimiento de caja ? ",
                        showCancelButton: false,
                        denyButtonText: `No imprimir`,
                        showDenyButton: true,
                        confirmButtonText: "Imprimir",
                        confirmButtonColor: "#2AA13D",
                        denyButtonColor: "#C13333",
                        reverseButtons: true,
                      }).then((result) => {
                        if (result.isConfirmed) {
                          $.ajax({
                            data: { id_cierre },
                            url: url + "/" + "caja/imp_movimiento_caja",
                            type: "POST",
                            success: function (resultado) {
                              var resultado = JSON.parse(resultado);

                              if (resultado.resultado == 1) {
                                $("#creacion_cliente_factura_pos").modal("hide");

                                Swal.fire({
                                  title: "Impresión de movimiento de caja correcto",
                                  showDenyButton: true,
                                  confirmButtonText: "Aceptar",
                                  denyButtonText: `Imprimir reporte de ventas`,
                                  confirmButtonColor: "#2AA13D",
                                  denyButtonColor: "#0d6efd",
                                }).then((result) => {
                                  if (result.isConfirmed) {
                                    Swal.fire({
                                      title: "Cierre de caja finalizado",
                                      icon: "success",
                                      confirmButtonText: "Aceptar",
                                      confirmButtonColor: "#2AA13D",
                                    });
                                  } else if (result.isDenied) {
                                    $.ajax({
                                      data: { id_cierre },
                                      url: url + "/" + "pedidos/reporte_ventas",
                                      type: "POST",
                                      success: function (resultado) {
                                        var resultado = JSON.parse(resultado);
                                        if (resultado.resultado == 1) {
                                          Swal.fire({
                                            icon: "success",
                                            title: "Impresión de reporte de ventas correcto",
                                            confirmButtonText: "Aceptar",
                                            confirmButtonColor: "#2AA13D",
                                          });
                                        }
                                      },
                                    });
                                  }
                                });
                              }
                            },
                          });
                        } else if (result.isDenied) {
                          Swal.fire({
                            icon: "info",
                            title: "No se imprime el movimiento de caja",
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#2AA13D",
                          });

                          document.getElementById("efectivo_de_cierre").value = "";
                          document.getElementById("transaccion_cierre").value = "";
                        }
                      });
                    }
                  },
                });
              }
            });
          }

          if (resultado.resultado == 0) {
            Swal.fire({
              icon: "warning",
              title: "Caja no tiene apertura",
              showCancelButton: true,
              confirmButtonText: "Aceptar",
              confirmButtonColor: "#2AA13D",
              cancelButtonText: "Cancelar",
              cancelButtonColor: "#C13333",
              reverseButtons: true,
            });
          }
        },
      });
    }
  });
}




// ========== VALIDACIÓN ANTES DE CIERRE ==========
async function cierre_caja() {
  try {
    let baseUrl = document.getElementById("url").value;
    let url = `${baseUrl}/edicion_eliminacion_factura_pedido/consultarFe`;

    let response = await fetch(url, {
      method: "GET",
      headers: { "X-Requested-With": "XMLHttpRequest" }
    });

    if (!response.ok) {
      throw new Error("Error en la petición: " + response.status);
    }

    let data = await response.json();
    console.log("Respuesta del servidor:", data);

    if (data.response === "success") {
      cierre_caja_1();
    } else if (data.response === "fail") {
      Swal.fire({
        icon: "warning",
        title: "Hay facturas pendientes por transmitir",
        confirmButtonText: "Entendido",
        customClass: { confirmButton: "btn btn-outline-success" },
        buttonsStyling: false
      });
    }

  } catch (error) {
    console.error("Error en cierre_caja():", error);
    Swal.fire({
      icon: "error",
      title: "Error inesperado ⚠️",
      text: "Ocurrió un problema al intentar cerrar la caja.",
      confirmButtonText: "Ok",
      confirmButtonColor: "#d33"
    });
  }
}

