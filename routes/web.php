<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'register' => false,
]);

Route::get('/', function () {
    return redirect('home');
})->middleware('auth');

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('users', UserController::class);
Route::resource('members', MemberController::class);

// Session Cart
Route::get('/carts', [CartController::class, 'index'])->name('carts.index');
Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('addToCart');
Route::put('update-cart', [CartController::class, 'updateCart'])->name('updateCart');
Route::delete('remove-cart', [CartController::class, 'removeCart'])->name('removeCart');
// End Cart

Route::resource('transactions', TransactionController::class)->only([
    'create', 'index', 'show', 'store'
]);
Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');


Route::get('/daily-report', [ReportController::class, 'daily'])->name('dailyReport');
Route::get('/api/daily-report', [ReportController::class, 'apiDaily'])->name('apiDailyReport');

Route::get('/monthly-report', [ReportController::class, 'monthly'])->name('monthlyReport');
Route::get('/api/monthly-report', [ReportController::class, 'apiMonthly'])->name('apiMonthlyReport');
