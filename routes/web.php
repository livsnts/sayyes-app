<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CasamentoController;
use App\Http\Controllers\ConvidadoController;
use App\Http\Controllers\PasswordResetController;

Route::get('/', function () {
    return view('inicio');
})->name('inicio');

Route::get('/cadastro', [UsuarioController::class, 'create'])->name('cadastro');
Route::post('/cadastro', [UsuarioController::class, 'store'])->name('cadastro.store');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

// ----- Redefinição de senha -----
Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->middleware('guest')->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/inicio', function () {
        return view('inicio');
    })->name('dashboard');

    Route::get('/perfil', [UsuarioController::class, 'edit'])->name('perfil.edit');
    Route::post('/perfil', [UsuarioController::class, 'update'])->name('perfil.update');

    // ---- Casamentos ----
    Route::get('/casamentos', [CasamentoController::class, 'index'])->name('casamento.index');
    Route::get('/casamentos/criar', [CasamentoController::class, 'create'])->name('casamento.create');
    Route::post('/casamentos', [CasamentoController::class, 'store'])->name('casamento.store');
    Route::get('/casamentos/{casamento}', [CasamentoController::class, 'show'])->name('casamento.show');
    Route::get('/casamentos/{casamento}/editar', [CasamentoController::class, 'edit'])->name('casamento.edit');
    Route::put('/casamentos/{casamento}', [CasamentoController::class, 'update'])->name('casamento.update');
    Route::get('/casamentos/{casamento}/equipe', [CasamentoController::class, 'equipe'])->name('casamento.equipe');
    Route::get('/casamentos/{casamento}/buscar-usuario', [CasamentoController::class, 'buscarUsuario'])->name('casamento.buscar-usuario');
    Route::post('/casamentos/{casamento}/adicionar-membro', [CasamentoController::class, 'adicionarMembro'])->name('casamento.adicionar-membro');

    // ---- Convidados ----
    Route::get('/casamentos/{casamento}/convidados', [ConvidadoController::class, 'index'])->name('convidado.index');
    Route::get('/casamentos/{casamento}/convidados/criar', [ConvidadoController::class, 'create'])->name('convidado.create');
    Route::post('/casamentos/{casamento}/convidados', [ConvidadoController::class, 'store'])->name('convidado.store');
    Route::post('/casamentos/{casamento}/convidados/importar', [ConvidadoController::class, 'importar'])->name('convidado.importar');
    Route::get('/casamentos/{casamento}/convidados/{convidado}/editar', [ConvidadoController::class, 'edit'])->name('convidado.edit');
    Route::put('/casamentos/{casamento}/convidados/{convidado}', [ConvidadoController::class, 'update'])->name('convidado.update');
    Route::delete('/casamentos/{casamento}/convidados/{convidado}', [ConvidadoController::class, 'destroy'])->name('convidado.destroy');
});

// ------ confirmação de presença via token --------
Route::get('/confirmar/{token}', [ConvidadoController::class, 'confirmar'])->name('convidado.confirmar');
Route::post('/confirmar/{token}', [ConvidadoController::class, 'salvarConfirmacao'])->name('convidado.salvar-confirmacao');
