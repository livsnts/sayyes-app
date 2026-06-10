<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// Route::verbo http(get, post, put, delete, patch)('/rota que vai ser no navegador', function () {
//  return view('nome da view que vai ser renderizada');
// }

Route::get('/inicio', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/cadastro', function () {
    return view('cadastro');
});

Route::post('/cadastro', [UsuarioController::class, 'store'])->name('cadastro.store');

Route::get('/', [UsuarioController::class, 'index']);

Route::get('/usuario/{id}', [UsuarioController::class, 'show']);


