@extends('layouts.app')

@section('title', 'Mis movimientos')

@section('content')
    <div class="pt-4 pb-4">
        <h1 class="text-2xl font-bold mb-4">Mis Movimientos</h1>

        <!-- 🔹 Botón para crear movimiento -->
        <a href="{{ route('movements.create') }}"
           class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Agregar movimiento
        </a>

        <!-- Tabla de movimientos -->
        <table class="min-w-full bg-white rounded shadow">
            <thead>
            <tr>
                <th class="p-2 text-left">Fecha</th>
                <th class="p-2 text-left">Tipo</th>
                <th class="p-2 text-left">Categoría</th>
                <th class="p-2 text-right">Monto</th>
                <th class="p-2 text-right">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($movements as $movement)
                <tr>
                    {{-- Aquí tu trait convierte el UTC a la zona de usuario --}}

                    <td class="p-2">{{ $movement->fecha_local }}</td>

                    <td class="p-2">{{ ucfirst($movement->tipo) }}</td>
                    <td class="p-2">
                        {!! $movement->category->icono ?? '' !!}
                        {{ $movement->category->nombre ?? 'Sin categoría' }}
                    </td>
                    <td class="p-2 text-right">
                        ${{ number_format($movement->monto, 2) }}
                    </td>
                    <td class="p-2 text-right space-x-2">
                        <a href="{{ route('movements.edit', $movement) }}"
                           class="text-blue-600 hover:underline">Editar</a>
                        <form action="{{ route('movements.destroy', $movement) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('¿Eliminar?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="p-4 justify-items-center">
            {{ $movements->links() }}
        </div>
    </div>
@endsection
