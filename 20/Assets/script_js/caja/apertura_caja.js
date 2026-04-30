const apertura_caja = document.querySelector("#apertura_caja");

// Función que devuelve string con separadores de miles
function formatNumber(n) {
  n = String(n).replace(/\D/g, "");
  if (n === "") return "0";
  return parseInt(n, 10).toLocaleString("es-CO");
}

// Si pierde el foco y está vacío, poner "0"
apertura_caja.addEventListener("blur", (e) => {
  if (e.target.value.trim() === "") {
    e.target.value = "0";
  } else {
    e.target.value = formatNumber(e.target.value);
  }
});

function abrir_caja() {
  var apertura = document.getElementById("apertura_caja").value.trim();

  // si quedó vacío, forzamos a "0"
  if (apertura === "") {
    apertura = "0";
    document.getElementById("apertura_caja").value = "0";
  }

  $("#error_apertura_caja").html("");

  Swal.fire({
    icon: "question",
    title: "¡Va a realizar la apertura de caja!",
    html: "Con un valor de <b>$ " + apertura + "</b>",
    showCancelButton: true,
    confirmButtonText: "Aceptar",
    confirmButtonColor: "#2AA13D",
    cancelButtonText: "Cancelar",
    cancelButtonColor: "#C13333",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      $("#formulario_apertura").submit();
    }
  });
}


function saltar_apertura(e, id) {
  // Obtenemos la tecla pulsada

  e.keyCode ? (k = e.keyCode) : (k = e.which);

  // Si la tecla pulsada es enter (codigo ascii 13)

  if (k == 13) {
    // Si la variable id contiene "submit" enviamos el formulario

    if (id == "submit") {
      document.forms[0].submit();
    } else {
      // nos posicionamos en el siguiente input

      document.getElementById(id).focus();
    }
  }
}





