@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Panel de Administración</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded p-4 text-center">
            <h2 class="text-lg font-semibold text-gray-600">Usuarios</h2>
            <p class="text-3xl font-bold text-blue-500">{{ $totalUsers }}</p>
        </div>

        <div class="bg-white shadow rounded p-4 text-center">
            <h2 class="text-lg font-semibold text-gray-600">Movimientos</h2>
            <p class="text-3xl font-bold text-green-500">{{ $totalMovements }}</p>
        </div>

        <div class="bg-white shadow rounded p-4 text-center">
            <h2 class="text-lg font-semibold text-gray-600">Categorías</h2>
            <p class="text-3xl font-bold text-yellow-500">{{ $totalCategories }}</p>
        </div>

        <div class="bg-white shadow rounded p-4 text-center">
            <h2 class="text-lg font-semibold text-gray-600">Noticias</h2>
            <p class="text-3xl font-bold text-red-500">{{ $totalNews }}</p>
        </div>
    </div>
@endsection
