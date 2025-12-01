<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
  public function index(Request $req)
  {
    $q      = trim((string)$req->query('q', ''));
    $status = $req->query('status', '');
    $mesa   = trim((string)$req->query('mesa', ''));

    $sql = DB::table('pedido as p')
      ->leftJoin('mesa as m', 'm.cd_mesa', '=', 'p.cd_mesa')
      ->select('p.cd_pedido', 'p.cd_mesa', 'p.vl_pedido', 'p.dt_pedido', 'p.id_status', 'm.nm_mesa')
      ->orderByDesc('p.cd_pedido');

    if ($q !== '') {
      $sql->where(function ($w) use ($q) {
        $w->where('p.cd_pedido', (int)$q)
          ->orWhere('p.cd_mesa', (int)$q)
          ->orWhere('m.nm_mesa', 'ilike', "%{$q}%");
      });
    }

    if ($mesa !== '') {
      $sql->where(function ($w) use ($mesa) {
        $w->where('p.cd_mesa', (int)$mesa)
          ->orWhere('m.nm_mesa', 'ilike', "%{$mesa}%");
      });
    }

    if ($status !== '' && is_numeric($status)) {
      $sql->where('p.id_status', (int)$status);
    }

    $pedidos = $sql->paginate(15)->appends($req->query());

    return view('pedidos.index', [
      'pedidos' => $pedidos,
      'q'       => $q,
      'status'  => $status,
      'mesa'    => $mesa,
    ]);
  }

  public function finalizar($pedidoId)
  {
    DB::table('pedido')
      ->where('cd_pedido', $pedidoId)
      ->where('id_status', '<>', 3)
      ->update(['id_status' => 3]);

    return redirect()
      ->route('pedidos.index')
      ->with('success', 'Pedido concluído.');
  }
}