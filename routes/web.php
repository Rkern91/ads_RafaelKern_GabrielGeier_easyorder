<?php

use App\Http\Controllers\MesaController;
use App\Http\Controllers\ProdutoCategoriaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
  
  Route::get('/', function () {
    return view('welcome');
  });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::resource('mesas', MesaController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
  Route::resource('categorias', ProdutoCategoriaController::class)->parameters([
    'categorias' => 'categoria'
  ])->except(['show']);
});

Route::middleware('auth')->group(function () {
  Route::resource('produtos', \App\Http\Controllers\ProdutoController::class)->parameters([
    'produtos' => 'produto'
  ])->except(['show']);
});

Route::middleware('auth')->group(function () {
  Route::resource('adicionais', \App\Http\Controllers\AdicionalController::class)
    ->parameters(['adicionais' => 'adicional'])
    ->except(['show']);
});

Route::middleware('auth')->group(function () {
  Route::get('/endereco', [\App\Http\Controllers\EnderecoController::class, 'edit'])->name('endereco.edit');
  Route::post('/endereco', [\App\Http\Controllers\EnderecoController::class, 'save'])->name('endereco.save');
});

Route::middleware('auth')->group(function () {
  Route::resource('usuarios', \App\Http\Controllers\UsuarioController::class)->parameters([
    'usuarios' => 'usuario'
  ])->except(['show']);
});

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/_dbcheck', function () {
  return response()->json(config('database.connections.pgsql'));
});

Route::get('/_pingdb', function () {
  try {
    return DB::select('select version()');
  } catch (\Throwable $e) {
    return response($e->getMessage(), 500);
  }
});
require __DIR__.'/auth.php';
