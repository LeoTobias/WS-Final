<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FabricanteController;
use App\Http\Controllers\Api\ModeloController;
use App\Http\Controllers\Api\ProprietarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('fabricantes', FabricanteController::class);
Route::apiResource('modelos', ModeloController::class);
Route::apiResource('proprietarios', ProprietarioController::class);

