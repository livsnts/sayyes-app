<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CasamentoController;

Route::get('/', function () {
    return view('inicio');
})->name('inicio');

Route::get('/cadastro', [UsuarioController::class, 'create'])->name('cadastro');
Route::post('/cadastro', [UsuarioController::class, 'store'])->name('cadastro.store');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/inicio', function () {
        return view('inicio');
    })->name('dashboard');
    Route::get('/perfil', [UsuarioController::class, 'edit'])->name('perfil.edit');
    Route::post('/perfil', [UsuarioController::class, 'update'])->name('perfil.update');

    // ---- Casamento ----
    Route::get('/casamento/criar', [CasamentoController::class, 'create'])->name('casamento.create');
    Route::post('/casamento', [CasamentoController::class, 'store'])->name('casamento.store');
    Route::get('/casamento/{casamento}', [CasamentoController::class, 'show'])->name('casamento.show');

    Route::get('/casamento/{casamento}/editar', [CasamentoController::class, 'edit'])->name('casamento.edit');
    Route::put('/casamento/{casamento}', [CasamentoController::class, 'update'])->name('casamento.update');

        // ++++ Adicionar usuário ++++
    Route::get('/casamento/{casamento}/equipe', [CasamentoController::class, 'equipe'])->name('casamento.equipe');
    Route::get('/casamento/{casamento}/buscar-usuario', [CasamentoController::class, 'buscarUsuario'])->name('casamento.buscar-usuario');
    Route::post('/casamento/{casamento}/adicionar-membro', [CasamentoController::class, 'adicionarMembro'])->name('casamento.adicionar-membro');
});
