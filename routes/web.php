<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PedidoController;

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

Route::get('/pedido/pendientes', [PedidoController::class, 'getPendientes'])->name('pedido.pendientes');

Route::post('/pedido/create', [PedidoController::class, 'create'])->name('pedido.create');

Route::put('/pedido/marcarListo/{mesaID}', [PedidoController::class, 'setOrdenLista'])->name('pedido.marcarListo');
