<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CustomAuthenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Log;

// Webhook de Mercado Pago (debe ser público)
Route::post('/payments/webhook', [PaymentController::class, 'webhook'])
    ->name('payments.webhook')
    ->withoutMiddleware(['web']);

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas públicas
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

// Ruta de prueba POST para el webhook
Route::match(['get', 'post'], '/payments/webhook-test', function() {
    if (request()->isMethod('get')) {
        // Si es GET, redirige a la página principal o cualquier otra
        return redirect('/');
    }
    // Si es POST, registra y responde
    Log::info('Test webhook POST endpoint hit', [
        'payload' => request()->all(),
        'headers' => request()->headers->all()
    ]);
    return response()->json(['status' => 'test successful']);
})->withoutMiddleware(['web']);

// Rutas protegidas (usuario autenticado)
Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard de usuario
    Route::get('/dashboard', [DashboardUserController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardUserController::class, 'data'])->name('dashboard.data');

    // Movimientos y categorías
    Route::resource('movements', MovementController::class);
    Route::resource('categories', CategoryController::class);

    // Rutas de pago
    Route::get('/payments/pro-required', [PaymentController::class, 'proRequired'])->name('payments.pro-required');
    Route::post('/payments/process', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/failure', [PaymentController::class, 'failure'])->name('payments.failure');
    Route::get('/payments/pending', [PaymentController::class, 'pending'])->name('payments.pending');

    Route::get('/payments', [PaymentController::class, 'index'])
        ->name('payments.index');

});

// Rutas de administración (admin)
Route::prefix('admin')
    ->middleware(['auth', AdminMiddleware::class])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');
        Route::resource('news', AdminNewsController::class)
            ->names('admin.news');
        Route::resource('users', AdminUserController::class)
            ->names('admin.users');
    });

require __DIR__ . '/auth.php';
