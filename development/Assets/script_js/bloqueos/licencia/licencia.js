document.addEventListener("DOMContentLoaded", async function () {
    url = document.getElementById('url').value;
    try {
        // 1. Consultar el ID de licencia desde el backend
        const respuesta = await fetch(url+"/producto/idLicencia");
        const data = await respuesta.json();

        if (data.id_licencia) {
            // 2. Llamar a consultarLicenciaSupabase con el ID obtenido
            const resultado = await consultarLicenciaSupabase(data.id_licencia);

            if (resultado === true) {
                console.log("✅ Licencia válida");
            } else if (resultado === false) {
                console.log("⛔ Licencia suspendida o inactiva");
            } else {
                console.log("⚠️ No se pudo verificar la licencia");
            }
        } else {
            console.log("⚠️ No se pudo obtener el ID de la licencia del backend");
        }
    } catch (error) {
        console.error("❌ Error al consultar el ID de la licencia:", error);
    }
});



async function consultarLicenciaSupabase(idLicencia) {
    // const url = `https://pjhzftkkysmuwjestwsk.supabase.co/rest/v1/estado_licencia?id_cliente=eq.${idLicencia}`;
    const url = `https://pjhzftkkysmuwjestwsk.supabase.co/rest/v1/estado_licencia?id_instalacion=eq.${idLicencia}`;
    const apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBqaHpmdGtreXNtdXdqZXN0d3NrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDU0NTYwMzcsImV4cCI6MjA2MTAzMjAzN30.-ZLlB3hKU-Xm9_FFI29fbfVQNULWQs07QdfirZG0o0I';

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'apikey': apiKey,
                'Authorization': `Bearer ${apiKey}`,
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            console.error('Error en la conexión:', response.status);
            return null;
        }

        const data = await response.json();

        if (data.length > 0 && data[0].estado_licencia) {
            const estado = data[0].estado_licencia;
            const mensaje = data[0].mensaje_licencia;

            // Aquí puedes guardar localmente si es necesario:
            console.log("Estado licencia:", estado);
            console.log("Mensaje licencia:", mensaje);

            // Puedes guardar en localStorage si deseas:
            // localStorage.setItem('estado_licencia', estado);
            // localStorage.setItem('mensaje_licencia', mensaje);

            let url = document.getElementById('url').value


            await fetch(url + '/impresora/actualizarEstadoLicencia', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `estado=${encodeURIComponent(estado)}&mensaje=${encodeURIComponent(mensaje)}`
            });




            return ['Activa', 'En Mora'].includes(estado); // true o false
        }

        return null; // No se obtuvo estado válido
    } catch (error) {
        console.error('Error al consultar Supabase:', error);
        return null;
    }
}