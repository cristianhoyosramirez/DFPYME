    // Función para bloquear letras y permitir solo números
    function soloNumeros(evt) {
        let charCode = (evt.which) ? evt.which : evt.keyCode;
        // Permitir backspace (8), delete (46) y números 0-9 (48-57)
        if (charCode === 8 || charCode === 46) return true;
        if (charCode < 48 || charCode > 57) return false;
        return true;
    }

    // Función para formatear con separador de miles
    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, ''); // quitar todo lo que no sea número
        if (value) {
            input.value = Number(value).toLocaleString('es-CO');
        } else {
            input.value = '';
        }
    }
