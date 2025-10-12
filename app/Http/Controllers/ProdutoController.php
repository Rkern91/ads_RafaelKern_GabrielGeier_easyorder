<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\ProdutoCategoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

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
    $data = $request->validate([
      'cd_categoria' => ['required','integer','exists:produto_categoria,cd_categoria'],
      'nm_produto' => ['required','max:50','unique:produto,nm_produto'],
      'vl_valor' => ['required','numeric','min:0'],
      'ds_produto' => ['nullable','max:100'],
    ]);

    Produto::create($data);

    return redirect()->route('produtos.index')->with('success', 'Produto criado.');
  }

  public function edit(Produto $produto)
  {
    $categorias = ProdutoCategoria::orderBy('nm_categoria')->get();
    return view('produtos.edit', compact('produto','categorias'));
  }

  public function update(Request $request, Produto $produto)
  {
    $data = $request->validate([
      'cd_categoria' => ['required','integer','exists:produto_categoria,cd_categoria'],
      'nm_produto' => ['required','max:50', Rule::unique('produto','nm_produto')->ignore($produto->cd_produto,'cd_produto')],
      'vl_valor' => ['required','numeric','min:0'],
      'ds_produto' => ['nullable','max:100'],
    ]);

    $produto->update($data);

    return redirect()->route('produtos.index')->with('success', 'Produto atualizado.');
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