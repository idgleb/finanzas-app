<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Movement;
use App\Models\Category;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $ingresos = Movement::where('user_id', $user->id)->where('tipo', 'ingreso')->sum('monto');
        $gastos = Movement::where('user_id', $user->id)->where('tipo', 'gasto')->sum('monto');
        $balance = $ingresos - $gastos;

        return view('dashboard', compact('ingresos', 'gastos', 'balance'));
    }
}
