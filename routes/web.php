<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\ThirdPartyController;
use App\Http\Controllers\VoucherController;
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
    return view('auth.login');
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


    /** Profile Routes */
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    /** Role Route */
    Route::resource('role', RolePermissionController::class);
    /** Role Users Routes */
    Route::resource('role-user', RoleUserController::class);

    Route::resource('accounts', AccountController::class);
    Route::resource('currencies', CurrencyController::class);


    Route::resource('clients', ThirdPartyController::class);
    Route::resource('vouchers', VoucherController::class);
    Route::resource('receipts', ReceiptController::class);


    // Client Balance Report (Filter Form)
    Route::get('/reports/client-balance/filter', function () {
        return view('reports.filter');
    })->name('reports.filter');

    // Client Balance Report (Overall for all clients)
    Route::get('/reports/client-balance', [VoucherController::class, 'clientBalanceReport'])->name('reports.client_balance');

    // Specific Client Balance Report
    Route::get('/reports/client/{id}/balance', [VoucherController::class, 'clientSpecificReport'])->name('reports.client_specific');

    Route::get('/invoices/client/{id}', [InvoiceController::class, 'showInvoice'])->name('invoices.show');

});

require __DIR__ . '/auth.php';
