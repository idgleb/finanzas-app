@extends('layouts.app')

@section('title', 'Crear Categoría')

@section('content')
<div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Crear Nueva Categoría</h1>

        <form action="{{ route('categories.store') }}" method="POST" class="space-y-6" id="categoryForm">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" id="nombre" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       value="{{ old('nombre') }}" 
                       required
                       minlength="1"
                       maxlength="50"
                       title="El nombre no puede estar vacío">
                @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo -->
            <div>
                <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo</label>
                <select name="tipo" id="tipo" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                    <option value="">Seleccione un tipo</option>
                    <option value="ingreso" {{ old('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                    <option value="gasto" {{ old('tipo') == 'gasto' ? 'selected' : '' }}>Gasto</option>
                </select>
                @error('tipo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Icono -->
            <div>
                <label for="icono" class="block text-sm font-medium text-gray-700">Icono (opcional)</label>
                <input type="text" name="icono" id="icono" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       value="{{ old('icono') }}"
                       maxlength="20"
                       title="Hasta 5 caracteres o emojis"
                       placeholder="Ejemplo: 🏠🚗🍔, $%&@#, etc.">
                @error('icono')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('categories.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Crear Categoría
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    const tipo = document.getElementById('tipo').value;
    const icono = document.getElementById('icono').value.trim();

    // Validación del nombre
    if (nombre.length === 0) {
        e.preventDefault();
        alert('El nombre no puede estar vacío.');
        return;
    }

    if (nombre.length > 50) {
        e.preventDefault();
        alert('El nombre no puede tener más de 50 caracteres.');
        return;
    }

    // Validación del tipo
    if (!tipo) {
        e.preventDefault();
        alert('Debes seleccionar un tipo de categoría.');
        return;
    }

    // Validación del icono (si se proporciona)
    if (icono && icono.length > 5) {
        e.preventDefault();
        alert('El icono no puede tener más de 5 caracteres o emojis.');
        return;
    }
});
</script>
@endpush

@endsection 