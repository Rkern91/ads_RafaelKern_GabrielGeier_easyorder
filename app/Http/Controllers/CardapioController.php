<?php

namespace App\Http\Controllers;

use App\Models\Adicional;
use App\Models\Mesa;
use App\Models\Produto;
use App\Models\ProdutoCategoria    as ProdutoCategoria;
use Illuminate\Contracts\View\View as View;
use Illuminate\Http\Request        as Request;
use Illuminate\Support\Facades\DB;

class CardapioController extends Controller
{
  /**
   * @return \Illuminate\Contracts\View\Factory|View
   */
  public function obterCardapioCompleto($idReturnSucess = false)
  {
    session(["mesa" => 1]);
    
    $categorias         = ProdutoCategoria::orderBy('nm_categoria')->get();
    $id_categoria_ativa = $categorias[0]->cd_categoria;
    $produtos           = Produto::where('cd_categoria', $id_categoria_ativa)->orderBy('nm_produto')->get();
    
    if ($idReturnSucess)
      return view(
        'cardapio.index',
        compact(
          'categorias',
          'id_categoria_ativa',
          'produtos'
        )
      )->with('success', 'Produto adicionado ao carrinho!');
    
    return view(
      'cardapio.index',
      compact(
        'categorias',
        'id_categoria_ativa',
        'produtos'
      )
    );
  }
  
  /**
   * Obtem os produtos de uma categoria para listagem.
   * @param ProdutoCategoria $categoria
   * @return \Illuminate\Contracts\View\Factory|View
   */
  public function obterCardapioCategoria(ProdutoCategoria $categoria)
  {
    $categorias         = ProdutoCategoria::orderBy('nm_categoria')->get();
    $id_categoria_ativa = $categoria->cd_categoria;
    $produtos           = Produto::where('cd_categoria', $id_categoria_ativa)->orderBy('nm_produto')->get();
    
    return view(
      'cardapio.index',
      compact(
        'categorias',
        'id_categoria_ativa',
        'produtos'
      )
    );
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
    $carrinhoSession  = session('carrinho_preview');
    $cdMesa           = session("mesa");
    $carrinhoProdutos = [];
    $subTotalGeral    = 0;
    
    if (isset($carrinhoSession["arrMesa"]) && count($carrinhoSession["arrMesa"][$cdMesa]["arrProdutos"]))
    {
      foreach ($carrinhoSession["arrMesa"][$cdMesa]["arrProdutos"] as $arrDadosProduto)
      {
        $Produto            = Produto::where('cd_produto', $arrDadosProduto["cd_produto"])->get()->first();
        $subTotalGeral     += $Produto->vl_valor;
        $arrDadosAdicionais = [];
        
        if (isset($arrDadosProduto["arrCdAdicionais"]))
        {
          foreach ($arrDadosProduto["arrCdAdicionais"] as $cdAdicional)
          {
            $Adicional      = Adicional::where('cd_adicional', $cdAdicional)->get()->first();
            $subTotalGeral += $Adicional->vl_adicional;
            
            $arrDadosAdicionais[] = [
              "cd_adicional" => $Adicional->cd_adicional,
              "nm_adicional" => $Adicional->nm_adicional,
              "vl_adicional" => $Adicional->vl_adicional
            ];
          }
        }

        $carrinhoProdutos[] = [
          "cd_produto"    => $Produto->cd_produto,
          "nm_produto"    => $Produto->nm_produto,
          "vl_produto"    => $Produto->vl_valor,
          "ds_produto"    => $Produto->ds_produto,
          "adicionais"    => $arrDadosAdicionais
        ];
      }
    }
    
    $categorias         = ProdutoCategoria::orderBy('nm_categoria')->get();
    $id_categoria_ativa = false;
    
    return view(
      "cardapio.carrinho-preview",
      compact([
        "carrinhoProdutos",
        "subTotalGeral",
        'categorias',
        'id_categoria_ativa'
      ])
    );
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
  
  public function adicionarItemCarrinho(Produto $Produto, Request $request)
  {
    $cdMesa          = session("mesa");
    $cart            = session("carrinho_preview");
    $arrIdAdicionais = $request->get('adicionais') != null ? $request->get('adicionais') : [];
    
    $cart["arrMesa"][$cdMesa]["arrProdutos"][] = [
      "cd_produto"      => $Produto->cd_produto,
      "nm_produto"      => $Produto->nm_produto,
      "arrCdAdicionais" => $arrIdAdicionais
    ];
    
    session(["carrinho_preview" => $cart]);
    
    return $this->obterCardapioCompleto(true);
  }

  public function obterAdicionalProduto(Produto $produto, Request $request)
  {
    $Adicionais = Adicional::where('cd_categoria', $produto->cd_categoria)->orderBy('nm_adicional')->get();
    $Produto    = $produto;
    
    if (!count($Adicionais))
      return $this->adicionarItemCarrinho($Produto, $request);
    
    return view(
      'cardapio.selecionar-adicional',
      compact(
        'Produto',
        'Adicionais'
      )
    );
  }
}