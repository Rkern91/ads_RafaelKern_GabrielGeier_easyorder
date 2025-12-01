<?php

namespace App\Http\Controllers;

use App\Models\AdicionaisPedido;
use App\Models\Adicional;
use App\Models\ItensPedido;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\ProdutoCategoria       as ProdutoCategoria;
use Illuminate\Contracts\View\Factory as FactoryAlias;
use Illuminate\Contracts\View\View    as ViewAlias;
use Illuminate\Http\Request           as Request;
use Illuminate\Support\Facades\DB;

class CardapioController extends Controller
{
  /**
   * @return FactoryAlias|ViewAlias
   */
  public function obterCardapioCompleto()
  {
    //TODO: Estruturar uma forma mais dinâmica de logar e identificar a mesa
    session(["mesa" => 3]);
    
    $categorias         = ProdutoCategoria::orderBy("nm_categoria")->get();
    $id_categoria_ativa = $categorias[0]->cd_categoria;
    $produtos           = Produto::where("cd_categoria", $id_categoria_ativa)->orderBy("nm_produto")->get();
    
    return view(
      "cardapio.index",
      compact(
        "categorias",
        "id_categoria_ativa",
        "produtos"
      )
    );
  }
  
  /**
   * Obtem os produtos de uma categoria para listagem.
   * @param ProdutoCategoria $categoria
   * @return FactoryAlias|ViewAlias
   */
  public function obterCardapioCategoria(ProdutoCategoria $categoria)
  {
    $categorias         = ProdutoCategoria::orderBy("nm_categoria")->get();
    $id_categoria_ativa = $categoria->cd_categoria;
    $produtos           = Produto::where("cd_categoria", $id_categoria_ativa)->orderBy("nm_produto")->get();
    
    return view(
      "cardapio.index",
      compact(
        "categorias",
        "id_categoria_ativa",
        "produtos"
      )
    );
  }

  public function confirmarEnviarPedido(Request $request)
  {
    // cd_mesa vem do hidden (LocalStorage) — obrigatório
    $cdMesa = (int)$request->input('cd_mesa');

    if (!$cdMesa)
      return back()->with('error', 'Mesa não definida neste dispositivo.');

    $cart = session('carrinho_preview');

    if (!isset($cart['arrMesa'][$cdMesa]['arrProdutos']) || empty($cart['arrMesa'][$cdMesa]['arrProdutos']))
      return back()->with('error', 'Seu carrinho está vazio.');

    $arrItensSessao = $cart['arrMesa'][$cdMesa]['arrProdutos'];
    $dsObs = $request->input('ds_observacao');

    // Calcula total e resolve objetos
    $subTotalGeral = 0.0;
    $itensParaPersistir = [];

    foreach ($arrItensSessao as $item) {

      $produto = Produto::where('cd_produto', $item['cd_produto'])->first();
      if (!$produto)
        continue;

      $qt = (int)($item['qt_produto'] ?? 1);
      $subTotalGeral += ($produto->vl_valor * $qt);

      $adicionaisObjs = [];

      foreach (($item['arrCdAdicionais'] ?? []) as $cdAd)
      {
        $ad = Adicional::where('cd_adicional', $cdAd)->first();
        if ($ad)
        {
          $subTotalGeral += ($ad->vl_adicional * $qt);
          $adicionaisObjs[] = $ad;
        }
      }

      $itensParaPersistir[] = [
        'produto' => $produto,
        'qt' => $qt,
        'adicionais' => $adicionaisObjs,
      ];
    }

    if (empty($itensParaPersistir))
      return back()->with('error', 'Não foi possível montar o pedido a partir do carrinho.');

    // Persiste tudo em transação
    $Pedido = DB::transaction(function () use ($cdMesa, $subTotalGeral, $dsObs, $itensParaPersistir)
    {
      $Pedido = Pedido::create([
        'cd_mesa' => $cdMesa,
        'vl_pedido' => $subTotalGeral,
        'id_status' => 0, // em aberto
        'ds_observacao' => $dsObs,
      ]);

      foreach ($itensParaPersistir as $item) {
        $Item = ItensPedido::create([
          'cd_pedido' => $Pedido->cd_pedido,
          'cd_produto' => $item['produto']->cd_produto,
          'qt_produto' => $item['qt'],
        ]);

        foreach ($item['adicionais'] as $ad) {
          AdicionaisPedido::create([
            'cd_item_pedido' => $Item->cd_item_pedido,
            'cd_adicional' => $ad->cd_adicional,
          ]);
        }
      }

      return $Pedido;
    });

    // Limpa o carrinho da mesa
    $cart['arrMesa'][$cdMesa]['arrProdutos'] = [];
    session(['carrinho_preview' => $cart]);

    // Redireciona para a tela de pagamento (QR Code) do pedido recém-criado
    return redirect()->route('pagamento.show', ['pedido' => $Pedido->cd_pedido]);
  }

  public function visualizarCarrinhoCompras()
  {
    $carrinhoSession  = session("carrinho_preview");
    $cdMesa           = session("mesa");
    $carrinhoProdutos = [];
    $subTotalGeral    = 0;
    
    if (isset($carrinhoSession["arrMesa"][$cdMesa]) && count($carrinhoSession["arrMesa"][$cdMesa]["arrProdutos"]))
    {
      foreach ($carrinhoSession["arrMesa"][$cdMesa]["arrProdutos"] as $index => $arrDadosProduto)
      {
        $Produto            = Produto::where("cd_produto", $arrDadosProduto["cd_produto"])->get()->first();
        $subTotalGeral     += ($Produto->vl_valor * $arrDadosProduto["qt_produto"]);
        $arrDadosAdicionais = [];
        
        if (isset($arrDadosProduto["arrCdAdicionais"]))
        {
          foreach ($arrDadosProduto["arrCdAdicionais"] as $cdAdicional)
          {
            $Adicional      = Adicional::where("cd_adicional", $cdAdicional)->get()->first();
            $subTotalGeral += ($Adicional->vl_adicional * $arrDadosProduto["qt_produto"]);
            
            $arrDadosAdicionais[] = [
              "obj_adicional" => $Adicional
            ];
          }
        }

        $carrinhoProdutos[$index] = [
          "obj_produto"    => $Produto,
          "qt_produto"     => $arrDadosProduto["qt_produto"],
          "arr_adicionais" => $arrDadosAdicionais
        ];
      }
    }
    
    $categorias         = ProdutoCategoria::orderBy("nm_categoria")->get();
    $id_categoria_ativa = false;
    
    return view(
      "cardapio.carrinho-preview",
      compact([
        "carrinhoProdutos",
        "subTotalGeral",
        "categorias",
        "id_categoria_ativa"
      ])
    );
  }
  
  public function adicionarItemCarrinho(Produto $Produto, Request $request)
  {
    $cdMesa          = session("mesa");
    $cart            = session("carrinho_preview");
    $arrIdAdicionais = $request->get("arr_adicionais")    != null ? $request->get("arr_adicionais")    : [];
    $qtProduto       = $request->get("qt_produto_submit") != null ? $request->get("qt_produto_submit") : 1;
    
    $cart["arrMesa"][$cdMesa]["arrProdutos"][] = [
      "cd_produto"      => $Produto->cd_produto,
      "nm_produto"      => $Produto->nm_produto,
      "qt_produto"      => $qtProduto,
      "arrCdAdicionais" => $arrIdAdicionais
    ];
    
    session(["carrinho_preview" => $cart]);
    
    return redirect()->route("cardapio.revisao")->with("success", "Item adicionado ao carrinho!");
  }
  
  public function alterarAdicionalProduto($index, Request $request)
  {
    $cdMesa          = session("mesa");
    $cart            = session("carrinho_preview");
    $arrIdAdicionais = $request->get("arr_adicionais")    != null ? $request->get("arr_adicionais") : [];
    $qtProduto       = $request->get("qt_produto_submit") != null ? $request->get("qt_produto_submit") : 1;
  
    $cart["arrMesa"][$cdMesa]["arrProdutos"][$index]["arrCdAdicionais"] = $arrIdAdicionais;
    $cart["arrMesa"][$cdMesa]["arrProdutos"][$index]["qt_produto"]      = $qtProduto;
    
    session(["carrinho_preview" => $cart]);
    
    return redirect()->route("cardapio.revisao")->with("success", "Item atualizado!");
  }

  public function removerItemCarrinho($index)
  {
    $cdMesa = session("mesa");
    $cart   = session("carrinho_preview");
    
    unset($cart["arrMesa"][$cdMesa]["arrProdutos"][$index]);
    
    session(["carrinho_preview" => $cart]);
    
    return redirect()->route("cardapio.revisao")->with("success", "Item removido do carrinho!");
  }

  public function obterAdicionalProduto(Produto $produto, Request $request)
  {
    $Adicionais = Adicional::where("cd_categoria", $produto->cd_categoria)->orderBy("nm_adicional")->get();
    $Produto    = $produto;
    
    if (!count($Adicionais))
      return $this->adicionarItemCarrinho($Produto, $request);
    
    return view(
      "cardapio.selecionar-adicional",
      compact(
        "Produto",
        "Adicionais"
      )
    );
  }
  
  /**
   * Obtem e direciona para tela de seleção de adicionais do produto.
   * @param $index
   * @return FactoryAlias|ViewAlias
   */
  public function editarAdicionalProdutoCarrinho($index)
  {
    $cdMesa       = session("mesa");
    $cart         = session("carrinho_preview");
    $indexProduto = $cart["arrMesa"][$cdMesa]["arrProdutos"][$index];
    $selectedAdds = $cart["arrMesa"][$cdMesa]["arrProdutos"][$index]["arrCdAdicionais"];
    $qtProduto    = $cart["arrMesa"][$cdMesa]["arrProdutos"][$index]["qt_produto"];
    $Produto      = Produto::where("cd_produto", $indexProduto["cd_produto"])->get()->first();
    $Adicionais   = Adicional::where("cd_categoria", $Produto->cd_categoria)->orderBy("nm_adicional")->get();
    
    return view(
      "cardapio.selecionar-adicional",
      compact(
        "Produto",
        "Adicionais",
        "selectedAdds",
        "index",
        "qtProduto",
      )
    );
  }
  
  public function visualizarConta()
  {
    $cdMesa  = session("mesa");
    $Pedidos = Pedido::query()
      ->where('cd_mesa', $cdMesa)
      ->where(function ($q) {
        $q->whereNull('id_status')->orWhere('id_status', '!=', 3);
      })
      ->with([
        'itens',
        'itens.produto',
        'itens.adicionais',
        'itens.adicionais.adicional'
      ])
      ->get();
    
    $categorias         = ProdutoCategoria::orderBy("nm_categoria")->get();
    $id_categoria_ativa = false;
    $vlTotalPedidos     = 0;
    
    foreach ($Pedidos as $Pedido)
      $vlTotalPedidos += $Pedido->vl_pedido;
    
    return view(
      'cardapio.conta',
      compact([
        "Pedidos",
        "categorias",
        "id_categoria_ativa",
        "vlTotalPedidos"
      ])
    );
  }
}