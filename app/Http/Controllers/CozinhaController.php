<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CozinhaController extends Controller
{
  public function index()
  {
    $pedidos = DB::table('pedido as p')
      ->leftJoin('mesa as m', 'm.cd_mesa', '=', 'p.cd_mesa')
      ->whereIn('p.id_status', [0, 1])
      ->orderBy('p.dt_pedido')
      ->select('p.cd_pedido','p.cd_mesa','p.vl_pedido','p.dt_pedido','p.id_status','p.ds_observacao','m.nm_mesa')
      ->get();

    $ids = $pedidos->pluck('cd_pedido')->all();

    $itensPorPedido = DB::table('itens_pedido as ip')
      ->join('produto as pr', 'pr.cd_produto', '=', 'ip.cd_produto')
      ->whereIn('ip.cd_pedido', $ids)
      ->select('ip.cd_pedido','ip.qt_produto','pr.nm_produto')
      ->get()
      ->groupBy('cd_pedido');

    $adicionaisPorPedido = DB::table('adicionais_pedido as ap')
      ->join('adicional as a', 'a.cd_adicional', '=', 'ap.cd_adicional_pedido')
      ->whereIn('ap.cd_pedido', $ids)
      ->select('ap.cd_pedido','a.nm_adicional')
      ->get()
      ->groupBy('cd_pedido');

    return view('cozinha.index', [
      'pedidos' => $pedidos,
      'itensPorPedido' => $itensPorPedido,
      'adicionaisPorPedido' => $adicionaisPorPedido,
    ]);
  }

  public function preparar($pedidoId)
  {
    DB::table('pedido')
      ->where('cd_pedido', $pedidoId)
      ->where('id_status', 0)
      ->update(['id_status' => 1]);

    return redirect()->route('cozinha.index')->with('success', 'Pedido marcado como preparando.');
  }

  public function servir($pedidoId)
  {
    DB::table('pedido')
      ->where('cd_pedido', $pedidoId)
      ->where('id_status', 1)
      ->update(['id_status' => 2]);

    return redirect()->route('cozinha.index')->with('success', 'Pedido marcado como servido.');
  }
}