<?php

use App\Http\Controllers\CardapioController;
use App\Http\Controllers\CozinhaController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\ProdutoCategoriaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PagamentoController;
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
  Route::resource('produtos', ProdutoController::class)->parameters([
    'produtos' => 'produto'
  ])->except(['show']);
});

Route::resource('produtos', ProdutoController::class);
Route::get('produtos/{produto}/imagem', [ProdutoController::class, 'imagem'])->name('produtos.imagem');

Route::middleware('auth')->group(function () {
  Route::resource('adicionais', \App\Http\Controllers\AdicionalController::class)
    ->parameters(['adicionais' => 'adicional'])
    ->except(['show']);
});

Route::resource('adicionais', \App\Http\Controllers\AdicionalController::class);
Route::get('adicionais/{adicional}/imagem', [\App\Http\Controllers\AdicionalController::class, 'imagem'])->name('adicionais.imagem');

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

Route::get('/cardapio',                      [CardapioController::class, 'obterCardapioCompleto']) ->name('cardapio.index');
Route::get('/cardapio/categoria/{categoria}',[CardapioController::class, 'obterCardapioCategoria'])->name('cardapio.categoria');
Route::get('/cardapio/adicional/{produto}',  [CardapioController::class, 'obterAdicionalProduto']) ->name('cardapio.produto.adicional');
Route::post('/cardapio/adicionar/{produto}', [CardapioController::class, 'adicionarItemCarrinho']) ->name('cardapio.carrinho.adicionar');
Route::get('/cardapio/revisao',              [CardapioController::class, 'revisao'])               ->name('cardapio.revisao');
Route::get('/cardapio/conta',                [CardapioController::class, 'conta'])                 ->name('cardapio.conta');

Route::post('/cardapio/confirmar',           [CardapioController::class, 'confirmar'])             ->name('cardapio.confirmar');
Route::post('/cardapio/finalizar',           [CardapioController::class, 'finalizar'])             ->name('pedido.finalizar');


Route::middleware(['auth'])->group(function () {
  Route::get('/cozinha', [CozinhaController::class, 'index'])->name('cozinha.index');
  Route::post('/cozinha/{pedido}/preparar', [CozinhaController::class, 'preparar'])->name('cozinha.preparar');
  Route::post('/cozinha/{pedido}/servir', [CozinhaController::class, 'servir'])->name('cozinha.servir');
});

Route::get('/pagamento/{pedido}', [PagamentoController::class,'show'])->name('pagamento.show');
Route::post('/pagamento/{pedido}/simular', [PagamentoController::class,'simular'])->name('pagamento.simular'); // sandbox
Route::get('/pagamento/{pedido}/status', [PagamentoController::class,'status'])->name('pagamento.status');

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
require __DIR__ . '/auth.php';
