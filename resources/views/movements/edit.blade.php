@extends('layouts.app')

@section('title', 'Editar Movimiento')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Editar Movimiento</h1>

        <form action="{{ route('movements.update', $movement) }}" method="POST" class="space-y-4" id="movementForm">
            @csrf
            @method('PUT')

            <!-- Fecha -->
            <div>
                <label for="fecha" class="block text-sm font-medium mb-1">Fecha</label>
                <input type="datetime-local" name="fecha" id="fecha"
                       value="{{ old('fecha', $movement->fecha_local)  }}"
                       class="w-full border border-gray-300 p-2 rounded @error('fecha') border-red-500 @enderror" required>
                @error('fecha')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-red-500 text-sm mt-1 hidden" id="fecha-error"></p>
            </div>

            <!-- Tipo -->
            <div class="mb-4">
                <label for="tipo" class="block text-sm font-medium mb-1">Tipo</label>
                <select name="tipo" id="tipo"
                        class="w-full border border-gray-300 p-2 rounded @error('tipo') border-red-500 @enderror" required>
                    <option value="ingreso" {{ old('tipo', $movement->tipo) == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                    <option value="gasto" {{ old('tipo', $movement->tipo) == 'gasto' ? 'selected' : '' }}>Gasto</option>
                </select>
                @error('tipo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-red-500 text-sm mt-1 hidden" id="tipo-error"></p>
            </div>

            <!-- Categoría -->
            <div class="mb-4">
                <label for="categoria_id" class="block text-sm font-medium mb-1">Categoría</label>
                <select name="categoria_id"
                        id="categoria_id"
                        data-current="{{ old('categoria_id', $movement->category?->id) }}"
                        class="w-full border border-gray-300 p-2 rounded @error('categoria_id') border-red-500 @enderror">
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" data-tipo="{{ $categoria->tipo }}">
                            {{ $categoria->icono }} {{ $categoria->nombre }} ({{ $categoria->tipo }})
                        </option>
                    @endforeach
                </select>

                @error('categoria_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-red-500 text-sm mt-1 hidden" id="categoria-error"></p>
            </div>

            <!-- Monto -->
            <div class="mb-4">
                <label for="monto" class="block text-sm font-medium mb-1">Monto</label>
                <input type="number" step="0.01" name="monto" id="monto"
                       value="{{ old('monto', $movement->monto) }}"
                       class="w-full border border-gray-300 p-2 rounded @error('monto') border-red-500 @enderror"
                       required
                       min="0.01"
                       max="999999999.99"
                       title="El monto debe estar entre $0.01 y $999,999,999.99">
                @error('monto')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-red-500 text-sm mt-1 hidden" id="monto-error"></p>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-2">
                <a href="{{ route('movements.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancelar</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar cambios</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('movementForm');
            const tipoSelect = document.getElementById('tipo');
            const montoInput = document.getElementById('monto');
            const fechaInput = document.getElementById('fecha');
            const categoriaSelect = document.getElementById('categoria_id');

            function showError(elementId, message) {
                const errorElement = document.getElementById(`${elementId}-error`);
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
                document.getElementById(elementId).classList.add('border-red-500');
            }

            function clearError(elementId) {
                const errorElement = document.getElementById(`${elementId}-error`);
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
                document.getElementById(elementId).classList.remove('border-red-500');
            }

            function validateAllFields() {
                let hasErrors = false;
                const errors = {};

                // Validar monto
                if (!montoInput.value.trim()) {
                    errors.monto = 'El monto es obligatorio';
                    hasErrors = true;
                } else {
                    const monto = parseFloat(montoInput.value);
                    if (monto < 0.01 || monto > 999999999.99) {
                        errors.monto = 'El monto debe estar entre $0.01 y $999,999,999.99';
                        hasErrors = true;
                    }
                }

                // Validar tipo
                if (!tipoSelect.value) {
                    errors.tipo = 'Debes seleccionar un tipo de movimiento';
                    hasErrors = true;
                }

                // Validar fecha
                if (!fechaInput.value) {
                    errors.fecha = 'La fecha es obligatoria';
                    hasErrors = true;
                } else {
                    const fecha = new Date(fechaInput.value);
                    const now = new Date();
                    if (fecha > now) {
                        errors.fecha = 'La fecha no puede ser futura';
                        hasErrors = true;
                    }
                }

                // Limpiar todos los errores primero
                clearError('monto');
                clearError('tipo');
                clearError('fecha');

                // Mostrar todos los errores encontrados
                if (hasErrors) {
                    Object.entries(errors).forEach(([field, message]) => {
                        showError(field, message);
                    });
                }

                return !hasErrors;
            }

            // Validación en tiempo real
            montoInput.addEventListener('input', function() {
                if (!this.value.trim()) {
                    showError('monto', 'El monto es obligatorio');
                } else {
                    const monto = parseFloat(this.value);
                    if (monto < 0.01 || monto > 999999999.99) {
                        showError('monto', 'El monto debe estar entre $0.01 y $999,999,999.99');
                    } else {
                        clearError('monto');
                    }
                }
            });

            fechaInput.addEventListener('change', function() {
                if (!this.value) {
                    showError('fecha', 'La fecha es obligatoria');
                } else {
                    const fecha = new Date(this.value);
                    const now = new Date();
                    if (fecha > now) {
                        showError('fecha', 'La fecha no puede ser futura');
                    } else {
                        clearError('fecha');
                    }
                }
            });

            tipoSelect.addEventListener('change', function() {
                if (!this.value) {
                    showError('tipo', 'Debes seleccionar un tipo de movimiento');
                } else {
                    clearError('tipo');
                }
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (validateAllFields()) {
                    form.submit();
                }
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoriaSelect = document.getElementById('categoria_id');
            // Leemos el id que queremos seleccionar tras filtrar:
            const currentCategoria = categoriaSelect.dataset.current;

            // Guardamos las opciones originales:
            const opcionesCategorias = Array.from(categoriaSelect.options).map(option => ({
                value: option.value,
                text: option.text,
                tipo: option.getAttribute('data-tipo'),
            }));

            function filtrarCategorias() {
                const tipoSeleccionado = document.getElementById('tipo').value;
                categoriaSelect.innerHTML = '';

                opcionesCategorias.forEach(opcion => {
                    if (opcion.tipo === tipoSeleccionado || tipoSeleccionado === '') {
                        const option = document.createElement('option');
                        option.value = opcion.value;
                        option.text = opcion.text;
                        option.setAttribute('data-tipo', opcion.tipo);
                        // Si coincide con la categoría actual, márcala como seleccionada
                        if (opcion.value === currentCategoria) {
                            option.selected = true;
                        }
                        categoriaSelect.appendChild(option);
                    }
                });
            }

            // Cada vez que cambie el tipo:
            document.getElementById('tipo').addEventListener('change', filtrarCategorias);

            // Filtramos al cargar, y así seleccionamos la de BD
            filtrarCategorias();
        });
    </script>

@endsection
