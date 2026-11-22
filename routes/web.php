<?php

use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\DanoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\RevisaoController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AutenticacaoController::class, 'showLogin'])->name('login');
    Route::post('/login', [AutenticacaoController::class, 'login']);
    Route::get('/register', [AutenticacaoController::class, 'showRegister'])->name('register');
    Route::post('/register', [AutenticacaoController::class, 'register']);
});

Route::middleware('auth')->group(function (): void {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index']);

    Route::post('/logout', [AutenticacaoController::class, 'logout'])->name('logout');

    Route::get('/veiculos', [VeiculoController::class, 'index'])->name('veiculos.index');
    Route::get('/veiculos/criar', [VeiculoController::class, 'create'])->name('veiculos.create');
    Route::post('/veiculos', [VeiculoController::class, 'store'])->name('veiculos.store');
    Route::get('/veiculos/{id}', [VeiculoController::class, 'show'])->name('veiculos.show');
    Route::get('/veiculos/{id}/editar', [VeiculoController::class, 'edit'])->name('veiculos.edit');
    Route::post('/veiculos/{id}', [VeiculoController::class, 'update'])->name('veiculos.update');
    Route::post('/veiculos/{id}/status', [VeiculoController::class, 'updateStatus'])->name('veiculos.status');
    Route::delete('/veiculos/{id}', [VeiculoController::class, 'destroy'])->name('veiculos.destroy');

    Route::get('/veiculos/{id}/revisoes', [RevisaoController::class, 'history'])->name('revisoes.history');
    Route::get('/revisoes/criar', [RevisaoController::class, 'create'])->name('revisoes.create');
    Route::post('/revisoes', [RevisaoController::class, 'store'])->name('revisoes.store');

    Route::get('/veiculos/{id}/danos', [DanoController::class, 'index'])->name('danos.index');
    Route::get('/danos/criar', [DanoController::class, 'create'])->name('danos.create');
    Route::post('/danos', [DanoController::class, 'store'])->name('danos.store');

    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('/relatorios/frota', [RelatorioController::class, 'frota'])->name('relatorios.frota');
    Route::get('/relatorios/revisoes', [RelatorioController::class, 'revisoes'])->name('relatorios.revisoes');
    Route::get('/relatorios/danos', [RelatorioController::class, 'danos'])->name('relatorios.danos');
    Route::get('/relatorios/veiculo', [RelatorioController::class, 'veiculo'])->name('relatorios.veiculo');
});
