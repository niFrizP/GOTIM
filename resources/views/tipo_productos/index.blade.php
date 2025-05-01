<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Tipo de Productos') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <a href="{{ route('tipo_productos.create') }}"
                class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                <i class="fa-solid fa-square-plus"></i> Crear Tipo de Producto
            </a>
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800 dark:text-green-300 dark:bg-green-900/20">
                    {{ session('success') }}
                </div>
            @endif
            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-4 shadow dark:shadow-md">
                <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead>
                        <tr>
                            <th class="border-b p-2">Nombre</th>
                            <th class="border-b p-2">Estado</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tiposProductos as $tipo)
                            <tr>
                                <td class="border-b p-2">{{ $tipo->nombre }}</td>
                                <td class="border-b p-2">
                                    <span class="{{ $tipo->estado ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $tipo->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="border-b p-2 space-x-2">
                                    <a href="{{ route('tipo_productos.show', $tipo->id) }}"
                                        class="text-gray-600 dark:text-gray-300 hover:underline">Ver</a>
                                    <a href="{{ route('tipo_productos.edit', $tipo->id) }}"
                                        class="text-blue-500 hover:underline">Editar</a>

                                    @if ($tipo->estado)
                                        <form method="POST"
                                            action="{{ route('tipo_productos.desactivar', $tipo->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-red-500 hover:underline">Desactivar</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('tipo_productos.activar', $tipo->id) }}"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-green-500 hover:underline">Activar</button>
                                        </form>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $tiposProductos->links() }} <!-- Esto generará los enlaces de paginación -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
