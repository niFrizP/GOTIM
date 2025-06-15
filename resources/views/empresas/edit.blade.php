{{-- resources/views/empresa/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Editar Empresa
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <form method="POST" action="{{ route('empresas.update', $empresa->id_empresa) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre de Empresa -->
                        <div>
                            <x-input-label for="nom_emp" value="Nombre de Empresa" class="dark:text-gray-300" />
                            <x-text-input id="nom_emp" name="nom_emp" type="text" class="w-full"
                                value="{{ old('nom_emp', $empresa->nom_emp) }}" required />
                            <x-input-error :messages="$errors->get('nom_emp')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- RUT Empresa -->
                        <div>
                            <x-input-label for="rut_empresa" value="RUT Empresa" class="dark:text-gray-300" />
                            <x-text-input id="rut_empresa" name="rut_empresa" type="text" class="w-full"
                                value="{{ old('rut_empresa', $empresa->rut_empresa) }}" maxlength="12"
                                placeholder="Ej: 99.999.999-9" required />
                            <x-input-error :messages="$errors->get('rut_empresa')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <x-input-label for="telefono" value="Teléfono" class="dark:text-gray-300" />
                            <x-text-input id="telefono" name="telefono" type="tel" class="w-full"
                                value="{{ old('telefono', $empresa->telefono) }}" maxlength="9" minlength="9"
                                required />
                            <small class="text-gray-500 dark:text-gray-400">Ingrese el número sin el prefijo +56</small>
                            <x-input-error :messages="$errors->get('telefono')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Razón Social -->
                        <div>
                            <x-input-label for="razon_social" value="Razón Social" class="dark:text-gray-300" />
                            <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                                value="{{ old('razon_social', $empresa->razon_social) }}" required />
                            <x-input-error :messages="$errors->get('razon_social')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Giro -->
                        <div>
                            <x-input-label for="giro" value="Giro" class="dark:text-gray-300" />
                            <x-text-input id="giro" name="giro" type="text" class="w-full"
                                value="{{ old('giro', $empresa->giro) }}" required />
                            <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600" />
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                        <x-primary-button>Actualizar Empresa</x-primary-button>
                        <a href="{{ route('empresas.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validar solo números en teléfono
        const telefonoEmpresa = document.getElementById('telefono');
        if (telefonoEmpresa) {
            telefonoEmpresa.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // Formateo de RUT
        function formatRut(input) {
            let value = input.value.toUpperCase().replace(/[^0-9K]/g, '');
            if (value.length === 9) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
            } else if (value.length === 8) {
                value = value.replace(/^(\d{1})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
            }
            input.value = value;
        }

        document.getElementById('rut_empresa').addEventListener('input', function () {
            formatRut(this);
        });
    </script>
</x-app-layout>