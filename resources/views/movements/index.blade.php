@extends('layouts.app')

@section('title', 'Mis movimientos')

@section('content')
    <div class="container mx-auto p-4 sm:p-6 md:p-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-4">Mis Movimientos</h1>

        <!-- 🔹 Botón para crear movimiento -->
        <a href="{{ route('movements.create') }}"
           class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Agregar movimiento
        </a>

        <!-- Tabla de movimientos -->
        <div class="overflow-x-auto">
            <table
                class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow text-xs sm:text-sm md:text-base">
                <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Fecha</th>
                    <th class="p-3 text-left">Tipo</th>
                    <th class="p-3 text-left">Categoría</th>
                    <th class="p-3 text-right">Monto</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach($movements as $movement)
                    <tr class="hover:bg-gray-50">
                        {{-- Aquí tu trait convierte el UTC a la zona de usuario --}}

                        <td class="p-3">{{ $movement->fecha_local }}</td>

                        <td class="p-3">{{ ucfirst($movement->tipo) }}</td>
                        <td class="p-3">
                            {!! $movement->category->icono ?? '' !!}
                            {{ $movement->category->nombre ?? 'Sin categoría' }}
                        </td>
                        <td class="p-3 text-right">
                            ${{ number_format($movement->monto, 2) }}
                        </td>
                        <td class="p-2 text-right space-x-2">
                            <a href="{{ route('movements.edit', $movement) }}"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <i class="bi bi-pencil-square me-1"></i>
                                <span class="hidden sm:inline">Editar</span>
                            </a>
                            <form action="{{ route('movements.destroy', $movement) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('¿Eliminar?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-800">
                                    <i class="bi bi-trash me-1"></i>
                                    <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 justify-items-center">
            {{ $movements->links() }}
        </div>
    </div>
@endsection
