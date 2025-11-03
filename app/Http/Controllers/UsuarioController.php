<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
  public function index(Request $request)
  {
    $q = trim($request->get('q', ''));
    $cargo = $request->get('ds_cargo', '');

    $usuarios = Usuario::when($q, function ($s) use ($q) {
      $like = "%$q%";
      $s->where(function ($w) use ($like) {
        $w->where('nm_pessoa', 'ilike', $like)
          ->orWhere('ds_apelido', 'ilike', $like)
          ->orWhere('ds_email', 'ilike', $like)
          ->orWhere('nr_cpf_cnpj', 'ilike', $like);
      });
    })
      ->when($cargo !== '', fn($s) => $s->where('ds_cargo', $cargo))
      ->orderBy('nm_pessoa')
      ->paginate(10)
      ->withQueryString();

    return view('usuarios.index', compact('usuarios', 'q', 'cargo'));
  }

  public function create()
  {
    $cargos = ['cozinha' => 'Cozinha', 'adm' => 'ADM', 'caixa' => 'Caixa'];
    return view('usuarios.create', compact('cargos'));
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'nm_pessoa' => ['required', 'max:100'],
      'ds_apelido' => ['required', 'max:30', 'unique:usuario,ds_apelido'],
      'nr_cpf_cnpj' => ['required', 'max:14'],
      'ds_email' => ['required', 'email', 'max:30', 'unique:usuario,ds_email'],
      'ds_senha' => ['required', 'max:20'],
      'ds_cargo' => ['required', Rule::in(['cozinha', 'adm', 'caixa'])],
    ]);

    Usuario::create($data);

    return redirect()->route('usuarios.index')->with('success', 'Usuário criado.');
  }

  public function edit(Usuario $usuario)
  {
    $cargos = ['cozinha' => 'Cozinha', 'adm' => 'ADM', 'caixa' => 'Caixa'];
    return view('usuarios.edit', compact('usuario', 'cargos'));
  }

  public function update(Request $request, Usuario $usuario)
  {
    $data = $request->validate([
      'nm_pessoa' => ['required', 'max:100'],
      'ds_apelido' => ['required', 'max:30', Rule::unique('usuario', 'ds_apelido')->ignore($usuario->cd_pessoa, 'cd_pessoa')],
      'nr_cpf_cnpj' => ['required', 'max:14'],
      'ds_email' => ['required', 'email', 'max:30', Rule::unique('usuario', 'ds_email')->ignore($usuario->cd_pessoa, 'cd_pessoa')],
      'ds_senha' => ['nullable', 'max:20'],
      'ds_cargo' => ['required', Rule::in(['cozinha', 'adm', 'caixa'])],
    ]);

    if (!$data['ds_senha']) unset($data['ds_senha']);

    $usuario->update($data);

    return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado.');
  }

  public function destroy(Usuario $usuario)
  {
    try {
      $usuario->delete();
      return redirect()->route('usuarios.index')->with('success', 'Usuário excluído.');
    } catch (QueryException $e) {
      return redirect()->route('usuarios.index')->with('error', 'Não é possível excluir: usuário vinculado a outros registros.');
    }
  }
}