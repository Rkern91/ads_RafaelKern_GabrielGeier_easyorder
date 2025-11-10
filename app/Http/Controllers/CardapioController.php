<?php

namespace App\Http\Controllers;

use App\Models\Adicional;
use App\Models\Produto;
use App\Models\ProdutoCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardapioController extends Controller
{
  public function index(Request $request)
  {
    $categorias = ProdutoCategoria::orderBy('nm_categoria')->get();
    $produtosPorCategoria = Produto::orderBy('nm_produto')
      ->get()
      ->groupBy('cd_categoria');

    $adicionais = Adicional::orderBy('nm_adicional')->get();

    return view('cardapio.index', [
      'categorias' => $categorias,
      'modo' => 'todos',
      'titulo' => 'Cardápio',
      'produtosPorCategoria' => $produtosPorCategoria,
      'adicionais' => $adicionais,
      'mesa' => $request->query('mesa'),
    ]);
  }

  public function categoria(Request $request, ProdutoCategoria $categoria)
  {
    $categoriaProduto = ProdutoCategoria::where('cd_categoria', $categoria->cd_categoria)->get()[0];
    $produtos         = Produto::where('cd_categoria', $categoria->cd_categoria)->orderBy('nm_produto')->get();
    
    return view('cardapio.categoria', [ // <-
      'categorias' => $categoriaProduto,
      'mesa'       => '01',
      'itens'      => $produtos
    ]);
  }

  public function adicionais(Request $request)
  {
    $categorias = ProdutoCategoria::orderBy('nm_categoria')->get();
    $adicionais = Adicional::orderBy('nm_adicional')->get();
    return view('cardapio.index', [ // <-
      'categorias' => $categorias,
      'modo' => 'adicional',
      'titulo' => 'Adicionais',
      'itens' => $adicionais,
      'mesa' => $request->query('mesa'),
    ]);
  }

  public function confirmar(Request $request)
  {
    $mesa = $request->input('mesa');
    $obs = trim((string)$request->input('obs'));
    $payload = json_decode($request->input('payload', '{}'), true) ?: [];

    $prodSel = collect($payload['produtos'] ?? []);
    $adiSel = collect($payload['adicionais'] ?? []);

    $produtos = Produto::whereIn('cd_produto', $prodSel->pluck('id'))->get()->keyBy('cd_produto');
    $adicionais = Adicional::whereIn('cd_adicional', $adiSel->pluck('id'))->get()->keyBy('cd_adicional');

    $itensProdutos = $prodSel->map(function ($p) use ($produtos) {
      $m = $produtos[$p['id']] ?? null;
      if (!$m) return null;
      $q = (int)$p['qtd'];
      $vl = (float)$m->vl_valor;
      return [
        'id' => (int)$m->cd_produto,
        'nome' => $m->nm_produto,
        'qtd' => $q,
        'preco' => $vl,
        'subtotal' => $q * $vl,
        'tipo' => 'produto',
      ];
    })->filter()->values();

    $itensAdicionais = $adiSel->map(function ($a) use ($adicionais) {
      $m = $adicionais[$a['id']] ?? null;
      if (!$m) return null;
      $q = (int)$a['qtd'];
      $vl = (float)$m->vl_adicional;
      return [
        'id' => (int)$m->cd_adicional,
        'nome' => $m->nm_adicional,
        'qtd' => $q,
        'preco' => $vl,
        'subtotal' => $q * $vl,
        'tipo' => 'adicional',
      ];
    })->filter()->values();

    $total = $itensProdutos->sum('subtotal') + $itensAdicionais->sum('subtotal');

    session([
      'carrinho_preview' => [
        'mesa' => $mesa,
        'obs' => $obs,
        'produtos' => $itensProdutos->all(),
        'adicionais' => $itensAdicionais->all(),
        'total' => $total,
        'restore' => [
          'produtos' => $prodSel->keyBy('id'),
          'adicionais' => $adiSel->keyBy('id'),
          'obs' => $obs,
        ],
      ],
    ]);

    return redirect()->route('cardapio.revisao');
  }

  public function revisao(Request $request)
  {
    $cart = session('carrinho_preview');
    if (!$cart) {
      return redirect()->route('cardapio.index');
    }

    $categorias = \App\Models\ProdutoCategoria::orderBy('nm_categoria')->get();
    $mesa = $cart['mesa'] ?? null;
    return view('cardapio.confirmacao', [
      'categorias' => $categorias,
      'mesa' => $mesa,
      'cart' => $cart,
    ]);
  }

  public function finalizar()
  {
    $cart = session('carrinho_preview');
    if (!$cart) {
      return redirect()->route('cardapio.index')->with('error', 'Carrinho vazio.');
    }

    $mesa = (int)($cart['mesa'] ?? 0);
    $obs = (string)($cart['obs'] ?? '');
    $produtos = collect($cart['produtos'] ?? []);   // [id, qtd, preco, subtotal]
    $adicionais = collect($cart['adicionais'] ?? []); // [id, qtd, preco, subtotal]
    $total = (float)($cart['total'] ?? 0);

    if ($produtos->isEmpty() && $adicionais->isEmpty()) {
      return redirect()->route('cardapio.index')->with('error', 'Nenhum item selecionado.');
    }

    DB::transaction(function () use ($mesa, $total, $obs, $produtos, $adicionais) {
      $pedidoId = DB::table('pedido')->insertGetId([
        'cd_mesa' => $mesa ?: null,
        'vl_pedido' => $total,
        'dt_pedido' => now(),
        'id_status' => 0,
        'ds_observacao' => $obs ?: null, // remova se não criar a coluna
      ], 'cd_pedido');

      foreach ($produtos as $p) {
        DB::table('itens_pedido')->insert([
          'cd_pedido' => $pedidoId,
          'cd_produto' => (int)$p['id'],
          'qt_produto' => (int)$p['qtd'],
        ]);
      }

      foreach ($adicionais as $a) {
        $q = (int)$a['qtd'];
        if ($q < 1) continue;
        $rows = [];
        for ($i = 0; $i < $q; $i++) {
          $rows[] = [
            'cd_pedido' => $pedidoId,
            'cd_adicional_pedido' => (int)$a['id'],
          ];
        }
        DB::table('adicionais_pedido')->insert($rows);
      }
    });

    session()->forget('carrinho_preview');

    return redirect()->route('cardapio.index', ['mesa' => $mesa])->with('success', 'Pedido enviado com sucesso!');
  }
  
  public function navigation(Request $request)
  {
    $categorias = ProdutoCategoria::orderBy('nm_categoria')->get();
    return view('cardapio.navigation', [
      'categorias' => $categorias,
      'mesa' => $request->query('mesa'),
    ]);
  }
  
  public function adicionaisProduto(Request $request, Produto $produto)
  {
    $adicionais = Adicional::orderBy('nm_adicional')->get();
    return view('cardapio.adicionais', [
      'produto' => $produto,
      'adicionais' => $adicionais,
      'mesa' => $request->query('mesa'),
    ]);
  }
  
  public function adicionarCarrinho(Request $request)
  {
    $produtoId = $request->input('produto_id');
    $mesa = $request->input('mesa');
    $adicionais = $request->input('adicionais', []);
    
    $produto = Produto::findOrFail($produtoId);
    $adics = Adicional::whereIn('cd_adicional', $adicionais)->get();
    
    $cart = session('carrinho_preview', [
      'mesa' => $mesa,
      'produtos' => [],
      'adicionais' => [],
      'total' => 0
    ]);
    
    $cart['produtos'][] = [
      'id' => $produto->cd_produto,
      'nome' => $produto->nm_produto,
      'preco' => $produto->vl_valor,
      'subtotal' => $produto->vl_valor,
      'tipo' => 'produto'
    ];
    
    foreach ($adics as $a) {
      $cart['adicionais'][] = [
        'id' => $a->cd_adicional,
        'nome' => $a->nm_adicional,
        'preco' => $a->vl_adicional,
        'subtotal' => $a->vl_adicional,
        'tipo' => 'adicional'
      ];
    }
    
    $cart['total'] = collect($cart['produtos'])->sum('subtotal') + collect($cart['adicionais'])->sum('subtotal');
    session(['carrinho_preview' => $cart]);
    
    return redirect()->route('cardapio.revisao');
  }
  
}