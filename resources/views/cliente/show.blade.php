<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Detalle del Cliente
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded bg-white p-6 shadow">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="font-semibold">Nombre:</dt>
                        <dd>{{ $cliente->nombre }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Apellido:</dt>
                        <dd>{{ $cliente->apellido }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Correo:</dt>
                        <dd>{{ $cliente->email }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">RUT:</dt>
                        <dd>{{ $cliente->rut }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Teléfono:</dt>
                        <dd>{{ $cliente->telefono }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Dirección:</dt>
                        <dd>{{ $cliente->direccion }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Estado:</dt>
                        <dd>
                            <span
                                class="inline-block rounded px-2 py-1 text-sm font-semibold 
                                {{ $cliente->estado ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ $cliente->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <a href="{{ route('clientes.index') }}" class="text-blue-600 hover:underline">← Volver</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
