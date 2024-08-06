<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** Dashboard Routes */

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /** Clients Routes */
    Route::resource('clients', ClientController::class);

    /** Suppliers Routes */
    Route::resource('suppliers', SupplierController::class);

    /** Orders Routes */
    Route::resource('orders', OrderController::class);

    /** Inventory Routes */
    Route::get('/inventories/{id}/add-fuel', [InventoryController::class, 'showAddFuelForm'])->name('inventories.addFuel');
    Route::post('/inventories/{id}/add-fuel', [InventoryController::class, 'addFuel'])->name('inventories.addFuel.store');
    Route::resource('inventories', InventoryController::class);

    /** Reports Routes */

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate-invoice/{order}', [ReportController::class, 'generateInvoice'])->name('reports.generate-invoice');


    /** Profile Routes */
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

require __DIR__ . '/auth.php';
