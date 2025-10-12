<?php

namespace App\Http\Controllers;

use App\Models\ProdutoCategoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class ProdutoCategoriaController extends Controller
{
  public function index(Request $request)
  {
    $q = trim($request->get('q', ''));
    $categorias = ProdutoCategoria::when($q, fn($s) => $s->where('nm_categoria', 'ilike', "%$q%"))
      ->orderBy('nm_categoria')
      ->paginate(10)
      ->withQueryString();

    return view('categorias.index', compact('categorias', 'q'));
  }

  public function create()
  {
    return view('categorias.create');
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'nm_categoria' => ['required','max:50','unique:produto_categoria,nm_categoria'],
    ]);

    ProdutoCategoria::create($data);

    return redirect()->route('categorias.index')->with('success', 'Categoria criada.');
  }

  public function edit(ProdutoCategoria $categoria)
  {
    return view('categorias.edit', compact('categoria'));
  }

  public function update(Request $request, ProdutoCategoria $categoria)
  {
    $data = $request->validate([
      'nm_categoria' => ['required','max:50', Rule::unique('produto_categoria','nm_categoria')->ignore($categoria->cd_categoria,'cd_categoria')],
    ]);

    $categoria->update($data);

    return redirect()->route('categorias.index')->with('success', 'Categoria atualizada.');
  }

  public function destroy(ProdutoCategoria $categoria)
  {
    try {
      $categoria->delete();
      return redirect()->route('categorias.index')->with('success', 'Categoria excluída.');
    } catch (QueryException $e) {
      return redirect()->route('categorias.index')->with('error', 'Não é possível excluir: categoria vinculada a produtos.');
    }
  }
}