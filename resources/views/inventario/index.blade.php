<?php

// index.blade.php para inventario
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Lista de Inventario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="flex flex-wrap items-center justify-between mb-4">
                <a href="{{ route('inventario.create') }}"
                    class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                    <i class="fa-solid fa-plus"></i> Nuevo Registro
                </a>

                <a href="{{ route('inventario.historial') }}"
                    class="inline-block rounded bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">
                    <i class="fa-solid fa-clock-rotate-left"></i> Ver Historial
                </a>
            </div>

            @if (session('success'))
            <div class="mb-4 rounded bg-green-100 p-4 text-green-800 dark:text-green-300 dark:bg-green-900/20">
                {{ session('success') }}
            </div>
            @endif

            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-4 shadow dark:shadow-md">
                <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead>
                        <tr>
                            <th class="border-b p-2">Producto</th>
                            <th class="border-b p-2">Cantidad</th>
                            <th class="border-b p-2">Ingreso</th>
                            <th class="border-b p-2">Salida</th>
                            <th class="border-b p-2">Estado</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventarios as $inv)
                        <tr>
                            <td class="border-b p-2">{{ $inv->producto->descripcion ?? 'N/A' }}</td>
                            <td class="border-b p-2">{{ $inv->cantidad }}</td>
                            <td class="border-b p-2">{{ $inv->fecha_ingreso }}</td>
                            <td class="border-b p-2">{{ $inv->fecha_salida ?? '-' }}</td>
                            <td class="border-b p-2">
                                <span class="inline-block rounded px-2 py-1 text-xs font-semibold {{ $inv->estado === 'activo' ? 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                    {{ ucfirst($inv->estado) }}
                                </span>
                            </td>
                            <td class="border-b p-2 space-x-2">
                                <a href="{{ route('inventario.ver', $inv->id_inventario) }}" class="text-gray-600 hover:underline dark:text-gray-300">Ver</a>
                                <a href="{{ route('inventario.editar', $inv->id_inventario) }}" class="text-blue-500 hover:underline">Editar</a>
                                @if ($inv->estado === 'activo')
                                <a href="{{ route('inventario.desactivar', $inv->id_inventario) }}" class="text-red-500 hover:underline">Desactivar</a>
                                @else
                                <a href="{{ route('inventario.reactivar', $inv->id_inventario) }}" class="text-green-500 hover:underline">Reactivar</a>
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