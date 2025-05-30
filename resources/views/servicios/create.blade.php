<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Servicio
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded bg-white dark:bg-gray-800 p-6 shadow">
                <form method="POST" action="{{ route('servicios.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Nombre Servicio -->
                        <div class="sm:col-span-2">
                            <x-input-label for="nombre_servicio" value="Nombre del Servicio" />
                            <x-text-input id="nombre_servicio" name="nombre_servicio" type="text" class="w-full"
                                value="{{ old('nombre_servicio') }}" required />
                            <p id="nombre_servicio_msg" class="mt-1 text-sm"></p>
                        </div>
                        <x-input-error :messages="$errors->get('nombre_servicio')" class="mt-1 text-sm text-red-600 dark:text-red-400" />

                        <!-- Descripción -->
                        <div class="sm:col-span-2">
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                rows="4">{{ old('descripcion') }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-primary-button type="submit" id="btnGuardar">Guardar Servicio</x-primary-button>
                        <a href="{{ route('servicios.index') }}"
                            class="ml-4 text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('nombre_servicio');
            const msg = document.getElementById('nombre_servicio_msg');
            const btnGuardar = document.getElementById('btnGuardar');
            let timeout;

            // Función para validar el nombre del servicio
            const validarNombre = async (nombre) => {
                try {
                    const response = await fetch(
                        `/servicios/validar-nombre?nombre=${encodeURIComponent(nombre)}`);
                    const data = await response.json();

                    if (data.disponible) {
                        msg.textContent = '✅ Nombre disponible';
                        msg.className = 'mt-1 text-sm text-green-600';
                        btnGuardar.disabled = false; // Habilitar botón si el nombre es válido
                    } else {
                        msg.textContent = '❌ Nombre ya está en uso';
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
                    btnGuardar.disabled = false; // Si está vacío, permitimos guardar (validará server-side)
                    return;
                }

                timeout = setTimeout(() => {
                    validarNombre(nombre);
                }, 400);
            });
        });
    </script>

</x-app-layout>
