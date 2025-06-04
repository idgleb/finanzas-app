@extends('layouts.app')

@section('content')

    <h2 class="fw-bold text-primary m-8">¡Hola, {{ Auth::user()->name }}!</h2>

    <div class="container  mx-auto p-4 sm:p-6 md:p-8 bg-blue-100 rounded-lg shadow-lg">

        <div class="flex justify-between mb-4">
            <a href="{{ route('dashboard', ['start_date' => $prevMonthStart, 'end_date' => $prevMonthEnd]) }}"
               class="btn-outline-finanzas nav-month"
               data-url="{{ route('dashboard') }}"
               data-start="{{ $prevMonthStart }}" data-end="{{ $prevMonthEnd }}">
                &laquo; {{ $prevMonthLabel }}
            </a>
            <a href="{{ route('dashboard', ['start_date' => $nextMonthStart, 'end_date' => $nextMonthEnd]) }}"
               class="btn-outline-finanzas nav-month"
               data-url="{{ route('dashboard') }}"
               data-start="{{ $nextMonthStart }}" data-end="{{ $nextMonthEnd }}">
                {{ $nextMonthLabel }} &raquo;
            </a>
        </div>

        <form method="GET" id="dateFilterForm"
              class="m-6 flex flex-col md:flex-row items-center justify-center space-y-2 md:space-y-0 md:space-x-2">
            <label for="start_date" class="text-sm">Desde:</label>
            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="border rounded p-1">
            <label for="end_date" class="text-sm">Hasta:</label>
            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="border rounded p-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Filtrar</button>
        </form>

        <p id="dateRange" class="text-sm text-gray-600 mb-2">Datos del {{ $startDate }} al {{ $endDate }} </p>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-3 ">
            <div class="card text-center shadow-sm border-0 p-4 bg-white rounded-lg">
                <h6 id="balance" class="text-muted">Balance: ${{ number_format($balance, 2, ',', '.') }}</h6>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="card text-center justify-items-center shadow-sm border-0 p-4 bg-white rounded-lg">
                <canvas id="incomeCategoryChart" class="w-full h-60 md:h-44 lg:h-44 p-1"></canvas>
                <div id="incomeCategoryLegend" class="mt-2 flex flex-wrap justify-center"></div>
            </div>
            <div class="card text-center justify-items-center shadow-sm border-0 p-4 bg-white rounded-lg">
                <canvas id="expenseCategoryChart" class="w-full h-60 md:h-44 lg:h-44 p-1"></canvas>
                <div id="expenseCategoryLegend" class="mt-2 flex flex-wrap justify-center"></div>
            </div>
        </div>
    </div>



    <div class="container mx-auto p-4 sm:p-6 md:p-8 mt-8 mb-8 bg-blue-100 rounded-lg shadow-lg">
        <form method="GET" id="monthFilterForm"
              class="mb-4 flex flex-col md:flex-row items-center justify-center space-y-2 md:space-y-0 md:space-x-2">
            <label for="month_from" class="text-sm">Mes desde:</label>
            <input type="month" id="month_from" name="month_from" value="{{ $monthFrom }}" class="border rounded p-1">
            <label for="month_to" class="text-sm">Mes hasta:</label>
            <input type="month" id="month_to" name="month_to" value="{{ $monthTo }}" class="border rounded p-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Filtrar</button>
        </form>
        <div class="bg-white rounded shadow">
            <canvas id="monthlyChart" class="w-full h-60 md:h-72 lg:h-80"></canvas>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        const getOrCreateLegendList = (chart, id) => {
            const legendContainer = document.getElementById(id);
            let listContainer = legendContainer.querySelector('ul');
            if (!listContainer) {
                listContainer = document.createElement('ul');
                listContainer.className = 'flex flex-wrap justify-center gap-2 text-sm p-0 m-0 list-none';
                legendContainer.appendChild(listContainer);
            }
            return listContainer;
        };

        const htmlLegendPlugin = {
            id: 'htmlLegend',
            afterUpdate(chart, args, options) {
                const ul = getOrCreateLegendList(chart, options.containerID);
                while (ul.firstChild) {
                    ul.firstChild.remove();
                }

                const items = chart.options.plugins.legend.labels.generateLabels(chart);
                items.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center cursor-pointer';
                    li.onclick = () => {
                        chart.toggleDataVisibility(item.index);
                        chart.update();
                    };

                    const box = document.createElement('span');
                    box.className = 'w-3 h-3 mr-1 inline-block';
                    box.style.background = item.fillStyle;
                    box.style.borderColor = item.strokeStyle;
                    box.style.borderWidth = item.lineWidth + 'px';

                    const text = document.createElement('span');
                    text.textContent = item.text;

                    li.appendChild(box);
                    li.appendChild(text);
                    ul.appendChild(li);
                });
            }
        };

        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw(chart, args, options) {
                const {ctx, chartArea} = chart;
                if (!chartArea) {
                    return;
                }
                const {width, height} = chartArea;
                ctx.save();
                ctx.font = options.font || 'bold 16px sans-serif';
                ctx.fillStyle = options.color || '#111';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const lines = options.text.split('\n');
                const lineHeight = options.lineHeight || 16;
                lines.forEach((line, index) => {
                    const y = height / 2 + (index - (lines.length - 1) / 2) * lineHeight;
                    ctx.fillText(line, width / 2, y);
                });

                ctx.restore();
            }
        };


        document.addEventListener('DOMContentLoaded', () => {
            const ctxMonthly = document.getElementById('monthlyChart');
            const ctxExpense = document.getElementById('expenseCategoryChart');
            const ctxIncome = document.getElementById('incomeCategoryChart');
            let monthlyChart, expenseChart, incomeChart;

            function formatCurrency(value) {
                return Number(value).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }

            function renderDateCharts(data) {
                if (incomeChart) incomeChart.destroy();
                incomeChart = new Chart(ctxIncome, {
                    type: 'doughnut',
                    data: {
                        labels: data.incomeCategoryLabels.map((label, i) =>
                            `${label}: $${formatCurrency(data.incomeCategoryTotals[i])}`
                        ),
                        datasets: [{
                            data: data.incomeCategoryTotals,
                            backgroundColor: data.incomeCategoryTotals.map((_, i) =>
                                `hsl(${(i * 360) / data.incomeCategoryTotals.length}, 70%, 50%)`
                            )
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        radius: '100%',
                        cutout: '70%',
                        plugins: {
                            legend: {display: false},
                            htmlLegend: {containerID: 'incomeCategoryLegend'},
                            centerText: {
                                text: `Ingresos\n\n$${formatCurrency(data.ingresos)}`,
                                color: '#007c2d'
                            }
                        }
                    },
                    plugins: [htmlLegendPlugin, centerTextPlugin]
                });
                if (expenseChart) expenseChart.destroy();
                expenseChart = new Chart(ctxExpense, {
                    type: 'doughnut',
                    data: {
                        labels: data.categoryLabels.map((label, i) =>
                            `${label}: $${formatCurrency(data.categoryTotals[i])}`
                        ),
                        datasets: [{
                            data: data.categoryTotals,
                            backgroundColor: data.categoryTotals.map((_, i) =>
                                `hsl(${(i * 360) / data.categoryTotals.length}, 70%, 50%)`
                            )
                        }]

                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        radius: '100%',
                        cutout: '70%',
                        plugins: {
                            legend: {display: false},
                            htmlLegend: {containerID: 'expenseCategoryLegend'},
                            centerText: {
                                text: `Gastos\n\n$${formatCurrency(data.gastos)}`,
                                color: '#dc2626'
                            }
                        }
                    },
                    plugins: [htmlLegendPlugin, centerTextPlugin]
                });
            }
            function renderMonthChart(data) {
                if (monthlyChart) monthlyChart.destroy();
                monthlyChart = new Chart(ctxMonthly, {
                    type: 'bar',
                    data: {
                        labels: data.monthlyLabels,
                        datasets: [
                            {
                                label: 'Ingresos',
                                data: data.ingresosData,
                                borderColor: '#16a34a',
                                backgroundColor: 'rgba(22,163,74,0.2)',
                                tension: 0.4
                            },
                            {
                                label: 'Gastos',
                                data: data.gastosData,
                                borderColor: '#dc2626',
                                backgroundColor: 'rgba(220,38,38,0.2)',
                                tension: 0.4
                            }
                        ]
                    },
                    options: {responsive: true, maintainAspectRatio: false}
                });
            }

            function updateInfo(data) {
                document.getElementById('dateRange').textContent = `Datos del ${data.startDate} al ${data.endDate}`;
                document.getElementById('balance').textContent = `Balance: $${formatCurrency(data.balance)}`;
                const links = document.querySelectorAll('.nav-month');
                if (links.length === 2) {
                    links[0].dataset.start = data.prevMonthStart;
                    links[0].dataset.end = data.prevMonthEnd;
                    links[0].textContent = `« ${data.prevMonthLabel}`;
                    links[0].href = `${links[0].dataset.url}?start_date=${data.prevMonthStart}&end_date=${data.prevMonthEnd}`;
                    links[1].dataset.start = data.nextMonthStart;
                    links[1].dataset.end = data.nextMonthEnd;
                    links[1].textContent = `${data.nextMonthLabel} »`;
                    links[1].href = `${links[1].dataset.url}?start_date=${data.nextMonthStart}&end_date=${data.nextMonthEnd}`;
                }
            }



            async function fetchDateDashboardData() {
                const params = {
                    start_date: document.getElementById('start_date').value,
                    end_date: document.getElementById('end_date').value,
                    month_from: document.getElementById('month_from').value,
                    month_to: document.getElementById('month_to').value
                };
                try {
                    const {data} = await axios.get("{{ route('dashboard.data') }}", {params});
                    renderDateCharts(data);
                    updateInfo(data);
                } catch (e) {
                    console.error(e);
                }
            }
            async function fetchMonthDashboardData() {
                const params = {
                    start_date: document.getElementById('start_date').value,
                    end_date: document.getElementById('end_date').value,
                    month_from: document.getElementById('month_from').value,
                    month_to: document.getElementById('month_to').value
                };
                try {
                    const {data} = await axios.get("{{ route('dashboard.data') }}", {params});
                    renderMonthChart(data);
                } catch (e) {
                    console.error(e);
                }
            }



            document.getElementById('dateFilterForm').addEventListener('submit', e => {
                e.preventDefault();
                fetchDateDashboardData();
            });

            document.getElementById('monthFilterForm').addEventListener('submit', e => {
                e.preventDefault();
                fetchMonthDashboardData();
            });

            document.querySelectorAll('.nav-month').forEach(link => {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    document.getElementById('start_date').value = link.dataset.start;
                    document.getElementById('end_date').value = link.dataset.end;
                    fetchDateDashboardData();
                });
            });

            const initialData = {
                monthlyLabels: @json($monthlyLabels),
                ingresosData: @json($ingresosData),
                gastosData: @json($gastosData),
                categoryLabels: @json($categoryLabels),
                categoryTotals: @json($categoryTotals),
                incomeCategoryLabels: @json($incomeCategoryLabels),
                incomeCategoryTotals: @json($incomeCategoryTotals),
                gastos: {{ $gastos }},
                ingresos: {{ $ingresos }},
                balance: {{ $balance }},
                startDate: '{{ $startDate }}',
                endDate: '{{ $endDate }}',
                prevMonthStart: '{{ $prevMonthStart }}',
                prevMonthEnd: '{{ $prevMonthEnd }}',
                prevMonthLabel: '{{ $prevMonthLabel }}',
                nextMonthStart: '{{ $nextMonthStart }}',
                nextMonthEnd: '{{ $nextMonthEnd }}',
                nextMonthLabel: '{{ $nextMonthLabel }}'
            };

           // Render charts and update info with initial data
            renderDateCharts(initialData);
            renderMonthChart(initialData)
            updateInfo(initialData);



        });

    </script>

@endsection
