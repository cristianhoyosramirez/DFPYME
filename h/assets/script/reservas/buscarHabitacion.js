async function buscarHabitacion(valor) {
    try {
        //if (!valor.trim()) return;

        //const response = await fetch('/ruta-a-tu-api', {
        const response = await fetch(`${BASE_URL}/reservas/busquedaPorHabitacion`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                busqueda: valor
            })
        });

        const data = await response.json();

        document.getElementById('reservasHabitaciones').innerHTML = data.reservas;

        // Aquí actualizas tu lista o tabla
        // ejemplo:
        // renderizarResultados(data);

    } catch (error) {
        console.error('Error en la búsqueda:', error);
    }
}