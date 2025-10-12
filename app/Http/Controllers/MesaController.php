<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MesaController extends Controller
{
  public function index(Request $request)
  {
    $q = trim($request->get('q', ''));
    $mesas = Mesa::when($q, fn($s) => $s->where('nm_mesa', 'ilike', "%$q%"))
      ->orderBy('nm_mesa')
      ->paginate(10)
      ->withQueryString();

    return view('mesas.index', compact('mesas', 'q'));
  }

  public function create()
  {
    return view('mesas.create');
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'nm_mesa' => ['required','max:25','unique:mesa,nm_mesa'],
    ]);

    Mesa::create($data);

    return redirect()->route('mesas.index')->with('success', 'Mesa criada.');
  }

  public function edit(Mesa $mesa)
  {
    return view('mesas.edit', compact('mesa'));
  }

  public function update(Request $request, Mesa $mesa)
  {
    $data = $request->validate([
      'nm_mesa' => ['required','max:25', Rule::unique('mesa','nm_mesa')->ignore($mesa->cd_mesa,'cd_mesa')],
    ]);

    $mesa->update($data);

    return redirect()->route('mesas.index')->with('success', 'Mesa atualizada.');
  }

  public function destroy(Mesa $mesa)
  {
    $mesa->delete();

    return redirect()->route('mesas.index')->with('success', 'Mesa excluída.');
  }
}