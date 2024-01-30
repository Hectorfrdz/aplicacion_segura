<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('/registro-usuario','register')->name('validar-registro');
    Route::post('/iniciar-sesion','login')->name('iniciar-sesion');
    Route::get('/logout','logout')->name('logout')->middleware('auth:sanctum');
    Route::get('/verificar-email','verificarEmail')->name('verificar-email');
    Route::post('/verificar-usuario','verificarUsuario')->name('verificar-usuario');
    Route::get('/reenviar-correo/{id}')->name('reenviar-correo');
});