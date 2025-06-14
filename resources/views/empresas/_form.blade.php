<div id="empresa-success-msg" class="hidden mb-4 p-3 rounded bg-green-100 text-green-700"></div>
<form id="formCrearEmpresa" method="POST" action="{{ route('empresas.store') }}" novalidate>
    @csrf

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <!-- Nombre de la empresa -->
        <div>
            <x-input-label for="nom_emp" value="Nombre de la empresa *" class="dark:text-gray-300" />
            <x-text-input id="nom_emp" name="nom_emp" type="text" class="w-full" value="{{ old('nom_emp') }}"
                required maxlength="255" placeholder="Ej: Mi Empresa Ltda." />
            <x-input-error :messages="$errors->get('nom_emp')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- RUT Empresa-->
        <div>
            <x-input-label for="rut_empresa" value="RUT Empresa *" class="dark:text-gray-300" />
            <x-text-input id="rut_empresa" name="rut_empresa" type="text" class="w-full"
                value="{{ old('rut_empresa') }}" maxlength="12" placeholder="Ej: 99.999.999-9" required />
            <p id="rut_empresa_feedback" class="mt-1 text-sm"></p>
            <x-input-error :messages="$errors->get('rut_empresa')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Teléfono -->
        <div>
            <x-input-label for="telefono" value="Teléfono" class="dark:text-gray-300" />
            <x-text-input id="telefono" name="telefono" type="tel" class="w-full" maxlength="9" minlength="9"
                value="{{ old('telefono') }}" placeholder="Ej: 912345678" />
            <small class="text-gray-500 dark:text-gray-400">Ingrese el número sin el prefijo +56</small>
            <x-input-error :messages="$errors->get('telefono')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Razón Social -->
        <div>
            <x-input-label for="razon_social" value="Razón Social" class="dark:text-gray-300" />
            <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                value="{{ old('razon_social') }}" maxlength="255" placeholder="Ej: Mi Empresa Sociedad Limitada" />
            <x-input-error :messages="$errors->get('razon_social')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Giro de la empresa -->
        <div>
            <x-input-label for="giro" value="Giro de la empresa" class="dark:text-gray-300" />
            <x-text-input id="giro" name="giro" type="text" class="w-full" value="{{ old('giro') }}"
                maxlength="255" placeholder="Ej: Servicios de tecnología" />
            <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>
    </div>

    <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
        <x-primary-button type="submit" id="btnGuardar">Crear Empresa</x-primary-button>
        <x-secondary-button href="{{ route('empresas.index') }}"
            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
            Cancelar
        </x-secondary-button>
    </div>
</form>

@push('scripts')
    <script>
        // Llama a esta función después de insertar el popup en el DOM
        function initRutEmpresaValidation() {
            const $rut = document.getElementById('rut_empresa');
            const $feedback = document.getElementById('rut_empresa_feedback');
            const $btnGuardar = document.getElementById('btnGuardar');
            if (!$rut) return;

            function cleanRut(rut) {
                return rut.replace(/[^0-9kK]/g, '').toUpperCase();
            }

            function formatRut(rut) {
                rut = cleanRut(rut);
                if (rut.length < 2) return rut;
                let cuerpo = rut.slice(0, -1);
                let dv = rut.slice(-1);
                cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                return cuerpo + '-' + dv;
            }

            function dvRut(cuerpo) {
                let suma = 0,
                    multiplo = 2;
                for (let i = cuerpo.length - 1; i >= 0; i--) {
                    suma += parseInt(cuerpo.charAt(i)) * multiplo;
                    multiplo = multiplo < 7 ? multiplo + 1 : 2;
                }
                let dv = 11 - (suma % 11);
                if (dv == 11) return '0';
                if (dv == 10) return 'K';
                return dv.toString();
            }

            function validarRut(rut) {
                rut = cleanRut(rut);
                if (rut.length < 2) return false;
                let cuerpo = rut.slice(0, -1);
                let dv = rut.slice(-1).toUpperCase();
                if (!/^\d+$/.test(cuerpo)) return false;
                return dvRut(cuerpo) === dv;
            }

            function mostrarFeedback(mensaje, ok = false) {
                if ($feedback) {
                    $feedback.textContent = mensaje;
                    $feedback.style.color = ok ? 'green' : 'red';
                }
                if ($btnGuardar) $btnGuardar.disabled = !ok;
            }

            $rut.addEventListener('input', function() {
                let valor = cleanRut($rut.value);
                if (!valor) {
                    mostrarFeedback('');
                    if ($btnGuardar) $btnGuardar.disabled = true;
                    return;
                }
                if (valor.length < 2) {
                    mostrarFeedback('Ingresa al menos 2 dígitos');
                    if ($btnGuardar) $btnGuardar.disabled = true;
                    return;
                }
                if (!validarRut(valor)) {
                    mostrarFeedback('RUT inválido');
                    $rut.value = formatRut(valor);
                    return;
                }
                mostrarFeedback('RUT válido', true);
                $rut.value = formatRut(valor);
            });

            $rut.addEventListener('blur', function() {
                let valor = cleanRut($rut.value);
                $rut.value = formatRut(valor);
            });

            // Solo números para teléfono empresa
            let telefono = document.getElementById('telefono');
            if (telefono) {
                telefono.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        }

        // Si cargas el popup dinámicamente, llama a esta función después de insertar el HTML.
        // Por ejemplo:
        // $('#contenidoModalEmpresa').html(data);
        // initRutEmpresaValidation();

        document.addEventListener('DOMContentLoaded', function() {
            // Si el popup ya está en el DOM al cargar, inicializa directo
            initRutEmpresaValidation();
        });
    </script>
@endpush
