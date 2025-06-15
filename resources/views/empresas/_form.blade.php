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
            <x-input-label for="rut" value="RUT *" />
            <x-text-input id="rut" name="rut" type="text" class="w-full" data-rut maxlength="12" placeholder="Ej: 12.345.678-9" value="{{ old('rut') }}"/>
            <p id="rut_feedback" class="mt-1 text-sm"></p>
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
        // Utilidad para manejar RUTs
        const RUTUtils = {
            clean: rut => (rut || '').replace(/[^0-9kK]/g, '').toUpperCase(),
            format: rut => {
                rut = RUTUtils.clean(rut);
                if (rut.length < 2) return rut;
                const cuerpo = rut.slice(0, -1);
                const dv = rut.slice(-1);
                return cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '-' + dv;
            },
            calculateDV: cuerpo => {
                let suma = 0, multiplo = 2;
                for (let i = cuerpo.length - 1; i >= 0; i--) {
                    suma += parseInt(cuerpo.charAt(i)) * multiplo;
                    multiplo = multiplo < 7 ? multiplo + 1 : 2;
                }
                const dv = 11 - (suma % 11);
                if (dv === 11) return '0';
                if (dv === 10) return 'K';
                return dv.toString();
            },
            validate: rut => {
                rut = RUTUtils.clean(rut);
                if (rut.length < 2) return false;
                const cuerpo = rut.slice(0, -1);
                const dv = rut.slice(-1).toUpperCase();
                return /^\d+$/.test(cuerpo) && RUTUtils.calculateDV(cuerpo) === dv;
            }
        };

        class EmpresaForm {
            constructor() {
                this.form = document.getElementById('formCrearEmpresa');
                this.rutInput = document.getElementById('rut');
                this.rutFeedback = document.getElementById('rut_feedback');
                this.telefonoInput = document.getElementById('telefono');
                this.btnGuardar = document.getElementById('btnGuardar');
                this.rutValido = false;
                this.init();
            }
            init() {
                if (this.rutInput) this.initRutValidation();
                if (this.telefonoInput) this.initTelefonoValidation();
                if (this.form) this.initFormValidation();
            }
            initRutValidation() {
                this.rutInput.addEventListener('input', () => this.handleRutInput());
                this.rutInput.addEventListener('blur', () => this.handleRutBlur());
            }
            initTelefonoValidation() {
                this.telefonoInput.addEventListener('input', e => {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                });
            }
            handleRutInput() {
                const valor = RUTUtils.clean(this.rutInput.value);
                if (!valor) {
                    this.updateFeedback('', null);
                    this.rutValido = false;
                    this.toggleBtnGuardar();
                    return;
                }
                if (valor.length < 2) {
                    this.updateFeedback('Ingresa al menos 2 dígitos', false);
                    this.rutValido = false;
                    this.toggleBtnGuardar();
                    return;
                }
                if (!RUTUtils.validate(valor)) {
                    this.updateFeedback('RUT inválido', false);
                    this.rutValido = false;
                } else {
                    this.updateFeedback('RUT válido', true);
                    this.rutValido = true;
                }
                this.toggleBtnGuardar();
                this.rutInput.value = RUTUtils.format(valor);
            }
            handleRutBlur() {
                const valor = RUTUtils.clean(this.rutInput.value);
                this.rutInput.value = RUTUtils.format(valor);
            }
            updateFeedback(mensaje, isValid) {
                if (this.rutFeedback) {
                    this.rutFeedback.textContent = mensaje;
                    if (isValid === null) {
                        this.rutFeedback.className = 'mt-1 text-sm';
                    } else {
                        this.rutFeedback.className = `mt-1 text-sm ${isValid ? 'text-green-600' : 'text-red-600'}`;
                    }
                }
            }
            toggleBtnGuardar() {
                if (this.btnGuardar) {
                    this.btnGuardar.disabled = !this.rutValido;
                }
            }
            initFormValidation() {
                this.form.addEventListener('submit', e => {
                    if (!this.rutValido) {
                        e.preventDefault();
                        this.updateFeedback('Debe ingresar un RUT válido', false);
                        this.rutInput.focus();
                    }
                });
            }
        }
        document.addEventListener('DOMContentLoaded', () => new EmpresaForm());
    </script>
@endpush
