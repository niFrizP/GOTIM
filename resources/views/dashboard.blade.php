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
            @php
                $cards = [
                    [
                        'title' => 'Clientes',
                        'value' => $totalCliente,
                        'param' => 'clientes_filtro',
                        'bg' => 'bg-gray-600',
                        'icon' => 'users',
                        'filtro' => $filtros['clientes'],
                    ],
                    [
                        'title' => 'Órdenes Totales',
                        'value' => $totalOrden,
                        'param' => 'ordenes_filtro',
                        'bg' => 'bg-red-600',
                        'icon' => 'clipboard-list',
                        'filtro' => $filtros['ordenes'],
                    ],
                    [
                        'title' => 'Órdenes Completadas',
                        'value' => $completedOrden,
                        'param' => 'completadas_filtro',
                        'bg' => 'bg-indigo-600',
                        'icon' => 'check-circle',
                        'filtro' => $filtros['completadas'],
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($cards as $card)
                    <div class="{{ $card['bg'] }} text-white p-6 rounded shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium uppercase tracking-wide">{{ $card['title'] }}</div>
                                <div class="text-3xl font-bold">{{ $card['value'] }}</div>
                            </div>
                            <i data-lucide="{{ $card['icon'] }}" class="w-10 h-10 opacity-80"></i>
                        </div>

                        {{-- Filtros horizontales --}}
                        <div class="mt-4 flex flex-wrap gap-2">
                            @php
                                $opciones = ['semana', 'mes', 'año', 'total'];
                            @endphp

                            @foreach ($opciones as $opcion)
                                <form method="GET" class="inline">
                                    {{-- Mantener los otros filtros activos --}}
                                    @foreach ($cards as $otherCard)
                                        @if ($otherCard['param'] !== $card['param'] && request($otherCard['param']))
                                            <input type="hidden" name="{{ $otherCard['param'] }}" value="{{ request($otherCard['param']) }}">
                                        @endif
                                    @endforeach

                                    {{-- Si existe un filtro general para los gráficos, mantenlo --}}
                                    @if (request('filtro'))
                                        <input type="hidden" name="filtro" value="{{ request('filtro') }}">
                                    @endif

                                    <input type="hidden" name="{{ $card['param'] }}" value="{{ $opcion }}">
                                    <button type="submit"
                                        class="text-xs px-2 py-1 rounded transition-all
                                        {{ $card['filtro'] === $opcion ? 'bg-white text-black font-bold' : 'bg-black bg-opacity-30' }}">
                                        {{ ucfirst($opcion) }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>



            <!-- Gráficos -->
            <!--Filtro para gráficos-->
            <form method="GET" action="{{ route('dashboard') }}" class="mb-6 flex gap-4 items-center">
                <label for="filtro" class="p-6 text-gray-900 dark:text-gray-100">Filtrar por:</label>
                <select name="filtro" id="filtro" onchange="this.form.submit()" class="p-2 border rounded">
                    <option value="total" {{ $filtro == 'total' ? 'selected' : '' }}>Total</option>
                    <option value="semana" {{ $filtro == 'semana' ? 'selected' : '' }}>Semana</option>
                    <option value="mes" {{ $filtro == 'mes' ? 'selected' : '' }}>Mes</option>
                    <option value="año" {{ $filtro == 'año' ? 'selected' : '' }}>Año</option>
                </select>
            </form>

            @if ($ordersByMonth->isEmpty())
                <div class="text-center text-gray-500 p-4 border rounded bg-gray-100">
                    No hay datos disponibles para generar el gráfico.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        $charts = [
                            ['id' => 'ordersChart', 'title' => 'Órdenes por Mes', 'icon' => 'calendar'],
                            ['id' => 'ordersByStatusChart', 'title' => 'Órdenes por Estado', 'icon' => 'bar-chart-3'],
                            [
                                'id' => 'productsByCategoryChart',
                                'title' => 'Productos por Categoría',
                                'icon' => 'layers',
                            ],
                            ['id' => 'responsableOrdersChart', 'title' => 'Órdenes por Responsable', 'icon' => 'users'],
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
            @endif

            <!-- Productos con bajo stock -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <div class="mb-4 font-bold text-gray-900 dark:text-gray-100 text-lg flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400"></i>
                    Productos con Stock Bajo
                </div>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Los siguientes productos tienen un stock de 3 unidades o menos. Por favor, considere
                    reabastecerlos.
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
            </div>
            @endif

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
        <script>
            // Ejes Y con enteros y tooltips coherentes
            const integerYAxisOptions = {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    callback: value => Number.isInteger(value) ? value : null
                }
            };

            const integerTooltipOptions = {
                callbacks: {
                    label: context => {
                        let label = context.dataset.label || '';
                        if (label) label += ': ';
                        label += Number.isInteger(context.parsed.y) ?
                            context.parsed.y :
                            Math.round(context.parsed.y);
                        return label;
                    }
                }
            };

            // Traducción de meses al español
            const monthLabels = {!! json_encode($ordersByMonth->keys()) !!}.map(month => ({
                January: 'Enero',
                February: 'Febrero',
                March: 'Marzo',
                April: 'Abril',
                May: 'Mayo',
                June: 'Junio',
                July: 'Julio',
                August: 'Agosto',
                September: 'Septiembre',
                October: 'Octubre',
                November: 'Noviembre',
                December: 'Diciembre'
            } [month] || month));

            // Tamaños para cada gráfico
            document.getElementById('ordersChart').style.height = '250px';
            document.getElementById('ordersByStatusChart').style.height = '250px';
            document.getElementById('productsByCategoryChart').style.height = '250px';
            document.getElementById('responsableOrdersChart').style.height = '250px';

            // Gráfico: Órdenes por Mes
            new Chart(document.getElementById('ordersChart'), {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Órdenes',
                        data: {!! json_encode($ordersByMonth->values()) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    }]
                },
                options: {
                    scales: {
                        y: integerYAxisOptions
                    },
                    plugins: {
                        tooltip: integerTooltipOptions
                    }
                }
            });

            // Gráfico: Órdenes por Estado
            new Chart(document.getElementById('ordersByStatusChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($ordersByStatus->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($ordersByStatus->values()) !!},
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#00A36C', '#C71585'
                        ]
                    }]
                },
                options: {
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold'
                            },
                            formatter: (value, ctx) => {
                                const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percent = ((value / total) * 100).toFixed(1);
                                return `${percent}% (${value})`;
                            }
                        },
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.label}: ${ctx.parsed}`
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            // Gráfico: Productos por Categoría
            new Chart(document.getElementById('productsByCategoryChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($productCategories->keys()) !!},
                    datasets: [{
                        label: 'Productos por Categoría',
                        data: {!! json_encode($productCategories->values()) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                    }]
                },
                options: {
                    scales: {
                        y: integerYAxisOptions
                    },
                    plugins: {
                        tooltip: integerTooltipOptions
                    }
                }
            });

            // Gráfico: Órdenes por Responsable
            new Chart(document.getElementById('responsableOrdersChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($responsableOrders->keys()) !!},
                    datasets: [{
                        label: 'Órdenes por Responsable',
                        data: {!! json_encode($responsableOrders->values()) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: integerYAxisOptions
                    },
                    plugins: {
                        tooltip: integerTooltipOptions
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>
