<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Lista Clientes') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <a href="{{ route('clientes.create') }}"
                class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"><i
                    class="fa-solid fa-square-plus"></i>
                <span> Nuevo Cliente</span>
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
                            <th class="border-b p-2">Apellido</th>
                            <th class="border-b p-2">Correo</th>
                            <th class="border-b p-2">RUT</th>
                            <th class="border-b p-2">Teléfono</th>
                            <th class="border-b p-2">Estado</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td class="border-b p-2">{{ $cliente->nombre_cliente }}</td>
                                <td class="border-b p-2">{{ $cliente->apellido_cliente }}</td>
                                <td class="border-b p-2">{{ $cliente->email }}</td>
                                <td class="border-b p-2">{{ $cliente->rut }}</td>
                                <td class="border-b p-2">{{ $cliente->nro_contacto }}</td>
                                <td class="border-b p-2">
                                    <span
                                        class="inline-block rounded px-2 py-1 text-xs font-semibold
                                    {{ $cliente->estado === 'activo' ? 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                        {{ $cliente->estado === 'activo' ? 'Activo' : 'Inhabilitado' }}
                                    </span>

                                </td>
                                <td class="border-b p-2 space-x-2">
                                    <a href="{{ route('clientes.show', $cliente->id_cliente) }}"
                                        class="text-gray-600 dark:text-gray-300 hover:underline">Ver</a>
                                    <a href="{{ route('clientes.edit', $cliente->id_cliente) }}"
                                        class="text-blue-500 hover:underline">Editar</a>
                                    @if ($cliente->estado === 'activo')
                                        <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('¿Estás seguro de inhabilitar este cliente?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:underline">Inhabilitar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('clientes.reactivar', $cliente->id_cliente) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="text-green-500 hover:underline">Reactivar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
