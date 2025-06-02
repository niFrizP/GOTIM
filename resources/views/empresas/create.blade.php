<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Nueva Empresa
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <form action="{{ route('empresas.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre de la empresa -->
                        <div>
                            <x-input-label for="nom_emp" value="Nombre de la empresa" class="dark:text-gray-300" />
                            <x-text-input id="nom_emp" name="nom_emp" type="text" class="w-full"
                                value="{{ old('nom_emp') }}" required />
                            <span id="nom_emp_msg" class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                <x-input-error :messages="$errors->get('nom_emp')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Rut Empresa-->
                        <div>
                            <x-input-label for="rut_empresa" value="RUT Empresa" class="dark:text-gray-300" />
                            <x-text-input id="rut_empresa" name="rut_empresa" type="text" class="w-full"
                                value="{{ old('rut_empresa') }}" required />
                            <x-input-error :messages="$errors->get('rut_empresa')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Telefono -->
                        <div>
                            <x-input-label for="telefono" value="Teléfono" class="dark:text-gray-300" />
                            <x-text-input id="telefono" name="telefono" type="tel" class="w-full"
                                value="{{ old('telefono') }}" required />
                            <x-input-error :messages="$errors->get('telefono')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Razón Social -->
                        <div>
                            <x-input-label for="razon_social" value="Razón Social" class="dark:text-gray-300" />
                            <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                                value="{{ old('razon_social') }}" required />
                            <x-input-error :messages="$errors->get('razon_social')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Giro de la empresa -->
                        <div>
                            <x-input-label for="giro" value="Giro de la empresa" class="dark:text-gray-300" />
                            <x-text-input id="giro" name="giro" type="text" class="w-full"
                                value="{{ old('giro') }}" required />
                            <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600" />
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('nom_emp');
            const msg = document.getElementById('nom_emp_msg');
            const btnGuardar = document.getElementById('btnGuardar');
            let timeout;

            // Función para validar el nombre del servicio
            const validarNombre = async (nombre) => {
                try {
                    const response = await fetch(
                        `/empresas/comprobar-nombre?nombre=${encodeURIComponent(nom_empbre)}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        };
                    );
                    const data = await response.json();

                    if (data.disponible) {
                        msg.textContent = '✅ Nombre de Empresa disponible';
                        msg.className = 'mt-1 text-sm text-green-600';
                        btnGuardar.disabled = false; // Habilitar botón si el nombre es válido
                    } else {
                        msg.textContent = '❌ Empresa ya existe';
                        msg.className = 'mt-1 text-sm text-red-600';
                        btnGuardar.disabled = true; // Deshabilitar botón si el nombre no es válido
                    }
                } catch (error) {
                    msg.textContent = '⚠️ Error al validar';
                    msg.className = 'mt-1 text-sm text-yellow-600';
                    btnGuardar.disabled = true; // Deshabilitar botón en caso de error
                }
            };
            input.addEventListener('input', () => {
                clearTimeout(timeout);
                const nombre = input.value.trim();

                if (nombre.length === 0) {
                    msg.textContent = '';
                    btnGuardar.disabled =
                        false; // Si está vacío, permitimos guardar (validará server-side)
                    return;
                }

                timeout = setTimeout(() => {
                    validarNombre(nombre);
                }, 400);
            });
        });
    </script>
</x-app-layout>



<!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->
