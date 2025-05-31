import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';

// Parseamos datos
const dashboardDataElement = document.getElementById('dashboard-data');
let dashboardData = {};
if (dashboardDataElement && dashboardDataElement.textContent) {
    try {
        dashboardData = JSON.parse(dashboardDataElement.textContent);
    } catch (e) {
        console.error('Error al analizar los datos del dashboard:', e);
    }
} else {
    console.error('Elemento dashboard-data no encontrado o vacío.');
}

// Función para traducir meses (en su propio scope)
function translateMonth(month) {
    const map = {
        'January': 'enero',
        'February': 'febrero',
        'March': 'marzo',
        'April': 'abril',
        'May': 'mayo',
        'June': 'junio',
        'July': 'julio',
        'August': 'agosto',
        'September': 'septiembre',
        'October': 'octubre',
        'November': 'noviembre',
        'December': 'diciembre'
    };
    return map[month] || month;
}

// Opciones comunes para ejes y tooltips (enteros)
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
            label += Number.isInteger(context.parsed.y) ? context.parsed.y : Math.round(context.parsed.y);
            return label;
        }
    }
};

// Función que pinta gráfico y retorna instancia o null si no hay datos
function createChartOrMessage(canvasId, config) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    // Verificamos si hay datos
    const hasData = config.data.labels.length > 0 && config.data.datasets.some(ds => ds.data.length > 0);

    if (!hasData) {
        ctx.parentNode.innerHTML = '<p class="text-center text-gray-500">No hay datos para mostrar.</p>';
        return null;
    }

    return new Chart(ctx, config);
}

// Gráfico órdenes por estado (pie)
createChartOrMessage('ordersByStatusChart', {
    type: 'pie',
    data: {
        labels: Object.keys(dashboardData.ordersByStatus),
        datasets: [{
            data: Object.values(dashboardData.ordersByStatus),
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
                font: { weight: 'bold' },
                formatter: (value, context) => {
                    const data = context.chart.data.datasets[0].data;
                    const total = data.reduce((a, b) => a + b, 0);
                    const percent = total ? ((value / total) * 100).toFixed(0) : 0;
                    return `${percent}% (${value})`;
                }
            },
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.label}: ${ctx.parsed}`
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});

// Gráfico órdenes por mes (bar)
createChartOrMessage('ordersChart', {
    type: 'bar',
    data: {
        labels: Object.keys(dashboardData.ordersByMonth).map(translateMonth),
        datasets: [{
            label: 'Órdenes',
            data: Object.values(dashboardData.ordersByMonth),
            backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
    },
    options: {
        scales: { y: integerYAxisOptions },
        plugins: { tooltip: integerTooltipOptions }
    }
});

// Gráfico productos por categoría (bar)
createChartOrMessage('productsByCategoryChart', {
    type: 'bar',
    data: {
        labels: Object.keys(dashboardData.productCategories),
        datasets: [{
            label: 'Productos por Categoría',
            data: Object.values(dashboardData.productCategories),
            backgroundColor: 'rgba(75, 192, 192, 0.6)'
        }]
    },
    options: {
        scales: { y: integerYAxisOptions },
        plugins: { tooltip: integerTooltipOptions }
    }
});

// Gráfico órdenes por responsable (bar)
createChartOrMessage('ordersByResponsableChart', {
    type: 'bar',
    data: {
        labels: Object.keys(dashboardData.responsableOrders),
        datasets: [{
            label: 'Órdenes por Responsable',
            data: Object.values(dashboardData.responsableOrders),
            backgroundColor: 'rgba(153, 102, 255, 0.6)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: { y: integerYAxisOptions },
        plugins: { tooltip: integerTooltipOptions }
    }
});

