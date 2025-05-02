<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Editar Tipo de Producto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 shadow rounded">
                <form method="POST" action="{{ route('tipo_productos.update', $tipo->tipo_producto_id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="nombre" value="Nombre del Tipo" />
                        <x-text-input id="nombre" name="nombre" type="text" class="w-full"
                            value="{{ old('nombre', $tipo->nombre) }}" required />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <div class="mt-4">
                        <x-primary-button>
                            Actualizar
                        </x-primary-button>
                        <a href="{{ route('tipo_productos.index') }}" class="ml-4 text-blue-500 hover:text-blue-700">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Do what you can, with what you have, where you are. - Theodore Roosevelt -->
