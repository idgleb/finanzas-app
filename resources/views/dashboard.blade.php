@extends('layouts.app')

@section('content')
    <div>
        <h2 class="fw-bold text-primary mb-4">¡Hola, {{ Auth::user()->name }}!</h2>

        <form method="GET" class="mb-6 flex items-center space-x-2">
            <label for="start_date" class="text-sm">Desde:</label>
            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="border rounded p-1">
            <label for="end_date" class="text-sm">Hasta:</label>
            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="border rounded p-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Filtrar</button>
        </form>

        <p class="text-sm text-gray-600 mb-2">Datos del {{ $startDate }} al {{ $endDate }}</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="card text-center shadow-sm border-0 p-4 bg-white">
                <h6 class="text-muted">Total Ingresos</h6>
                <h3 class="text-success fw-bold">${{ number_format($ingresos, 2, ',', '.') }}</h3>
            </div>
            <div class="card text-center shadow-sm border-0 p-4 bg-white">
                <h6 class="text-muted">Total Gastos</h6>
                <h3 class="text-danger fw-bold">${{ number_format($gastos, 2, ',', '.') }}</h3>
            </div>
            <div class="card text-center shadow-sm border-0 p-4 bg-white">
                <h6 class="text-muted">Balance</h6>
                <h3 class="text-primary fw-bold">${{ number_format($balance, 2, ',', '.') }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded shadow">
                <canvas id="monthlyChart" height="200"></canvas>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <canvas id="categoryChart" height="200"></canvas>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <canvas id="incomeCategoryChart" height="200"></canvas>
            </div>
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


        new Chart(document.getElementById('categoryChart'), {
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
                    }
                }
            }
        });
    </script>


@endsection
