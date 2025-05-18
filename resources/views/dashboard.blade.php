@extends('layouts.app')

@section('content')
    <div class="">
        <h2 class="fw-bold text-primary mb-4">¡Hola, {{ Auth::user()->name }}!</h2>


        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 p-4">
                <h6 class="text-muted">Total Ingresos</h6>
                <h3 class="text-success fw-bold">${{ number_format($ingresos, 2, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 p-4">
                <h6 class="text-muted">Total Gastos</h6>
                <h3 class="text-danger fw-bold">${{ number_format($gastos, 2, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0 p-4">
                <h6 class="text-muted">Balance</h6>
                <h3 class="text-primary fw-bold">${{ number_format($balance, 2, ',', '.') }}</h3>
            </div>
        </div>
    </div>

@endsection
