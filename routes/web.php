<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

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
    return response()->json(['success' => $success, 'message' => 'Welcome'], 200);
});

Route::get('/item/pendientes', [ItemController::class, 'getPendientes'])->name('item.pendientes');

Route::post('/item/create', [ItemController::class, 'create'])->name('item.create');

Route::put('/orden/marcarLista/{mesaID}', [ItemController::class, 'setOrdenLista'])->name('orden.marcarLista');

Route::put('/orden/entregar/{mesaID}', [ItemController::class, 'entregarOrden'])->name('orden.entregar');
