<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
  public function edit(Request $request)
  {
    $user = $request->user();
    return view('profile.edit', compact('user'));
  }

  public function update(Request $request)
  {
    $user = $request->user();

    $data = $request->validate([
      'nm_pessoa' => ['required', 'max:100'],
      'ds_apelido' => ['required', 'max:30', Rule::unique('usuario', 'ds_apelido')->ignore($user->cd_pessoa, 'cd_pessoa')],
      'ds_email' => ['required', 'email', 'max:30', Rule::unique('usuario', 'ds_email')->ignore($user->cd_pessoa, 'cd_pessoa')],
      'ds_senha' => ['nullable', 'max:20'],
    ]);

    if (empty($data['ds_senha'])) unset($data['ds_senha']);

    $user->update($data);

    return back()->with('status', 'profile-updated');
  }
}