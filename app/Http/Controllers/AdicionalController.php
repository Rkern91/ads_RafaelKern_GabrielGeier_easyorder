<?php

namespace App\Http\Controllers;

use App\Models\Adicional;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class AdicionalController extends Controller
{
  public function index(Request $request)
  {
    $q = trim($request->get('q', ''));
    $adicionais = Adicional::when($q, fn($s) => $s->where('nm_adicional', 'ilike', "%$q%"))
      ->orderBy('nm_adicional')
      ->paginate(10)
      ->withQueryString();

    return view('adicionais.index', compact('adicionais','q'));
  }

  public function create()
  {
    return view('adicionais.create');
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'nm_adicional' => ['required','max:50','unique:adicional,nm_adicional'],
      'vl_adicional' => ['required','numeric','min:0'],
      'ds_adicional' => ['nullable','max:255'],
    ]);

    Adicional::create($data);

    return redirect()->route('adicionais.index')->with('success', 'Adicional criado.');
  }

  public function edit(Adicional $adicionai)
  {
    return view('adicionais.edit', ['adicional' => $adicionai]);
  }

  public function update(Request $request, Adicional $adicionai)
  {
    $data = $request->validate([
      'nm_adicional' => ['required','max:50', Rule::unique('adicional','nm_adicional')->ignore($adicionai->cd_adicional,'cd_adicional')],
      'vl_adicional' => ['required','numeric','min:0'],
      'ds_adicional' => ['nullable','max:255'],
    ]);

    $adicionai->update($data);

    return redirect()->route('adicionais.index')->with('success', 'Adicional atualizado.');
  }

  public function destroy(Adicional $adicionai)
  {
    try {
      $adicionai->delete();
      return redirect()->route('adicionais.index')->with('success', 'Adicional excluído.');
    } catch (QueryException $e) {
      return redirect()->route('adicionais.index')->with('error', 'Não é possível excluir: adicional vinculado a pedidos.');
    }
  }
}