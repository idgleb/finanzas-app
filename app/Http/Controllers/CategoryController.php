<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = auth()->user()
            ->categories()
            ->where('activa', true)
            ->get();

        return view('categories.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isPro()) {
            return redirect()->route('payments.pro-required');
        }

        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isPro()) {
            return redirect()->route('payments.pro-required')
                ->with('error', 'Necesitas un plan PRO para crear categorías personalizadas.');
        }

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'min:1',
            ],
            'tipo' => [
                'required',
                'string',
                'in:ingreso,gasto',
            ],
            'icono' => [
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if ($value && mb_strlen($value) > 5) {
                        $fail('El icono no puede tener más de 5 caracteres o emojis.');
                    }
                },
            ],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.min' => 'El nombre no puede estar vacío.',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',
            'tipo.required' => 'Debes seleccionar un tipo de categoría.',
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'icono.max' => 'El icono no puede tener más de 5 caracteres o emojis.',
        ]);

        // Buscar si existe una categoría inactiva con el mismo nombre y tipo
        $categoriaExistente = auth()->user()->categories()
            ->where('nombre', $request->nombre)
            ->where('tipo', $request->tipo)
            ->where('activa', false)
            ->first();

        if ($categoriaExistente) {
            // Si existe, la reactivamos y actualizamos el icono si se proporcionó uno nuevo
            $categoriaExistente->update([
                'activa' => true,
                'icono' => $request->icono ?? $categoriaExistente->icono
            ]);

            return redirect()->route('categories.index')
                ->with('success', 'Categoría reactivada correctamente.');
        }

        // Si no existe, creamos una nueva
        auth()->user()->categories()->create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'icono' => $request->icono,
            'activa' => true,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada correctamente.');
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
    public function edit(Category $category)
    {
        if (!auth()->user()->isPro()) {
            return redirect()->route('payments.pro-required');
        }

        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        if (!auth()->user()->isPro()) {
            return redirect()->route('payments.pro-required')
                ->with('error', 'Necesitas un plan PRO para modificar categorías.');
        }

        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'min:1',
            ],
            'tipo' => [
                'required',
                'string',
                'in:ingreso,gasto',
            ],
            'icono' => [
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if ($value && mb_strlen($value) > 5) {
                        $fail('El icono no puede tener más de 5 caracteres o emojis.');
                    }
                },
            ],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.min' => 'El nombre no puede estar vacío.',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',
            'tipo.required' => 'Debes seleccionar un tipo de categoría.',
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'icono.max' => 'El icono no puede tener más de 5 caracteres o emojis.',
        ]);

        $category->update([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'icono' => $request->icono,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }

        if (!auth()->user()->isPro()) {
            return redirect()->route('payments.pro-required');
        }

        // En lugar de eliminar, desactivamos la categoría
        $category->update(['activa' => false]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría desactivada correctamente.');
    }
}
