<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Listado de Empresas
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <a href="{{ route('empresas.create') }}"
                class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                <i class="fa-solid fa-square-plus"></i> Nueva Empresa
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
                            <th class="border-b p-2">RUT</th>
                            <th class="border-b p-2">Giro</th>
                            <th class="border-b p-2">Estado</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empresas as $empresa)
                            <tr>
                                <td class="border-b p-2">{{ $empresa->nom_emp }}</td>
                                <td class="border-b p-2">{{ $empresa->rut_empresa }}</td>
                                <td class="border-b p-2">{{ $empresa->giro }}</td>
                                <td class="border-b p-2">
                                    @if ($empresa->estado === 'activo')
                                        <span
                                            class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/20 dark:text-green-300">
                                            Activo
                                        </span>
                                    @else
                                        <span
                                            class="inline-block rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                            Inhabilitado
                                        </span>
                                    @endif
                                </td>

                                <td class="border-b p-2">
                                    <a href="{{ route('empresas.show', $empresa->id_empresa) }}"
                                        class="text-gray-600 hover:underline dark:text-gray-300">
                                        Ver
                                    </a>
                                    <a href="{{ route('empresas.edit', $empresa->id_empresa) }}"
                                        class="text-blue-500 hover:underline">
                                        Editar
                                    </a>
                                    @if (Auth::user()->rol === 'administrador')
                                        @if ($empresa->estado === 'activo')
                                            <form action="{{ route('empresas.destroy', $empresa->id_empresa) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:underline">Inhabilitar</button>
                                            </form>
                                        @else
                                            <form action="{{ route('empresas.reactivar', $empresa->id_empresa) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-500 hover:underline">Reactivar</button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
</x-app-layout>
<!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->