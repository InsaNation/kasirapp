<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// === Auth ===

Route::get('/', function () {
    
    if (auth()->check()) {
        return redirect()->route('dashboard_admin');
    }
    return view('login');

})->name('login-page');

Route::post('/logins', [AuthController::class, 'login'])->name('login_users');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// === End Auth ===


// === Admin ===
Route::prefix('admin')->middleware('auth')->group(function () {
    
    Route::get('/dashboard', [CashierController::class, 'index_admin'])->name('dashboard_admin'); // open dashboard view
    Route::get('/laporan', [CashierController::class, 'laporan'])->name('laporan'); // open laporan view

    // === User ===

    Route::get('/data', [CashierController::class, 'data_admin'])->name('data_user'); // open data user view

    Route::get('/data_user/create', [CashierController::class, 'create_user'])->name('user.create'); // go to create user view

    Route::post('/user', [CashierController::class, 'store_user'])->name('user.store'); // store data user
    
    Route::put('/data/{id}', [CashierController::class, 'update_user'])->name('user.update'); // update data user

    Route::delete('/data/{id}', [CashierController::class, 'delete_user'])->name('user.delete'); // delete data user


    // === End User ===
    
    // === Item ===
    
    Route::get('/data_items', [CashierController::class, 'index_item'])->name('index_item'); // open data item view 
    
    Route::get('/data_items/create', [CashierController::class, 'create_item'])->name('item.create'); // go to create item view

    
    
    Route::post('/item', [CashierController::class, 'store_item'])->name('item.store'); // store data item

    Route::put('/data_items/update/{id}', [CashierController::class, 'update_item'])->name('item.update'); // update data item
    
    Route::delete('/data_items/delete/{id}', [CashierController::class, 'delete_item'])->name('item.delete'); // delete data item

    // === End Item ===
});
// === End Admin ===


Route::prefix('kasir')->middleware('auth')->group(function () {


    // Route::get('/dashboard', [CashierController::class, 'index_kasir'])->name('dashboard_kasir'); // open dashboard view

    // === Transaction ===

    Route::get('/transaksi', [CashierController::class, 'index_transaksi'])->name('index_transaksi'); // open transaksi view

    route::get('/transaksi/create', [CashierController::class, 'createInvoice'])->name('transaksi.create');

    Route::get('/transaksi/search-item', [CashierController::class, 'search_item'])->name('search_item'); // search item

    Route::post('/transaksi', [CashierController::class, 'store_transaksi'])->name('transaksi.store'); // store data transaksi

    Route::put('/transaksi/update/{id}', [CashierController::class, 'update_transaksi'])->name('transaksi.update'); // update data transaksi

    Route::delete('/transaksi/delete/{id}', [CashierController::class, 'delete_transaksi'])->name('transaksi.delete'); // delete data transaksi

    // === End Transaction ===

    // === Item ===

    // Route::get('/data_items', [CashierController::class, 'index_item'])->name('index_item'); // open data item view

    // === End Item ===
});

