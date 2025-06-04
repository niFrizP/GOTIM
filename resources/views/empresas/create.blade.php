<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Nueva Empresa
        </h2>
    </x-slot>
    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 p-4 text-red-800 dark:bg-red-900/20 dark:text-red-300">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <form action="{{ route('empresas.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre de la empresa -->
                        <div>
                            <x-input-label for="nom_emp" value="Nombre de la empresa *" class="dark:text-gray-300" />
                            <x-text-input id="nom_emp" name="nom_emp" type="text" class="w-full"
                                value="{{ old('nom_emp') }}" required />
                            <x-input-error :messages="$errors->get('nom_emp')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- RUT Empresa-->
                        <div>
                            <x-input-label for="rut_empresa" value="RUT Empresa *" class="dark:text-gray-300" />
                            <x-text-input id="rut_empresa" name="rut_empresa" type="text" class="w-full"
                                value="{{ old('rut_empresa') }}" maxlength="12" placeholder="Ej: 99.999.999-9"
                                required />
                            {{-- Aquí mostraremos el mensaje de validación de RUT --}}
                            <p id="rut_empresa_feedback" class="mt-1 text-sm"></p>
                            <x-input-error :messages="$errors->get('rut_empresa')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <x-input-label for="telefono" value="Teléfono *" class="dark:text-gray-300" />
                            <x-text-input id="telefono" name="telefono" type="tel" class="w-full" maxlength="9"
                                minlength="9" value="{{ old('telefono') }}" required />
                            <small class="text-gray-500 dark:text-gray-400">Ingrese el número sin el prefijo +56</small>
                            <x-input-error :messages="$errors->get('telefono')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Razón Social -->
                        <div>
                            <x-input-label for="razon_social" value="Razón Social *" class="dark:text-gray-300" />
                            <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                                value="{{ old('razon_social') }}" required />
                            <x-input-error :messages="$errors->get('razon_social')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Giro de la empresa -->
                        <div>
                            <x-input-label for="giro" value="Giro de la empresa *" class="dark:text-gray-300" />
                            <x-text-input id="giro" name="giro" type="text" class="w-full" value="{{ old('giro') }}"
                                required />
                            <x-input-error :messages="$errors->get('giro')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    <br>
                    <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                        <x-primary-button type="submit" id="btnGuardar">Crear Empresa</x-primary-button>
                        <x-secondary-button href="{{ route('categorias.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                            Cancelar
                        </x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Formateo y validación de RUT --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const inputRut = document.getElementById('rut_empresa');
                const feedback = document.getElementById('rut_empresa_feedback');
                const btnGuardar = document.getElementById('btnGuardar');

                // Función para formatear RUT en formato 99.999.999-9 o 9.999.999-9
                function formatRut(input) {
                    let value = input.value.toUpperCase().replace(/[^0-9K]/g, '');
                    // Si tiene 9 dígitos (incluyendo dígito verificador)
                    if (value.length === 9) {
                        value = value.replace(/^(\d{2})(\d{3})(\d{3})([\dK])$/, '$1.$2.$3-$4');
                    }
                    // Si tiene 8 dígitos (incluyendo dígito verificador)
                    else if (value.length === 8) {
                        value = value.replace(/^(\d)(\d{3})(\d{3})([\dK])$/, '$1.$2.$3-$4');
                    }
                    input.value = value;
                }

                // Validar formato base del RUT
                function isRutFormatoValido(rut) {
                    // Regex que acepta 1 o 2 dígitos punto 3 dígitos punto 3 dígitos guión dígito/K
                    return /^\d{1,2}\.\d{3}\.\d{3}-[\dK]$/.test(rut);
                }

                // Chequear existencia de RUT en DB vía AJAX
                async function comprobarRut(rut) {
                    try {
                        const response = await fetch(`/empresas/comprobar-rut/${rut}`);
                        if (!response.ok) throw new Error('Error en servidor');
                        const data = await response.json();
                        return data; // { existe: true/false, empresa: {...} }
                    } catch (err) {
                        console.error('Error al comprobar RUT:', err);
                        return { existe: false };
                    }
                }

                inputRut.addEventListener('input', async function () {
                    formatRut(this);
                    const rut = this.value;
                    feedback.textContent = '';
                    feedback.className = 'mt-1 text-sm';

                    // Si el largo coincide con un RUT debidamente formateado, consultamos
                    if (isRutFormatoValido(rut)) {
                        feedback.textContent = 'Consultando RUT...';
                        feedback.classList.add('text-gray-500');
                        btnGuardar.disabled = true;

                        const result = await comprobarRut(rut);
                        if (result.existe) {
                            // Si ya existe: mostramos mensaje y deshabilitamos botón
                            feedback.textContent = '❌ Este RUT ya está registrado (' + result.empresa.nom_emp + ')';
                            feedback.classList.remove('text-gray-500');
                            feedback.classList.add('text-red-600');
                            btnGuardar.disabled = true;
                        } else {
                            // RUT válido y no existe: habilitamos botón y mostramos OK
                            feedback.textContent = '✅ RUT disponible';
                            feedback.classList.remove('text-gray-500');
                            feedback.classList.add('text-green-600');
                            btnGuardar.disabled = false;
                        }
                    } else {
                        // Si no está en formato correcto
                        feedback.textContent = '⚠️ Formato de RUT inválido';
                        feedback.classList.remove('text-green-600', 'text-red-600');
                        feedback.classList.add('text-yellow-600');
                        btnGuardar.disabled = true;
                    }
                });

                // Validar solo números/K en RUT mientras escribe
                inputRut.addEventListener('keypress', function (e) {
                    const char = String.fromCharCode(e.which);
                    if (!/[0-9Kk]/.test(char)) {
                        e.preventDefault();
                    }
                });

                // Validar solo números en teléfono
                const telefonoEmpresa = document.getElementById('telefono');
                if (telefonoEmpresa) {
                    telefonoEmpresa.addEventListener('input', function () {
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>