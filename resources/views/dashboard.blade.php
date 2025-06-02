@extends('layouts.app')

@section('content')

    <h2 class="fw-bold text-primary m-8">¡Hola, {{ Auth::user()->name }}!</h2>

    <div class="p-8 bg-blue-100 rounded-lg shadow-lg">

        <div class="flex justify-between mb-4">
            <a href="{{ route('dashboard', ['start_date' => $prevMonthStart, 'end_date' => $prevMonthEnd]) }}"
               class="btn-outline-finanzas">
                &laquo; {{ $prevMonthLabel }}
            </a>
            <a href="{{ route('dashboard', ['start_date' => $nextMonthStart, 'end_date' => $nextMonthEnd]) }}"
               class="btn-outline-finanzas">
                {{ $nextMonthLabel }} &raquo;
            </a>
        </div>

        <form method="GET" class="m-6 flex items-center space-x-2 justify-center">
            <label for="start_date" class="text-sm">Desde:</label>
            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="border rounded p-1">
            <label for="end_date" class="text-sm">Hasta:</label>
            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="border rounded p-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Filtrar</button>
        </form>

        <p class="text-sm text-gray-600 mb-2">Datos del {{ $startDate }} al {{ $endDate }} </p>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-3 ">
            <div class="card text-center shadow-sm border-0 p-4 bg-white rounded-lg">
                <h6 class="text-muted">Balance: ${{ number_format($balance, 2, ',', '.') }}</h6>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="card text-center shadow-sm border-0 p-4 bg-white rounded-lg">
                <canvas id="incomeCategoryChart" height="200"></canvas>
            </div>
            <div class="card text-center shadow-sm border-0 p-4 bg-white rounded-lg">
                <canvas id="expenseCategoryChart" height="200"></canvas>
            </div>
        </div>
    </div>



    <div class="p-8 mt-8 mb-8   bg-blue-100 rounded-lg shadow-lg">

    <div class="bg-white rounded shadow">
        <canvas id="monthlyChart" height="200"></canvas>
    </div>
    </div>


    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlyLabels = @json($monthlyLabels);
        const ingresosData = @json($ingresosData);
        const gastosData = @json($gastosData);

        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [
                    {
                        label: 'Ingresos',
                        data: ingresosData,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22,163,74,0.2)',
                        tension: 0.4
                    },
                    {
                        label: 'Gastos',
                        data: gastosData,
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220,38,38,0.2)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        const categoryLabels = @json($categoryLabels);
        const categoryTotals = @json($categoryTotals);
        const colors = categoryTotals.map((_, i) =>
            `hsl(${(i * 360) / categoryTotals.length}, 70%, 50%)`
        );


        new Chart(document.getElementById('expenseCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryTotals,
                    backgroundColor: colors.slice(0, categoryTotals.length)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Gastos: ${{ number_format($gastos, 2, ',', '.') }}',
                        align: 'start',
                        padding: {
                            top: 0,
                            left: 0
                        }
                    }
                }
            }
        });


        const incomeCategoryLabels = @json($incomeCategoryLabels);
        const incomeCategoryTotals = @json($incomeCategoryTotals);
        const incomeColors = incomeCategoryTotals.map((_, i) =>
            `hsl(${(i * 360) / incomeCategoryTotals.length}, 70%, 50%)`
        );

        new Chart(document.getElementById('incomeCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: incomeCategoryLabels,
                datasets: [{
                    data: incomeCategoryTotals,
                    backgroundColor: incomeColors.slice(0, incomeCategoryTotals.length)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Ingresos: ${{ number_format($ingresos, 2, ',', '.') }}',
                        align: 'start', // 'start' para esquina superior izquierda
                        padding: {
                            top: 0,
                            left: 0
                        }
                    }
                }
            }
        });
    </script>

@endsection
