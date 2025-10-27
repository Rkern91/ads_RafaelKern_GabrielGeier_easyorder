<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\ProdutoCategoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ProdutoController extends Controller
{
  public function index(Request $request)
  {
    $q = trim($request->get('q', ''));
    $cat = $request->integer('cd_categoria');
    $categorias = ProdutoCategoria::orderBy('nm_categoria')->get();
    $produtos = Produto::with('categoria')
      ->when($q, fn($s) => $s->where('nm_produto', 'ilike', "%$q%"))
      ->when($cat, fn($s) => $s->where('cd_categoria', $cat))
      ->orderBy('nm_produto')
      ->paginate(10)
      ->withQueryString();

    return view('produtos.index', compact('produtos','categorias','q','cat'));
  }

  public function create()
  {
    $categorias = ProdutoCategoria::orderBy('nm_categoria')->get();
    return view('produtos.create', compact('categorias'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'cd_categoria' => ['required','integer','exists:produto_categoria,cd_categoria'],
      'nm_produto'   => ['required','string','max:50'],
      'vl_valor'     => ['required','numeric','min:0'],
      'ds_produto'   => ['nullable','string','max:100'],
      'imagem'       => ['nullable','image','max:4096'],
    ]);

    unset($validated['imagem']);

    $produto = new \App\Models\Produto($validated);
    $produto->cd_usuario_cadastro = auth()->id();

    if ($request->hasFile('imagem')) {
      $f = $request->file('imagem');
      $bin = file_get_contents($f->getRealPath());
      $produto->img_b64  = base64_encode($bin);
      $produto->img_mime = $f->getMimeType();
    }

    $produto->save();

    return redirect()->route('produtos.index')->with('success', 'Produto criado.');
  }

  public function update(Request $request, \App\Models\Produto $produto)
  {
    $validated = $request->validate([
      'cd_categoria'   => ['required','integer','exists:produto_categoria,cd_categoria'],
      'nm_produto'     => ['required','string','max:50'],
      'vl_valor'       => ['required','numeric','min:0'],
      'ds_produto'     => ['nullable','string','max:100'],
      'imagem'         => ['nullable','image','max:4096'],
      'remover_imagem' => ['nullable','boolean'],
    ]);

    $remover = (bool)($validated['remover_imagem'] ?? false);
    unset($validated['imagem'], $validated['remover_imagem']);

    $produto->fill($validated);

    if ($remover) {
      $produto->img_b64  = null;
      $produto->img_mime = null;
    } elseif ($request->hasFile('imagem')) {
      $f = $request->file('imagem');
      $bin = file_get_contents($f->getRealPath());
      $produto->img_b64  = base64_encode($bin);
      $produto->img_mime = $f->getMimeType();
    }

    $produto->save();

    return redirect()->route('produtos.index')->with('success', 'Produto atualizado.');
  }

  public function imagem(\App\Models\Produto $produto)
  {
    abort_unless($produto->img_b64, 404);
    $bytes = base64_decode($produto->img_b64, true);
    abort_unless($bytes !== false, 404);

    return response($bytes)
      ->header('Content-Type', $produto->img_mime ?: 'image/png')
      ->header('Content-Length', strlen($bytes))
      ->header('Cache-Control', 'public, max-age=86400');
  }

  public function edit(Produto $produto)
  {
    $categorias = ProdutoCategoria::orderBy('nm_categoria')->get();
    return view('produtos.edit', compact('produto','categorias'));
  }

  public function destroy(Produto $produto)
  {
    try {
      $produto->delete();
      return redirect()->route('produtos.index')->with('success', 'Produto excluído.');
    } catch (QueryException $e) {
      return redirect()->route('produtos.index')->with('error', 'Não é possível excluir: produto vinculado a pedidos.');
    }
  }
}