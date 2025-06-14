<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Detalle del Producto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Nombre</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $producto->nombre_producto }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Código</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $producto->codigo }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Categoría</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $producto->categoria->nombre_categoria ?? 'N/A' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Tipo de Producto</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $producto->tipoproducto->nombre ?? 'N/A' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Marca</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $producto->marca ?? 'N/A' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Modelo</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $producto->modelo ?? 'N/A' }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Descripción</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200 whitespace-pre-line">
                            {{ $producto->descripcion ?? 'No hay una descripción del producto' }}
                        </dd>
                    </div>

                    @if ($producto->imagen)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Imagen</dt>
                            <dd class="mt-2">
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen del producto"
                                    class="h-48 rounded-lg object-cover shadow border border-gray-300 dark:border-gray-600 cursor-pointer"
                                    onclick="mostrarImagenModal('{{ asset('storage/' . $producto->imagen) }}')" />
                            </dd>
                        </div>
                    @endif


                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Fecha de Creación</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $producto->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Estado</dt>
                        <dd class="mt-2">
                            <span
                                class="inline-block rounded px-2 py-1 text-xs font-semibold
                                {{ $producto->estado ? 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                {{ $producto->estado ? 'Activo' : 'Inhabilitado' }}
                            </span>
                        </dd>
                    </div>

                </dl>

                <div class="mt-8">
                    <a href="{{ route('productos.index') }}"
                        class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        ← Volver a la lista
                    </a>

                    <a href="{{ route('productos.edit', $producto->id_producto) }}"
                        class="inline-block rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600 ml-2">
                        Editar Producto
                    </a>
                    @if (Auth::user()->rol === 'administrador')
                        {{-- Botón para desactivar/activar producto --}}
                        @if ($producto->estado)
                            <!-- Botón para inhabilitar -->
                            <button onclick="mostrarModal()"
                                class="inline-block rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700 ml-2">
                                Inhabilitar Producto
                            </button>
                        @else
                            <!-- Botón de reactivar -->
                            <form action="{{ route('productos.reactivar', $producto->id_producto) }}" method="POST"
                                class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="inline-block rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700 ml-2">
                                    Reactivar Producto
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="confirmModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">

        <div
            class="transform rounded-lg bg-white dark:bg-gray-800 p-8 shadow-md scale-95 transition-transform duration-300 ease-in-out w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white text-center">
                Confirmar Inhabilitación
            </h3>
            <p id="confirmMessage" class="mt-4 text-center text-gray-600 dark:text-gray-300">
                ¿Estás seguro de que deseas inhabilitar el producto <strong>{{ $producto->nombre_producto }}</strong>?
            </p>
            <div class="mt-6 flex justify-center space-x-4">
                <button onclick="cerrarModal()" class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">
                    Cancelar
                </button>
                <form method="POST" action="{{ route('productos.destroy', $producto->id_producto) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                        Inhabilitar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.querySelector('div').classList.add('scale-100');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function cerrarModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>

    <!-- Modal para ver imagen en grande -->
    <div id="imagenModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center">
        <div class="relative max-w-3xl mx-auto">
            <img id="imagenAmpliada" src="" class="max-h-[80vh] rounded-lg shadow-xl" alt="Imagen ampliada" />
            <button onclick="cerrarImagenModal()"
                class="absolute top-2 right-2 text-white text-2xl font-bold">&times;</button>
        </div>
    </div>
    <script>
        function mostrarImagenModal(src) {
            const modal = document.getElementById('imagenModal');
            const imagen = document.getElementById('imagenAmpliada');
            imagen.src = src;
            modal.classList.remove('hidden');
        }

        function cerrarImagenModal() {
            const modal = document.getElementById('imagenModal');
            modal.classList.add('hidden');
            document.getElementById('imagenAmpliada').src = '';
        }
    </script>


</x-app-layout>