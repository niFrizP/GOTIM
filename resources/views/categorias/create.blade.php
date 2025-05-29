<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Nueva Categoría
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <form action="{{ route('categorias.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre de la categoría -->
                        <div>
                            <x-input-label for="nombre_categoria" value="Nombre de la Categoría"
                                class="dark:text-gray-300" />
                            <x-text-input id="nombre_categoria" name="nombre_categoria" type="text" class="w-full"
                                value="{{ old('nombre_categoria') }}" required />
                            <span id="nombre_categoria_msg" class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                <x-input-error :messages="$errors->get('nombre_categoria')" class="mt-1 text-sm text-red-600" />
                        </div>


                        <!-- Descripción (ocupa ambas columnas) -->
                        <div class="sm:col-span-2">
                            <x-input-label for="descripcion" value="Descripción" class="dark:text-gray-300" />
                            <textarea id="descripcion" name="descripcion" rows="4"
                                class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">{{ old('descripcion') }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-1 text-sm text-red-600" />
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                        <x-primary-button type="submit" id="btnGuardar">Crear Categoría</x-primary-button>
                        <a href="{{ route('categorias.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('nombre_categoria');
            const msg = document.getElementById('nombre_categoria_msg');
            const btnGuardar = document.getElementById('btnGuardar');
            let timeout;

            // Función para validar el nombre del servicio
            const validarNombre = async (nombre) => {
                try {
                    const response = await fetch(
                        `/categorias/validar-nombre?nombre=${encodeURIComponent(nombre)}`);
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


<!-- Una vida no examinada no vale la pena ser vivida. - Sócrates -->
