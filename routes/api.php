<?php

use App\Http\Controllers\AuthApiController;
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


Route::post('/login', [AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->get('/exit', [AuthApiController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/verificarCodigo', [AuthApiController::class, 'verificarCodigo']);


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
    Route::get('read-authors','readAuthors')->name('read-authors')->middleware("checkRole:1,2,3");
    Route::get('read-author/{id}','readAuthor')->name('read-author')->where('id', '[0-9]+')->middleware("checkRole:1,2");
    Route::post('create-author','createAuthor')->name('create-author')->middleware("checkRole:1,2");
    Route::put('update-author/{id}','updateAuthor')->name('update-author')->where('id', '[0-9]+')->middleware("checkRole:1,2");
    Route::delete('delete-author/{id}','deleteAuthor')->name('delete-author')->where('id', '[0-9]+')->middleware("checkRole:1,2");
})->middleware('auth:sanctum')->middleware("ips");

Route::controller(GenreController::class)->group(function(){
    Route::get('read-genres','readGenres')->name('read-genres')->middleware("checkRole:1,2,3");
    Route::get('read-genre/{id}','readGenre')->name('read-genre')->where('id', '[0-9]+')->middleware("checkRole:1,2");
    Route::post('create-genre','createGenre')->name('create-genre')->middleware("checkRole:1,2");
    Route::put('update-genre/{id}','updateGenre')->name('update-genre')->where('id', '[0-9]+')->middleware("checkRole:1,2");
    Route::delete('delete-genre/{id}','deleteGenre')->name('delete-genre')->where('id', '[0-9]+')->middleware("checkRole:1,2");
})->middleware('auth:sanctum')->middleware("ips");

Route::controller(EditorialController::class)->group(function(){
    Route::get('read-editorials','readEditorials')->name('read-editorials')->middleware("checkRole:1,2,3");
    Route::get('read-editorial/{id}','readEditorial')->name('read-editorial')->where('id', '[0-9]+')->middleware("checkRole:1,2");
    Route::post('create-editorial','createEditorial')->name('create-editorial')->middleware("checkRole:1,2");
    Route::put('update-editorial/{id}','updateEditorial')->name('update-editorial')->where('id', '[0-9]+')->middleware("checkRole:1,2");
    Route::delete('delete-editorial/{id}','deleteEditorial')->name('delete-editorial')->where('id', '[0-9]+')->middleware("checkRole:1,2");
})->middleware('auth:sanctum')->middleware("ips");

Route::controller(BookController::class)->group(function(){
    Route::get('read-books','readBooks')->name('read-books')->middleware("checkRole:1,2,3");
    Route::get('read-book/{id}','readBook')->name('read-book')->where('id', '[0-9]+')->middleware("checkRole:1,2");
    Route::post('create-book','createBook')->name('create-book')->middleware("checkRole:1,2");
    Route::put('update-book/{id}','updateBook')->name('update-book')->where('id', '[0-9]+')->middleware("checkRole:1,2");
    Route::delete('delete-book/{id}','deleteBook')->name('delete-book')->where('id', '[0-9]+')->middleware("checkRole:1,2");
})->middleware('auth:sanctum')->middleware("ips");

Route::controller(UserController::class)->group(function(){
    Route::get('read-users','readUsers')->name('read-users')->middleware("checkRole:1");
    Route::get('read-user/{id}','readUser')->name('read-user')->where('id', '[0-9]+')->middleware("checkRole:1");
    Route::post('create-user','createUser')->name('create-user')->middleware("checkRole:1");
    Route::put('update-user/{id}','updateUser')->name('update-user')->where('id', '[0-9]+')->middleware("checkRole:1");
    Route::delete('delete-user/{id}','deleteUser')->name('delete-user')->where('id', '[0-9]+')->middleware("checkRole:1");
})->middleware('auth:sanctum')->middleware("ips");
