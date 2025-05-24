<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\MealTypeController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\RestaurantController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\DebugSession;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Routes - No need to specify 'web' middleware as it's applied by RouteServiceProvider
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Resource Routes
        Route::resource('hotels', HotelController::class);
        Route::resource('restaurants', RestaurantController::class);
        Route::resource('meal-types', MealTypeController::class);
        Route::resource('reservations', ReservationController::class);
        
        // Menu Management Routes
        Route::prefix('restaurants/{restaurant}/menu')->name('restaurants.menu.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            
            // Menu Category Routes
            Route::get('/categories/create', [MenuController::class, 'createCategory'])->name('createCategory');
            Route::post('/categories', [MenuController::class, 'storeCategory'])->name('storeCategory');
            Route::get('/categories/{category}/edit', [MenuController::class, 'editCategory'])->name('editCategory');
            Route::put('/categories/{category}', [MenuController::class, 'updateCategory'])->name('updateCategory');
            Route::delete('/categories/{category}', [MenuController::class, 'destroyCategory'])->name('destroyCategory');
            
            // Menu Item Routes
            Route::get('/categories/{category}/items/create', [MenuController::class, 'createItem'])->name('createItem');
            Route::post('/categories/{category}/items', [MenuController::class, 'storeItem'])->name('storeItem');
            Route::get('/items/{item}/edit', [MenuController::class, 'editItem'])->name('editItem');
            Route::put('/items/{item}', [MenuController::class, 'updateItem'])->name('updateItem');
            Route::delete('/items/{item}', [MenuController::class, 'destroyItem'])->name('destroyItem');
        });
        
        // Reports
        Route::get('reports/reservations', [ReportController::class, 'reservations'])->name('reports.reservations');
        Route::get('reports/statistics', [ReportController::class, 'statistics'])->name('reports.statistics');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Test session route for debugging
Route::get('/test-session', function () {
    // Generate a session ID if one doesn't exist
    if (!session()->has('test_key')) {
        session(['test_key' => 'test_value_' . time()]);
    }
    
    // Get the session ID
    $sessionId = session()->getId();
    
    // Check if the session exists in the database
    $session = DB::table('sessions')
        ->where('session_id', $sessionId)
        ->first();
    
    $output = [
        'session_id' => $sessionId,
        'test_key' => session('test_key', 'not set'),
        'session_in_db' => $session ? 'Found' : 'Not found',
    ];
    
    if ($session) {
        $output['session_details'] = [
            'last_activity' => date('Y-m-d H:i:s', $session->last_activity),
            'payload_length' => strlen($session->payload),
        ];
    }
    
    return response()->json($output);
});
