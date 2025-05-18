@extends('layouts.app')

@section('title', 'Mis Categorías')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">

        <div class="flex justify-between items-center mb-6">
            <h1 class=" pe-4 text-2xl font-bold text-gray-900">Mis Categorías</h1>
            <a href="{{ route('categories.create') }}"
               class="bg-blue-600 text-white px-4 p-2 rounded-lg hover:bg-blue-700 transition-colors">
                + Nueva Categoría
            </a>
        </div>

        <!-- Categorías de Ingresos -->
        <div class="py-6">
            <h2 class="text-xl font-semibold mb-4">Categorías de Ingresos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categorias->where('tipo', 'ingreso') as $categoria)
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if($categoria->icono)
                                    <span class="text-2xl mr-2">{{ $categoria->icono }}</span>
                                @endif
                                <span class="font-medium mr-2">{{ $categoria->nombre }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('categories.edit', $categoria) }}"
                                   class="text-blue-600 hover:text-blue-800">
                                    Editar
                                </a>
                                <form action="{{ route('categories.destroy', $categoria) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Categorías de Gastos -->
        <div class="py-6">
            <h2 class="text-xl font-semibold mb-4">Categorías de Gastos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categorias->where('tipo', 'gasto') as $categoria)
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if($categoria->icono)
                                    <span class="text-2xl mr-2">{{ $categoria->icono }}</span>
                                @endif
                                <span class="font-medium">{{ $categoria->nombre }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('categories.edit', $categoria) }}"
                                   class="text-blue-600 hover:text-blue-800">
                                    Editar
                                </a>
                                <form action="{{ route('categories.destroy', $categoria) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
