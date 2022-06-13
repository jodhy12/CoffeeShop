<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
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

Auth::routes();

Route::get('/', function () {
    return redirect('home');
})->middleware('auth');

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::resource('categories', CategoryController::class)->except(['show']);
Route::resource('products', ProductController::class)->except(['show']);
Route::resource('users', UserController::class)->except(['show']);
Route::resource('members', MemberController::class)->except(['show']);

Route::get('/carts', [CartController::class, 'index'])->name('carts.index');
Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('addToCart');
Route::put('update-cart', [CartController::class, 'updateCart'])->name('updateCart');
Route::delete('remove-cart', [CartController::class, 'removeCart'])->name('removeCart');

Route::resource('transactions', TransactionController::class)->only([
    'create', 'index', 'show', 'store'
]);

Route::get('/upload-file', [ProductController::class, 'uploadFile'])->name('uploadFile');
