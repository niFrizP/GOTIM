<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Lista de Clientes
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <a href="{{ route('clientes.create') }}"
               class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                + Nuevo Cliente
            </a>

            @if(session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded bg-white p-4 shadow">
                <table class="w-full table-auto text-left text-sm">
                    <thead>
                        <tr>
                            <th class="border-b p-2">Nombre</th>
                            <th class="border-b p-2">Apellido</th>
                            <th class="border-b p-2">Correo</th>
                            <th class="border-b p-2">RUT</th>
                            <th class="border-b p-2">Teléfono</th>
                            <th class="border-b p-2">Dirección</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td class="border-b p-2">{{ $cliente->nombre }}</td>
                                <td class="border-b p-2">{{ $cliente->apellido }}</td>
                                <td class="border-b p-2">{{ $cliente->email }}</td>
                                <td class="border-b p-2">{{ $cliente->rut }}</td>
                                <td class="border-b p-2">{{ $cliente->telefono }}</td>
                                <td class="border-b p-2">{{ $cliente->direccion }}</td>
                                <td class="border-b p-2">
                                    <span class="inline-block rounded px-2 py-1 text-xs font-semibold
                                        {{ $cliente->estado ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ $cliente->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="border-b p-2 space-x-2">
                                    <a href="{{ route('clientes.show', $cliente->id_cliente) }}"
                                       class="text-gray-600 hover:underline">Ver</a>
                                    <a href="{{ route('clientes.edit', $cliente->id_cliente) }}"
                                       class="text-blue-500 hover:underline">Editar</a>
                                    <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}"
                                          method="POST" class="inline-block"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>



<!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->

