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


        $selectedStart = request('start_date');
        $selectedEnd = request('end_date');

        if ($selectedStart && $selectedEnd) {
            $startDateFilter = Carbon::createFromFormat('Y-m-d', $selectedStart)->startOfDay();
            $endDateFilter = Carbon::createFromFormat('Y-m-d', $selectedEnd)->endOfDay();
        } else {
            $startDateFilter = Carbon::now()->startOfMonth();
            $endDateFilter = Carbon::now()->endOfMonth();
            $selectedStart = $startDateFilter->toDateString();
            $selectedEnd = $endDateFilter->toDateString();
        }

        $prevMonthStart = $startDateFilter->copy()->subMonth()->startOfMonth()->toDateString();
        $prevMonthEnd = $startDateFilter->copy()->subMonth()->endOfMonth()->toDateString();
        $nextMonthStart = $startDateFilter->copy()->addMonth()->startOfMonth()->toDateString();
        $nextMonthEnd = $startDateFilter->copy()->addMonth()->endOfMonth()->toDateString();

        Carbon::setLocale('es');
        $prevMonthLabel = Carbon::createFromFormat('Y-m-d', $prevMonthStart)->translatedFormat('F Y');
        $nextMonthLabel = Carbon::createFromFormat('Y-m-d', $nextMonthStart)->translatedFormat('F Y');



        $ingresos = Movement::where('user_id', $user->id)
            ->where('tipo', 'ingreso')
            ->whereBetween('fecha', [$startDateFilter, $endDateFilter])
            ->sum('monto');
        $gastos = Movement::where('user_id', $user->id)
            ->where('tipo', 'gasto')
            ->whereBetween('fecha', [$startDateFilter, $endDateFilter])
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
            ->whereBetween('movements.fecha', [$startDateFilter, $endDateFilter])
            ->groupBy('categories.nombre')
            ->orderBy('total', 'desc')
            ->get();

        $incomeCategory = DB::table('movements')
            ->join('categories', 'movements.categoria_id', '=', 'categories.id')
            ->selectRaw('categories.nombre as categoria, SUM(movements.monto) as total')
            ->where('movements.user_id', $user->id)
            ->where('movements.tipo', 'ingreso')
            ->whereBetween('movements.fecha', [$startDateFilter, $endDateFilter])
            ->groupBy('categories.nombre')
            ->orderBy('total', 'desc')
            ->get();


        $categoryLabels = $category->pluck('categoria');
        $categoryTotals = $category->pluck('total');

        $incomeCategoryLabels = $incomeCategory->pluck('categoria');
        $incomeCategoryTotals = $incomeCategory->pluck('total');


        return view('dashboard', [
            'ingresos' => $ingresos,
            'gastos' => $gastos,
            'balance' => $balance,
            'startDate' => $selectedStart,
            'endDate' => $selectedEnd,
            'prevMonthStart' => $prevMonthStart,
            'prevMonthEnd' => $prevMonthEnd,
            'prevMonthLabel' => $prevMonthLabel,
            'nextMonthStart' => $nextMonthStart,
            'nextMonthEnd' => $nextMonthEnd,
            'nextMonthLabel' => $nextMonthLabel,
            'monthlyLabels' => $monthlyLabels,
            'ingresosData' => $ingresosData,
            'gastosData' => $gastosData,
            'categoryLabels' => $categoryLabels,
            'categoryTotals' => $categoryTotals,
            'incomeCategoryLabels' => $incomeCategoryLabels,
            'incomeCategoryTotals' => $incomeCategoryTotals,
        ]);

    }
}
