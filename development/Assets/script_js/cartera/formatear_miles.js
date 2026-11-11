function formatearMiles(input) {

    // Eliminar puntos y caracteres no numéricos
    let valor = input.value.replace(/\./g, '').replace(/\D/g, '');

    // Evitar vacío
    if (valor === '') {
        valor = 0;
    }

    // Formatear número
    input.value = parseInt(valor).toLocaleString('es-CO');

}