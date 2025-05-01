<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Tipo de Producto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded bg-white dark:bg-gray-800 p-6 shadow">
                <form method="POST" action="{{ route('tipo_productos.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="nombre" value="Nombre del Tipo" />
                        <x-text-input id="nombre" name="nombre" type="text" class="w-full"
                            value="{{ old('nombre') }}" required autofocus />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <div class="mt-4">
                        <x-primary-button>
                            Crear Tipo
                        </x-primary-button>
                        <a href="{{ route('tipo_productos.index') }}" class="ml-4 text-blue-500 hover:text-blue-700">
                            Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->
