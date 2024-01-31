<?php

use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/index', function () {
    return view('index');
})->name('index')->middleware('auth');

Route::get('/activar-usuario', function () {
    return view('activar-usuario');
})->name('activar-usuario');

Route::get('/verificarCodigo', function () {
    return view('verificarCodigo');
})->name('verificarCodigo');

