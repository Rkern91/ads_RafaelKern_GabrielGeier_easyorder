<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
  public function create()
  {
    return view('auth.login');
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'ds_email' => ['required','email'],
      'ds_senha' => ['required'],
    ]);

    $user = Usuario::where('ds_email', $data['ds_email'])->first();

    if (!$user || $user->ds_senha !== $data['ds_senha']) {
      return back()->withErrors([
        'ds_email' => 'Credenciais inválidas.',
      ])->onlyInput('ds_email');
    }

    Auth::login($user, $request->boolean('remember'));
    $request->session()->regenerate();

    return redirect()->intended(route('dashboard'));
  }

  public function destroy(Request $request)
  {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
  }
}