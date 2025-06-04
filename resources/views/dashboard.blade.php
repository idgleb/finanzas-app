@extends('layouts.app')

@section('content')

    <h2 class="fw-bold text-primary m-8">¡Hola, {{ Auth::user()->name }}!</h2>

    <div class="container mx-auto p-4 sm:p-6 md:p-8 bg-blue-100 rounded-lg shadow-lg">

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
            <div class="card text-center shadow-sm border-0 p-4 bg-white rounded-lg">
                <canvas id="incomeCategoryChart" class="w-full h-60 md:h-72 lg:h-80"></canvas>
            </div>
            <div class="card text-center shadow-sm border-0 p-4 bg-white rounded-lg">
                <canvas id="expenseCategoryChart" class="w-full h-60 md:h-72 lg:h-80"></canvas>
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
        document.addEventListener('DOMContentLoaded', () => {
            const ctxMonthly = document.getElementById('monthlyChart');
            const ctxExpense = document.getElementById('expenseCategoryChart');
            const ctxIncome = document.getElementById('incomeCategoryChart');
            let monthlyChart, expenseChart, incomeChart;

            function formatCurrency(value) {
                return Number(value).toLocaleString('es-ES', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }


            function renderCharts(data) {
                if (expenseChart) expenseChart.destroy();
                expenseChart = new Chart(ctxExpense, {
                    type: 'doughnut',
                    data: {
                        labels: data.categoryLabels,
                        datasets: [{
                            data: data.categoryTotals,
                            backgroundColor: data.categoryTotals.map((_, i) =>
                                `hsl(${(i * 360) / data.categoryTotals.length}, 70%, 50%)`
                            )
                        }]

                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {position: 'bottom'},
                            title: {
                                display: true,
                                text: `Gastos: $${formatCurrency(data.gastos)}`,
                                align: 'start',
                                padding: {top: 0, left: 0}
                            }
                        }
                    }
                });

                if (incomeChart) incomeChart.destroy();
                incomeChart = new Chart(ctxIncome, {
                    type: 'doughnut',
                    data: {
                        labels: data.incomeCategoryLabels,
                        datasets: [{
                            data: data.incomeCategoryTotals,
                            backgroundColor: data.incomeCategoryTotals.map((_, i) =>
                                `hsl(${(i * 360) / data.incomeCategoryTotals.length}, 70%, 50%)`
                            )
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {position: 'bottom'},
                            title: {
                                display: true,
                                text: `Ingresos: $${formatCurrency(data.ingresos)}`,
                                align: 'start',
                                padding: {top: 0, left: 0}
                            }
                        }
                    }
                });

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


            async function fetchDashboardData() {
                const params = {
                    start_date: document.getElementById('start_date').value,
                    end_date: document.getElementById('end_date').value,
                    month_from: document.getElementById('month_from').value,
                    month_to: document.getElementById('month_to').value
                };
                try {
                    const {data} = await axios.get("{{ route('dashboard.data') }}", {params});
                    renderCharts(data);
                    updateInfo(data);
                } catch (e) {
                    console.error(e);
                }
            }

            document.getElementById('dateFilterForm').addEventListener('submit', e => {
                e.preventDefault();
                fetchDashboardData();
            });

            document.getElementById('monthFilterForm').addEventListener('submit', e => {
                e.preventDefault();
                fetchDashboardData();
            });

            document.querySelectorAll('.nav-month').forEach(link => {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    document.getElementById('start_date').value = link.dataset.start;
                    document.getElementById('end_date').value = link.dataset.end;
                    fetchDashboardData();
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
            renderCharts(initialData);
            updateInfo(initialData);
        });

    </script>

@endsection
