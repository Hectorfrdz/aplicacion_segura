<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('/registro-usuario','register')->name('validar-registro');
    Route::post('/iniciar-sesion','login')->name('iniciar-sesion');
    Route::get('/logout','logout')->name('logout')->middleware('auth:sanctum');
    Route::get('/verificar-email','verificarEmail')->name('verificar-email');
    Route::post('/verificar-usuario','verificarUsuario')->name('verificar-usuario');
    Route::post('/codigo-admin','codigoAdmin')->name('codigo-admin');
    Route::get('/reenviar-correo/{id}')->name('reenviar-correo');
    Route::post('/reenviar-codigo/{id}','reenviarCodigo')->name('reenviar-codigo')->where('id', '[0-9]+');
});

Route::controller(AuthorController::class)->group(function(){
    Route::get('read-authors','readAuthors')->name('read-authors');
    Route::get('read-author/{id}','readAuthor')->name('read-author');
    Route::post('create-author','createAuthor')->name('create-author');
    Route::put('update-author/{id}','updateAuthor')->name('update-author');
    Route::delete('delete-author/{id}','deleteAuthor')->name('delete-author');
});

Route::controller(GenreController::class)->group(function(){
    Route::get('read-genres','readGenres')->name('read-genres');
    Route::get('read-genre/{id}','readGenre')->name('read-genre');
    Route::post('create-genre','createGenre')->name('create-genre');
    Route::put('update-genre/{id}','updateGenre')->name('update-genre');
    Route::delete('delete-genre/{id}','deleteGenre')->name('delete-genre');
});

Route::controller(EditorialController::class)->group(function(){
    Route::get('read-editorials','readEditorials')->name('read-editorials');
    Route::get('read-editorial/{id}','readEditorial')->name('read-editorial');
    Route::post('create-editorial','createEditorial')->name('create-editorial');
    Route::put('update-editorial/{id}','updateEditorial')->name('update-editorial');
    Route::delete('delete-editorial/{id}','deleteEditorial')->name('delete-editorial');
});

Route::controller(BookController::class)->group(function(){
    Route::get('read-books','readBooks')->name('read-books');
    Route::get('read-book/{id}','readBook')->name('read-book');
    Route::post('create-book','createBook')->name('create-book');
    Route::put('update-book/{id}','updateBook')->name('update-book');
    Route::delete('delete-book/{id}','deleteBook')->name('delete-book');
});

Route::controller(UserController::class)->group(function(){
    Route::get('read-users','readUsers')->name('read-users');
    Route::get('read-user/{id}','readUser')->name('read-user');
    // Route::post('create-book','createBook')->name('create-book');
    // Route::put('update-book/{id}','updateBook')->name('update-book');
    // Route::delete('delete-book/{id}','deleteBook')->name('delete-book');
});
// ->middleware('auth:sanctum')->middleware("checkRole:1,2");0.
