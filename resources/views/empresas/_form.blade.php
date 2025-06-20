<div id="empresa-alert-msg" class="hidden mb-4 p-3 rounded font-semibold"></div>
<div class="modal-body-scroll max-h-[70vh] overflow-y-auto px-4 py-2">
    <form id="formCrearEmpresa" method="POST" action="{{ route('empresas.store') }}" novalidate>
        @csrf

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Nombre de la empresa -->
            <div>
                <x-input-label for="nom_emp" value="Nombre de la empresa *" class="dark:text-gray-300" />
                <x-text-input id="nom_emp" name="nom_emp" type="text" class="w-full" value="{{ old('nom_emp') }}"
                    required maxlength="255" placeholder="Ej: Mi Empresa Ltda." />
                <x-input-error :messages="$errors->get('nom_emp')"
                    class="mt-1 text-sm text-red-600 dark:text-red-400" />
            </div>

            <!-- RUT Empresa -->
            <div class="relative">
                <x-input-label for="rut" value="RUT *" />
                <x-text-input id="rut" name="rut_empresa" type="text" class="w-full pr-10" maxlength="12"
                    placeholder="Ej: 12.345.678-9" value="{{ old('rut') }}" />
                <div id="rut_icon" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"></div>
                <p id="rut_feedback" class="mt-1 text-sm"></p>
            </div>

            <!-- Teléfono -->
            <div>
                <x-input-label for="telefono" value="Teléfono" class="dark:text-gray-300" />
                <x-text-input id="telefono" name="telefono" type="tel" class="w-full" maxlength="9" minlength="9"
                    value="{{ old('telefono') }}" placeholder="Ej: 912345678" />
                <small class="text-gray-500 dark:text-gray-400">Ingrese el número sin el prefijo +56</small>
                <x-input-error :messages="$errors->get('telefono')"
                    class="mt-1 text-sm text-red-600 dark:text-red-400" />
            </div>

            <!-- Razón Social -->
            <div>
                <x-input-label for="razon_social" value="Razón Social" class="dark:text-gray-300" />
                <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                    value="{{ old('razon_social') }}" maxlength="255" placeholder="Ej: Mi Empresa Sociedad Limitada" />
                <x-input-error :messages="$errors->get('razon_social')"
                    class="mt-1 text-sm text-red-600 dark:text-red-400" />
            </div>

            <!-- Giro -->
            <div>
                <x-input-label for="giro" value="Giro de la empresa" class="dark:text-gray-300" />
                <x-text-input id="giro" name="giro" type="text" class="w-full" value="{{ old('giro') }}" maxlength="255"
                    placeholder="Ej: Servicios de tecnología" />
                <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
            </div>
        </div>

        <!-- Botones -->
        <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
            <x-primary-button type="submit" id="btnGuardar">Crear Empresa</x-primary-button>
            <x-secondary-button type="button" id="cerrarModalEmpresa">Cancelar</x-secondary-button>
        </div>
    </form>
</div>
<script>
    function limpiarRut(rut) {
        return rut.replace(/[^0-9kK]/g, '').toUpperCase();
    }
    function formatearRut(rut) {
        rut = limpiarRut(rut);
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
    function validarRut(rutCompleto) {
        rutCompleto = limpiarRut(rutCompleto);
        if (rutCompleto.length < 8) return false; // mínimo 7 + dv

        let cuerpo = rutCompleto.slice(0, -1);
        let dv = rutCompleto.slice(-1).toUpperCase();

        if (!/^\d+$/.test(cuerpo) || !/[0-9K]/.test(dv)) return false;

        let suma = 0, multiplo = 2;
        for (let i = cuerpo.length - 1; i >= 0; i--) {
            suma += parseInt(cuerpo[i]) * multiplo;
            multiplo = multiplo < 7 ? multiplo + 1 : 2;
        }
        let dvEsperado = 11 - (suma % 11);
        let dvCalc = dvEsperado === 11 ? '0' : dvEsperado === 10 ? 'K' : dvEsperado.toString();

        return dvCalc === dv;
    }

    function cerrarModalEmpresa() {
        $('#modalCrearEmpresa').addClass('hidden');
        $('#contenidoModalEmpresa').empty();
    }

    function initRutEmpresaValidation() {
        const rutInput = document.getElementById('rut');
        const feedback = document.getElementById('rut_feedback');
        const icon = document.getElementById('rut_icon');
        const alert = document.getElementById('empresa-alert-msg');
        const form = document.getElementById('formCrearEmpresa');

        rutInput.addEventListener('input', () => {
            let val = limpiarRut(rutInput.value);
            rutInput.value = formatearRut(val);
            // Opcional: feedback inmediato
            if (rutInput.value.length > 0) {
                if (validarRut(rutInput.value)) {
                    icon.innerHTML = `<svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>`;
                    feedback.textContent = "RUT válido.";
                    feedback.className = "text-green-600 text-sm";
                } else {
                    icon.innerHTML = `<svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`;
                    feedback.textContent = "RUT inválido.";
                    feedback.className = "text-red-600 text-sm";
                }
            } else {
                icon.innerHTML = '';
                feedback.textContent = '';
            }
        });

        rutInput.addEventListener('blur', () => {
            const rut = rutInput.value;
            if (validarRut(rut)) {
                icon.innerHTML = `<svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>`;
                feedback.textContent = "RUT válido.";
                feedback.className = "text-green-600 text-sm";
            } else {
                icon.innerHTML = `<svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`;
                feedback.textContent = "RUT inválido.";
                feedback.className = "text-red-600 text-sm";
            }
        });

        form.addEventListener('submit', (e) => {
            const rutVal = rutInput.value;
            alert.classList.add('hidden'); // Ocultar mensaje anterior

            if (!validarRut(rutVal)) {
                e.preventDefault();
                alert.textContent = "❌ Error: El RUT ingresado no es válido.";
                alert.className = "mb-4 p-3 rounded font-semibold bg-red-100 text-red-700";
                alert.classList.remove('hidden');
                rutInput.focus();
            }
        });
    }
    function initTelefonoEmpresaValidation() {
        const telefonoInput = document.getElementById('telefono');
        const form = document.getElementById('formCrearEmpresa');
        const alert = document.getElementById('empresa-alert-msg');

        // Solo permite números en tiempo real
        telefonoInput.addEventListener('input', function (e) {
            // Quitar todo lo que no sea dígito
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Previene pegar letras
        telefonoInput.addEventListener('paste', function (e) {
            let paste = (e.clipboardData || window.clipboardData).getData('text');
            if (/[^0-9]/.test(paste)) {
                e.preventDefault();
            }
        });

        // Validación al enviar formulario
        form.addEventListener('submit', function (e) {
            alert.classList.add('hidden');
            const telefono = telefonoInput.value.trim();

            if (telefono && (!/^\d{9}$/.test(telefono))) {
                e.preventDefault();
                alert.textContent = "❌ Error: El teléfono debe contener exactamente 9 dígitos numéricos (sin +56).";
                alert.className = "mb-4 p-3 rounded font-semibold bg-red-100 text-red-700";
                alert.classList.remove('hidden');
                telefonoInput.focus();
            }
        });
    }

    // Ejecutar ambas validaciones al cargar el modal
    function initRutValidation() {
        initRutEmpresaValidation();
        initTelefonoEmpresaValidation();
    }

    // Al abrir el modal o cargar el DOM, siempre ejecuta ambas validaciones
    window.initRutValidation = initRutValidation;

    document.addEventListener('DOMContentLoaded', initRutValidation);

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initRutValidation, 1);
    }
</script>