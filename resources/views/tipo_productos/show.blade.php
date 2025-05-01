<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Detalle del Tipo de Producto: {{ $tipo->nombre }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Aquí va la info del tipo de producto --}}
                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-2">Información del Tipo</h3>
                    <p><strong>Nombre:</strong> {{ $tipo->nombre }}</p>
                    <p>
                        <strong>Estado:</strong>
                        <span class="{{ $tipo->estado ? 'text-green-600' : 'text-red-600' }}">
                            {{ $tipo->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>

                    {{-- Botones --}}
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('tipo_productos.edit', $tipo->id) }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Editar
                        </a>

                        @if ($tipo->estado)
                            <form action="{{ route('tipo_productos.desactivar', $tipo->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    Desactivar
                                </button>
                            </form>
                        @else
                            <form action="{{ route('tipo_productos.activar', $tipo->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Activar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Tabla de productos --}}
                <div class="bg-white shadow p-4 rounded">
                    <h3 class="text-xl font-bold mb-4">Productos Asociados</h3>
                    @if ($productos->count())
                        <table class="w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="p-2">Nombre</th>
                                    <th class="p-2">Código</th>
                                    <th class="p-2">Stock</th>
                                    <th class="p-2">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $producto)
                                    <tr class="border-t">
                                        <td class="p-2">{{ $producto->nombre }}</td>
                                        <td class="p-2">{{ $producto->codigo }}</td>
                                        <td class="p-2">{{ $producto->stock }}</td>
                                        <td class="p-2">
                                            <span class="{{ $producto->estado ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $productos->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No hay productos asociados a este tipo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- It is never too late to be what you might have been. - George Eliot -->
