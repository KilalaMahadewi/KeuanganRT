<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WargaController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\PengeluaranController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/warga', [WargaController::class, 'index']);
Route::post('/warga', [WargaController::class, 'store']);

Route::get('/iuran', [IuranController::class, 'index']);
Route::get('/iuran/belum-bayar', [IuranController::class, 'wargaBelumBayar']);
Route::post('/iuran', [IuranController::class, 'store']);

Route::get('/pengeluaran', [PengeluaranController::class, 'index']);
Route::post('/pengeluaran', [PengeluaranController::class, 'store']);

