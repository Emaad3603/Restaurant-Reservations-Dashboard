<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\MealTypeController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RestaurantPricingTimeController;
use App\Http\Controllers\Admin\BoardTypeController;
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
        Route::get('test-priv', function() {
            return 'Privilege middleware works!';
        })->middleware('adminpriv:hotels_tab');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Resource Routes with privilege checks
        Route::middleware('adminpriv:hotels_tab')->resource('hotels', HotelController::class);
        Route::middleware('adminpriv:restaurants_tab')->resource('restaurants', RestaurantController::class);
        Route::middleware('adminpriv:meal_types_tab')->resource('meal-types', MealTypeController::class);
        Route::middleware('adminpriv:reservations_tab')->resource('reservations', ReservationController::class);
        
        // Menu Management Routes (assume restaurants_tab privilege)
        Route::prefix('restaurants/{restaurant}/menu')->middleware('adminpriv:restaurants_tab')->name('restaurants.menu.')->group(function () {
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
        
        // Reports (reports_tab privilege)
        Route::middleware('adminpriv:reports_tab')->group(function () {
            Route::get('reports/reservations', [ReportController::class, 'reservations'])->name('reports.reservations');
            Route::get('reports/statistics', [ReportController::class, 'statistics'])->name('reports.statistics');
        });

        // Restaurant Pricing Times Routes (restaurant_times_tab privilege)
        Route::prefix('restaurants/{restaurant}/pricing-times')->middleware('adminpriv:restaurant_times_tab')->name('restaurants.pricing-times.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\RestaurantPricingTimeController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\RestaurantPricingTimeController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\RestaurantPricingTimeController::class, 'store'])->name('store');
            Route::get('/{pricingTime}', [\App\Http\Controllers\Admin\RestaurantPricingTimeController::class, 'show'])->name('show');
            Route::get('/{pricingTime}/edit', [\App\Http\Controllers\Admin\RestaurantPricingTimeController::class, 'edit'])->name('edit');
            Route::put('/{pricingTime}', [\App\Http\Controllers\Admin\RestaurantPricingTimeController::class, 'update'])->name('update');
            Route::delete('/{pricingTime}', [\App\Http\Controllers\Admin\RestaurantPricingTimeController::class, 'destroy'])->name('destroy');
            Route::get('/{pricingTime}/menu', [\App\Http\Controllers\Admin\RestaurantPricingTimeController::class, 'menu'])->name('menu');
        });

        // Reservation Confirmation Routes
        Route::post('reservations/{reservation}/confirm', [\App\Http\Controllers\Admin\ReservationController::class, 'confirm'])->name('reservations.confirm');
        Route::post('reservations/{reservation}/cancel', [\App\Http\Controllers\Admin\ReservationController::class, 'cancel'])->name('reservations.cancel');

        // User Management (SuperAdmin only)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\AdminUserController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\AdminUserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [\App\Http\Controllers\AdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('destroy');
        });

        // Board Types
        Route::resource('board-types', BoardTypeController::class);
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin/menu')->middleware('auth:admin')->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('admin.menu.index');

    // Menus
    Route::post('/menu', [MenuController::class, 'storeMenu'])->name('admin.menu.store');
    Route::put('/menu/{menu}', [MenuController::class, 'updateMenu'])->name('admin.menu.update');
    Route::delete('/menu/{menu}', [MenuController::class, 'deleteMenu'])->name('admin.menu.delete');

    // Categories
    Route::post('/category', [MenuController::class, 'storeCategory'])->name('admin.menu.category.store');
    Route::put('/category/{category}', [MenuController::class, 'updateCategory'])->name('admin.menu.category.update');
    Route::delete('/category/{category}', [MenuController::class, 'deleteCategory'])->name('admin.menu.category.delete');

    // Subcategories
    Route::post('/subcategory', [MenuController::class, 'storeSubcategory'])->name('admin.menu.subcategory.store');
    Route::put('/subcategory/{subcategory}', [MenuController::class, 'updateSubcategory'])->name('admin.menu.subcategory.update');
    Route::delete('/subcategory/{subcategory}', [MenuController::class, 'deleteSubcategory'])->name('admin.menu.subcategory.delete');

    // Items
    Route::post('/item', [MenuController::class, 'storeItem'])->name('admin.menu.item.store');
    Route::put('/item/{item}', [MenuController::class, 'updateItem'])->name('admin.menu.item.update');
    Route::delete('/item/{item}', [MenuController::class, 'deleteItem'])->name('admin.menu.item.delete');
});

Route::get('admin/menus/manage', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('admin.menus.manage');
