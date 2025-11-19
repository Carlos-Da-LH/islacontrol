<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Api\ScanController;


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

// Ruta para guardar una nueva venta
Route::post('/sales', [SaleController::class, 'store']);

// Ruta para la generación de la nota (ya la tenías)
Route::post('/sales/generate-note-for-create', [SaleController::class, 'generateNote']);

Route::post('/scan-product', [ScanController::class, 'scan']);
