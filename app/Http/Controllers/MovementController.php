<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Category;
use DateTimeZone;
use Illuminate\Http\Request;
use Carbon\Carbon;


class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = auth()->user()
            ->movements()
            ->with('category')
            ->latest('fecha')
            ->paginate(5);

        return view('movements.index', compact('movements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Category::where('user_id', auth()->id())
            ->where('activa', true)
            ->get();

        return view('movements.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:ingreso,gasto',
            'monto' => 'required|numeric|min:0.01|max:999999999.99',
            'categoria_id' => 'nullable|exists:categories,id',
            'fecha' => 'required|date',
        ], [
            'tipo.required' => 'Debes seleccionar un tipo de movimiento.',
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'monto.required' => 'El monto es 666 obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto mínimo es $0.01.',
            'monto.max' => 'El monto máximo es $99,999,999.99.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
        ]);

        // 1. Recupero la zona horaria del usuario
        $zonaUsuario = auth()->user()->zona_horaria ?? 'UTC';

        // 2. Parseo la fecha en la zona del usuario
        //    (formato HTML5 datetime-local: 'Y-m-d\TH:i')
        $fechaUsuario = Carbon::createFromFormat(
            'Y-m-d\TH:i',
            $request->fecha,
            $zonaUsuario
        );

        // 3. Convierto a UTC
        $fechaUTC = $fechaUsuario->setTimezone('UTC');

        // 4. Creo el movimiento
        Movement::create([
            'user_id'      => auth()->id(),
            'tipo'         => $request->tipo,
            'monto'        => $request->monto,
            'categoria_id' => $request->categoria_id,
            'fecha'        => $fechaUTC,
        ]);

        return redirect()->route('movements.index')->with('success', 'Movimiento agregado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movement $movement)
    {
        $movement->load('category');
        $categorias = Category::where('user_id', auth()->id())
            ->where('activa', true)
            ->get();

        return view('movements.edit', compact('movement', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movement $movement)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,gasto',
            'categoria_id' => 'nullable|exists:categories,id',
            'monto' => 'required|numeric|min:0.01|max:999999999.99',
        ], [
            'updated_at.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'tipo.required' => 'Debes seleccionar un tipo de movimiento.',
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto mínimo es $0.01.',
            'monto.max' => 'El monto máximo es $99,999,999.99.',
        ]);

        // 1. Recupero la zona horaria del usuario
        $zonaUsuario = auth()->user()->zona_horaria ?? 'UTC';

        // 2. Parseo la fecha en la zona del usuario
        //    (formato HTML5 datetime-local: 'Y-m-d\TH:i')
        $fechaUsuario = Carbon::createFromFormat(
            'Y-m-d\TH:i',
            $request->fecha,
            $zonaUsuario
        );

        // 3. Convierto a UTC
        $fechaUTC = $fechaUsuario->setTimezone('UTC');

        $movement->update([
            'fecha' => $fechaUTC,
            'tipo' => $request->input('tipo'),
            'categoria_id' => $request->input('categoria_id'),
            'monto' => $request->input('monto'),
        ]);

        return redirect()->route('movements.index')->with('success', 'Movimiento actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $movement = Movement::findOrFail($id);
        $movement->delete();

        return redirect()->route('movements.index')->with('success', 'Movimiento eliminado correctamente');
    }
}
