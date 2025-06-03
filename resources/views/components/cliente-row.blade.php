@props(['cliente'])


<tr>
    <!-- Nombre y Apellido juntos -->
    <td class="border-b p-2">{{ $cliente->nombre_cliente }} {{ $cliente->apellido_cliente }}</td>

    <td class="border-b p-2">{{ $cliente->email }}</td>

    <!-- RUT dinámico -->
    <td class="border-b p-2">
        @if ($cliente->tipo_cliente === 'empresa' && $cliente->empresa)
            {{ $cliente->empresa->rut_empresa }}
        @else
            {{ $cliente->rut }}
        @endif
    </td>

    <td class="border-b p-2">{{ $cliente->nro_contacto }}</td>
    <td class="border-b p-2">{{ $cliente->direccion }}</td>
    <td class="border-b p-2">
        <span
            class="inline-block rounded px-2 py-1 text-xs font-semibold
            {{ $cliente->tipo_cliente === 'empresa' ? 'bg-blue-200 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300' : 'bg-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' }}">
            {{ ucfirst($cliente->tipo_cliente) }}
        </span>
    </td>
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
        <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="text-blue-500 hover:underline">Editar</a>
        @if ($cliente->estado === 'activo')
            <button
                onclick="mostrarModal('{{ $cliente->id_cliente }}', '{{ $cliente->nombre_cliente }} {{ $cliente->apellido_cliente }}')"
                class="text-red-500 hover:underline">
                Inhabilitar
            </button>
        @else
            <form action="{{ route('clientes.reactivar', $cliente->id_cliente) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="text-green-500 hover:underline">
                    Reactivar
                </button>
            </form>
        @endif
    </td>
</tr>
