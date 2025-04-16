<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Lista de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <a href="{{ route('users.create') }}"
                class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"><i
                    class="fa-solid fa-square-plus"></i>
                <span> Nuevo Usuario</span>
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
                            <th class="border-b p-2">Rol</th>
                            <th class="border-b p-2">Estado</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="border-b p-2">{{ $user->nombre }}</td>
                                <td class="border-b p-2">{{ $user->apellido }}</td>
                                <td class="border-b p-2">{{ $user->email }}</td>
                                <td class="border-b p-2 capitalize">{{ $user->rol }}</td>
                                <td class="border-b p-2">
                                    <span class="inline-block rounded px-2 py-1 text-xs font-semibold
                                        {{ $user->estado ? 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                        {{ $user->estado ? 'Activo' : 'Inhabilitado' }}
                                    </span>
                                </td>
                                <td class="border-b p-2 space-x-2">
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="text-gray-600 dark:text-gray-300 hover:underline">Ver</a>
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="text-blue-500 hover:underline">Editar</a>
                                    @if ($user->estado)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block"
                                            onsubmit="return confirm('¿Estás seguro de inhabilitar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Inhabilitar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('users.reactivar', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-green-500 hover:underline">Reactivar</button>
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
