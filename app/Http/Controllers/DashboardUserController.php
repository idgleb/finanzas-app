<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
/**
 * Retrieves and prepares dashboard data for the authenticated user.
 *
 * Calculates total income, expenses, balance, monthly financial trends,
 * and expense categories for rendering in the dashboard view.
 *
 * @return \Illuminate\View\View Dashboard view with financial metrics
 */


class DashboardUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();


        $selectedMonth = request('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();


        $ingresos = Movement::where('user_id', $user->id)
            ->where('tipo', 'ingreso')
            ->whereBetween('fecha', [$startOfMonth, $endOfMonth])
            ->sum('monto');
        $gastos = Movement::where('user_id', $user->id)
            ->where('tipo', 'gasto')
            ->whereBetween('fecha', [$startOfMonth, $endOfMonth])
            ->sum('monto');

        $balance = $ingresos - $gastos;

        $startDate = Carbon::now()->subMonths(5)->startOfMonth();

        $monthly = DB::table('movements')
            ->selectRaw("DATE_FORMAT(fecha, '%Y-%m') as month, " .
                "SUM(CASE WHEN tipo='ingreso' THEN monto ELSE 0 END) as ingresos, " .
                "SUM(CASE WHEN tipo='gasto' THEN monto ELSE 0 END) as gastos")
            ->where('user_id', $user->id)
            ->where('fecha', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $availableMonths = Movement::where('user_id', $user->id)
            ->selectRaw("DATE_FORMAT(fecha, '%Y-%m') as ym")
            ->groupBy('ym')
            ->orderBy('ym', 'desc')
            ->pluck('ym');


        $monthlyLabels = $monthly->pluck('month')->map(function ($m) {
            return Carbon::createFromFormat('Y-m', $m)->format('M y');
        });
        $ingresosData = $monthly->pluck('ingresos');
        $gastosData = $monthly->pluck('gastos');

        $category = DB::table('movements')
            ->join('categories', 'movements.categoria_id', '=', 'categories.id')
            ->selectRaw('categories.nombre as categoria, SUM(movements.monto) as total')
            ->where('movements.user_id', $user->id)
            ->where('movements.tipo', 'gasto')
            ->whereBetween('movements.fecha', [$startOfMonth, $endOfMonth])
            ->groupBy('categories.nombre')
            ->orderBy('total', 'desc')
            ->get();

        $categoryLabels = $category->pluck('categoria');
        $categoryTotals = $category->pluck('total');

        return view('dashboard', [
            'ingresos' => $ingresos,
            'gastos' => $gastos,
            'balance' => $balance,
            'selectedMonth' => $selectedMonth,
            'availableMonths' => $availableMonths,
            'monthlyLabels' => $monthlyLabels,
            'ingresosData' => $ingresosData,
            'gastosData' => $gastosData,
            'categoryLabels' => $categoryLabels,
            'categoryTotals' => $categoryTotals,
        ]);

    }
}
