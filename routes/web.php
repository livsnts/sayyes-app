<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// Route::verbo http(get, post, put, delete, patch)('/rota que vai ser no navegador', function () {
//  return view('nome da view que vai ser renderizada');
// }

Route::get('/', function () {
    return view('welcome');
})->name('inicio');

Route::get('/cadastro', function () {
    return view('cadastro');
})->name('cadastro');

Route::post('/cadastro', [UsuarioController::class, 'store'])->name('cadastro.store');

Route::get('/login', [UsuarioController::class, 'showLogin'])->name('login');

Route::post('/login', [UsuarioController::class, 'login'])->name('login.store');

Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

Route::get('/usuario/{id}', [UsuarioController::class, 'show']);


