/**
 * rutUtils.js
 * Utilidad para formatear, validar y manejar RUT chileno y teléfono.
 * Reutilizable en cualquier formulario.
 */

/**
 * Formatea un RUT chileno a formato 9.999.999-9 o 99.999.999-9
 * @param {string} rut
 * @returns {string}
 */
function formatRut(rut) {
    rut = rut.replace(/[^0-9kK]/g, '').toUpperCase();
    if (rut.length <= 1) return rut;
    let cuerpo = rut.slice(0, -1);
    let dv = rut.slice(-1);
    let cuerpoFormateado = '';
    let i = 0;
    for (let j = cuerpo.length - 1; j >= 0; j--) {
        cuerpoFormateado = cuerpo[j] + cuerpoFormateado;
        i++;
        if (i % 3 === 0 && j !== 0) cuerpoFormateado = '.' + cuerpoFormateado;
    }
    return cuerpoFormateado + '-' + dv;
}

/**
 * Valida el RUT chileno usando algoritmo de módulo 11.
 * @param {string} rut
 * @returns {boolean}
 */
function isRutValido(rut) {
    rut = rut.replace(/\./g, '').replace(/-/g, '').toUpperCase();
    if (rut.length < 2) return false;
    let cuerpo = rut.slice(0, -1);
    let dv = rut.slice(-1);

    let suma = 0, multiplo = 2;
    for (let i = cuerpo.length - 1; i >= 0; i--) {
        suma += parseInt(cuerpo[i]) * multiplo;
        multiplo = multiplo < 7 ? multiplo + 1 : 2;
    }
    let dvEsperado = 11 - (suma % 11);
    dvEsperado = dvEsperado === 11 ? '0' : dvEsperado === 10 ? 'K' : dvEsperado.toString();
    return dvEsperado === dv;
}

/**
 * Valida y formatea teléfono chileno (solo números, máximo 9 dígitos).
 * @param {string} telefono
 * @returns {string}
 */
function formatTelefono(telefono) {
    return telefono.replace(/[^0-9]/g, '').slice(0, 9);
}

/**
 * Componente: Aplica validación y formateo automático a los campos RUT.
 * @param {string} idRut - id del input del RUT
 * @param {string} idFeedback - id del elemento donde mostrar feedback
 * @param {string} idBtn - id del botón de submit (opcional)
 * @param {function} comprobarRutRemoto - función async para verificar existencia en backend (recibe rut formateado)
 * @param {string} caso - 'natural' o 'empresa'
 */
function rutComponent({ idRut, idFeedback, idBtn, comprobarRutRemoto, caso }) {
    const inputRut = document.getElementById(idRut);
    const feedback = document.getElementById(idFeedback);
    const btnGuardar = idBtn ? document.getElementById(idBtn) : null;
    const empresaNoEncontrada = document.getElementById('empresa_no_encontrada');
    const razonSocialEmpresa = document.getElementById('razon_social_empresa');

    if (!inputRut) return;

    inputRut.addEventListener('input', async function () {
        let rawRut = this.value.replace(/[^0-9kK]/g, '').toUpperCase();
        if (rawRut.length > 1) this.value = window.rutUtils.formatRut(rawRut);

        const rut = this.value;
        feedback.textContent = '';
        feedback.className = 'mt-1 text-sm';

        if (empresaNoEncontrada) empresaNoEncontrada.style.display = 'none';
        if (razonSocialEmpresa) razonSocialEmpresa.style.display = 'none';

        // Largo mínimo para un RUT razonable
        if (rawRut.length < 8) {
            if (btnGuardar) btnGuardar.disabled = true;
            return;
        }

        // Valida dígito verificador
        if (!window.rutUtils.isRutValido(rut)) {
            feedback.textContent = '❌ RUT inválido';
            feedback.classList.remove('text-green-600', 'text-gray-500', 'text-blue-600');
            feedback.classList.add('text-red-600');
            if (btnGuardar) btnGuardar.disabled = true;
            return;
        }

        // Si es válido y hay función de comprobación remota
        if (comprobarRutRemoto) {
            feedback.textContent = 'Consultando RUT...';
            feedback.classList.remove('text-green-600', 'text-red-600', 'text-blue-600');
            feedback.classList.add('text-gray-500');
            if (btnGuardar) btnGuardar.disabled = true;

            const result = await comprobarRutRemoto(window.rutUtils.formatRut(rawRut));

            // Lógica según caso (natural o empresa)
            if (caso === 'natural') {
                if (result && result.existe) {
                    feedback.textContent = '❌ Este RUT ya está en uso';
                    feedback.classList.remove('text-gray-500', 'text-green-600', 'text-blue-600');
                    feedback.classList.add('text-red-600');
                    if (btnGuardar) btnGuardar.disabled = true;
                } else {
                    feedback.textContent = '';
                    feedback.classList.remove('text-gray-500', 'text-red-600', 'text-blue-600');
                    if (btnGuardar) btnGuardar.disabled = false;
                }
            } else if (caso === 'empresa') {
                if (result && result.existe) {
                    feedback.textContent = 'Empresa: ' + (result.nom_emp || '[sin nombre]');
                    feedback.classList.remove('text-gray-500', 'text-green-600', 'text-red-600');
                    feedback.classList.add('text-blue-600');
                    if (btnGuardar) btnGuardar.disabled = false;
                    if (razonSocialEmpresa) {
                        razonSocialEmpresa.textContent = result.razon_social || '';
                        razonSocialEmpresa.style.display = 'block';
                    }
                    if (empresaNoEncontrada) empresaNoEncontrada.style.display = 'none';
                } else {
                    feedback.textContent = '✅ RUT disponible';
                    feedback.classList.remove('text-gray-500', 'text-red-600', 'text-blue-600');
                    feedback.classList.add('text-green-600');
                    if (btnGuardar) btnGuardar.disabled = false;
                    if (empresaNoEncontrada) empresaNoEncontrada.style.display = 'block';
                    if (razonSocialEmpresa) razonSocialEmpresa.style.display = 'none';
                }
            }
        }
    });

    inputRut.addEventListener('keypress', function (e) {
        const char = String.fromCharCode(e.which);
        if (!/[0-9Kk]/.test(char)) e.preventDefault();
    });
}

/**
 * Componente: Aplica validación a input de teléfono (solo números, máximo 9)
 * @param {string} idTelefono
 */
function telefonoComponent(idTelefono) {
    const inputTel = document.getElementById(idTelefono);
    if (!inputTel) return;
    inputTel.addEventListener('input', function () {
        this.value = formatTelefono(this.value);
    });
}

// Exportar para uso global
window.rutUtils = {
    formatRut,
    isRutValido,
    rutComponent,
    formatTelefono,
    telefonoComponent
};