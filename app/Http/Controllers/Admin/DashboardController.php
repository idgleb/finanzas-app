<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Movement;
use App\Models\Category;
use App\Models\News;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalMovements' => Movement::count(),
            'totalCategories' => Category::count(),
            'totalNews' => News::count(),
        ]);
    }
}
