<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
  public function edit()
  {
    $endereco = Endereco::first();
    return view('endereco.form', compact('endereco'));
  }

  public function save(Request $request)
  {
    $data = $request->validate([
      'nm_cidade' => ['nullable','string'],
      'nm_uf' => ['nullable','in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO'],
      'nm_bairro' => ['nullable','string'],
      'nm_logradouro' => ['required','string','max:100'],
      'nr_endereco' => ['nullable','integer'],
      'nr_cep' => ['nullable','string','regex:/^\d{5}-?\d{3}$/'],
      'ds_complemento' => ['nullable','string','max:100'],
    ]);

    $e = Endereco::first();
    if ($e) {
      $e->update($data);
    } else {
      Endereco::create($data);
    }

    return back()->with('success', 'Endereço salvo.');
  }
}