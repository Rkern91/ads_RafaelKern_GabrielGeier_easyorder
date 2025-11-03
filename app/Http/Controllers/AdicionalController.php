<?php

namespace App\Http\Controllers;

use App\Models\Adicional;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AdicionalController extends Controller
{
  public function index(Request $request)
  {
    $q = trim($request->get('q', ''));
    $adicionais = Adicional::when($q, fn($s) => $s->where('nm_adicional', 'ilike', "%$q%"))->orderBy('nm_adicional')->paginate(10)->withQueryString();
    return view('adicionais.index', compact('adicionais', 'q'));
  }

  public function create()
  {
    return view('adicionais.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'nm_adicional' => ['required', 'string', 'max:50'],
      'vl_adicional' => ['required', 'numeric', 'min:0'],
      'ds_adicional' => ['nullable', 'string', 'max:255'],
      'imagem' => ['nullable', 'image', 'max:4096'],
    ]);

    unset($validated['imagem']);

    $a = new Adicional($validated);

    if ($request->hasFile('imagem')) {
      $f = $request->file('imagem');
      $bin = file_get_contents($f->getRealPath());
      $a->img_b64 = base64_encode($bin);
      $a->img_mime = $f->getMimeType();
    }

    $a->save();

    return redirect()->route('adicionais.index')->with('success', 'Adicional criado.');
  }

  public function edit(Adicional $adicional)
  {
    return view('adicionais.edit', compact('adicional'));
  }

  public function update(Request $request, Adicional $adicional)
  {
    $validated = $request->validate([
      'nm_adicional' => ['required', 'string', 'max:50'],
      'vl_adicional' => ['required', 'numeric', 'min:0'],
      'ds_adicional' => ['nullable', 'string', 'max:255'],
      'imagem' => ['nullable', 'image', 'max:4096'],
      'remover_imagem' => ['nullable', 'boolean'],
    ]);

    $remover = (bool)($validated['remover_imagem'] ?? false);
    unset($validated['imagem'], $validated['remover_imagem']);

    $adicional->fill($validated);

    if ($remover) {
      $adicional->img_b64 = null;
      $adicional->img_mime = null;
    } elseif ($request->hasFile('imagem')) {
      $f = $request->file('imagem');
      $bin = file_get_contents($f->getRealPath());
      $adicional->img_b64 = base64_encode($bin);
      $adicional->img_mime = $f->getMimeType();
    }

    $adicional->save();

    return redirect()->route('adicionais.index')->with('success', 'Adicional atualizado.');
  }

  public function destroy(Adicional $adicional)
  {
    try {
      $adicional->delete();
      return redirect()->route('adicionais.index')->with('success', 'Adicional excluído.');
    } catch (QueryException $e) {
      return redirect()->route('adicionais.index')->with('error', 'Não é possível excluir.');
    }
  }

  public function imagem(Adicional $adicional)
  {
    abort_unless($adicional->img_b64, 404);
    $bytes = base64_decode($adicional->img_b64, true);
    abort_unless($bytes !== false, 404);

    return response($bytes)->header('Content-Type', $adicional->img_mime ?: 'image/png')->header('Content-Length', strlen($bytes))->header('Cache-Control', 'public, max-age=86400');
  }
}