<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Usuario conectado -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ Auth::user()->nombre }} {{ Auth::user()->apellido }} {{ __(' está conectado!') }}
                </div>
            </div>

            <!-- Tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $cards = [
                        [
                            'title' => 'Clientes',
                            'value' => $totalCliente,
                            'bg' => 'bg-gray-600',
                            'icon' => 'users',
                        ],
                        [
                            'title' => 'Órdenes Totales',
                            'value' => $totalOrden,
                            'bg' => 'bg-red-600',
                            'icon' => 'clipboard-list',
                        ],
                        [
                            'title' => 'Órdenes Completadas',
                            'value' => $completedOrden,
                            'bg' => 'bg-indigo-600',
                            'icon' => 'check-circle',
                        ],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div class="{{ $card['bg'] }} text-white p-6 rounded shadow flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium uppercase tracking-wide">{{ $card['title'] }}</div>
                            <div class="text-3xl font-bold">{{ $card['value'] }}</div>
                        </div>
                        <i data-lucide="{{ $card['icon'] }}" class="w-10 h-10 opacity-80"></i>
                    </div>
                @endforeach
            </div>


            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $charts = [
                        ['id' => 'ordersChart', 'title' => 'Órdenes por Mes', 'icon' => 'calendar'],
                        ['id' => 'ordersByStatusChart', 'title' => 'Órdenes por Estado', 'icon' => 'bar-chart-3'],
                        ['id' => 'productsByCategoryChart', 'title' => 'Productos por Categoría', 'icon' => 'layers'],
                        ['id' => 'ordersByResponsableChart', 'title' => 'Órdenes por Responsable', 'icon' => 'users'],
                    ];
                @endphp

                @foreach ($charts as $chart)
                    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                        <div class="mb-4 flex items-center gap-2 text-gray-900 dark:text-gray-100 font-bold">
                            <i data-lucide="{{ $chart['icon'] }}" class="w-5 h-5"></i>
                            {{ $chart['title'] }}
                        </div>
                        <canvas id="{{ $chart['id'] }}" class="h-64 w-full"></canvas>
                    </div>
                @endforeach
            </div>

            <!-- Productos con bajo stock -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <div class="mb-4 font-bold text-gray-900 dark:text-gray-100 text-lg flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400"></i>
                    Productos con Stock Bajo
                </div>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Los siguientes productos tienen un stock de 3 unidades o menos. Por favor, considere reabastecerlos.
                </p>


                @if ($lowStockProducts->where('cantidad', '<=', 3)->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead
                                class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 uppercase text-xs tracking-wider">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left">Producto</th>
                                    <th scope="col" class="px-4 py-2 text-left">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @php
                                    $filteredProducts = $lowStockProducts->where('cantidad', '<=', 3);
                                @endphp

                                @forelse ($filteredProducts as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-4 py-2">
                                            <a href="{{ route('productos.show', $product->id_producto) }}"
                                                class="text-blue-400 hover:underline">
                                                <span class="font-medium">{{ $product->nombre_producto }}</span>
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 font-bold text-red-600 dark:text-red-400">
                                            {{ $product->cantidad }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-2 text-center text-gray-500">
                                            No hay productos con stock bajo.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-gray-700 dark:text-gray-300 font-medium">Todos los productos tienen stock
                        suficiente.</div>
                @endif
            </div>

        </div>
    </div>


    @push('scripts')
        <script type="application/json" id="dashboard-data">
        {
            "ordersByStatus": @json($ordersByStatus),
            "ordersByMonth": @json($ordersByMonth),
            "productCategories": @json($productCategories),
            "responsableOrders": @json($responsableOrders)
        }
    </script>
    @endpush

</x-app-layout>
