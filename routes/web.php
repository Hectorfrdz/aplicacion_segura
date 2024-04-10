<?php

use App\Http\Controllers\UserController;
use App\Models\Author;
use App\Models\Book;
use App\Models\Editorial;
use App\Models\Genre;
use App\Models\User;
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

Route::get('/books', function () {
    $books = Book::with('genre', 'editorial', 'author')->simplePaginate(10);
    $authors = Author::all();
    $editorials = Editorial::all();
    $genres = Genre::all();
    return view('books', compact('books','authors','editorials','genres'));
})->name('books')->middleware('auth:sanctum')->middleware("ips");

Route::get('/users', function () {
    $users = User::all();
    return view('users', compact('users'));
})->name('users')->middleware('auth:sanctum')->middleware("checkRole:1")->middleware("ips");

Route::get('/authors', function () {
    $authors = Author::simplePaginate(10);
    return view('authors', compact('authors'));
})->name('authors')->middleware('auth:sanctum')->middleware("ips");

Route::get('/genres', function () {
    $genres = Genre::simplePaginate(10);
    return view('genres', compact('genres'));
})->name('genres')->middleware('auth:sanctum')->middleware("ips");

Route::get('/editorials', function () {
    $editorials = Editorial::simplePaginate(10);
    return view('editorials', compact('editorials'));
})->name('editorials')->middleware('auth:sanctum')->middleware("ips");
