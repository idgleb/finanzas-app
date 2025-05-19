<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use App\Models\Category;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plan_id' => Plan::getFreePlan()->id,
        ]);

        // Crear categorías predeterminadas para el nuevo usuario
        $categorias = [
            'gasto' => [
                ['nombre' => 'Comida', 'icono' => '🍔'],
                ['nombre' => 'Transporte', 'icono' => '🚗'],
                ['nombre' => 'Alquiler', 'icono' => '🏠'],
                ['nombre' => 'Salud', 'icono' => '💊'],
                ['nombre' => 'Entretenimiento', 'icono' => '🎮'],
                ['nombre' => 'Ropa', 'icono' => '👕'],
                ['nombre' => 'Educación', 'icono' => '📚'],
            ],
            'ingreso' => [
                ['nombre' => 'Sueldo', 'icono' => '💼'],
                ['nombre' => 'Freelance', 'icono' => '🧑‍💻'],
                ['nombre' => 'Inversiones', 'icono' => '📈'],
                ['nombre' => 'Regalos', 'icono' => '🎁'],
                ['nombre' => 'Venta de productos', 'icono' => '🛒'],
            ],
        ];

        foreach ($categorias as $tipo => $items) {
            foreach ($items as $item) {
                Category::create([
                    'nombre' => $item['nombre'],
                    'icono' => $item['icono'],
                    'tipo' => $tipo,
                    'user_id' => $user->id,
                    'activa' => true,
                ]);
            }
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
