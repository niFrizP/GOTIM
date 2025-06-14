// Este script autocompleta los campos de empresa (nombre, razón social, giro, id_empresa)
// según el RUT ingresado en el input #rut_empresa. Inclúyelo después de tus inputs en el Blade.

document.addEventListener('DOMContentLoaded', function () {
    // Selecciona los elementos por su ID. Asegúrate que los campos tengan estos IDs.
    const rutEmpresaInput = document.getElementById('rut_empresa');
    const nombreEmpresaLabel = document.getElementById('nombre_empresa_label');
    const razonSocialInput = document.getElementById('razon_social');
    const giroInput = document.getElementById('giro');
    const idEmpresaInput = document.getElementById('id_empresa');

    if (!rutEmpresaInput) return;

    rutEmpresaInput.addEventListener('input', function () {
        // Si el input está vacío o muy corto, limpia los campos y no hace petición
        if (this.value.trim().length < 8) {
            limpiarCampos();
            return;
        }

        const rut = limpiarFormatoRut(this.value);

        fetch(`/empresas/comprobar/${rut}`)
            .then(response => response.ok ? response.json() : Promise.reject())
            .then(data => {
                if (data.existe) {
                    nombreEmpresaLabel.textContent = data.nom_emp || '';
                    idEmpresaInput.value = data.id_empresa || '';
                    razonSocialInput.value = data.razon_social || '';
                    razonSocialInput.readOnly = true;
                    razonSocialInput.classList.add('bg-gray-100');
                    giroInput.value = data.giro || '';
                    giroInput.readOnly = false;
                    giroInput.classList.remove('bg-gray-100');
                } else {
                    limpiarCampos();
                }
            })
            .catch(limpiarCampos);
    });

    function limpiarCampos() {
        nombreEmpresaLabel.textContent = '';
        idEmpresaInput.value = '';
        razonSocialInput.value = '';
        razonSocialInput.readOnly = false;
        razonSocialInput.classList.remove('bg-gray-100');
        giroInput.value = '';
        giroInput.readOnly = false;
        giroInput.classList.remove('bg-gray-100');
    }

    // Opcional: función para limpiar puntos y guión del rut
    function limpiarFormatoRut(rut) {
        return rut.replace(/\./g, '').replace(/-/g, '').toUpperCase();
    }
});