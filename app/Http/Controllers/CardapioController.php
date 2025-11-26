<?php

namespace App\Http\Controllers;

use App\Models\AdicionaisPedido;
use App\Models\Adicional;
use App\Models\ItensPedido;
use App\Models\Mesa;
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
    //TODO: Ajustar método de confirmação do pedido
    $carrinhoSession  = session("carrinho_preview");
    $cdMesa           = session("mesa");
    $subTotalGeral    = 0;
    $carrinhoProdutos = [];
    
    if (isset($carrinhoSession["arrMesa"]) && count($carrinhoSession["arrMesa"][$cdMesa]["arrProdutos"]))
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
        
        $carrinhoProdutos[] = [
          "obj_produto"    => $Produto,
          "qt_produto"     => $arrDadosProduto["qt_produto"],
          "arr_adicionais" => $arrDadosAdicionais
        ];
      }
      
      $Pedido = Pedido::create([
        "cd_mesa"    => $cdMesa,
        "vl_pedido"  => $subTotalGeral,
        "id_status"  => 0, // em aberto
        "ds_observacao" => $request->get("ds_observacao"),
      ]);
      
      foreach ($carrinhoProdutos as $itemCarrinho)
      {
        $ItemPedido = ItensPedido::create([
          "cd_pedido" => $Pedido->cd_pedido,
          "cd_produto" => $itemCarrinho["obj_produto"]->cd_produto,
          "qt_produto" => $itemCarrinho["qt_produto"]
        ]);
        
        foreach ($itemCarrinho["arr_adicionais"] as $arrDadoAdicional)
        {
          AdicionaisPedido::create([
            "cd_item_pedido" => $ItemPedido->cd_item_pedido,
            "cd_adicional"   => $arrDadoAdicional["obj_adicional"]->cd_adicional
          ]);
        }
      }
    }
    
    $carrinhoSession["arrMesa"][$cdMesa]["arrProdutos"] = [];
    session(["carrinho_preview" => $carrinhoSession]);
    
    return redirect()->route("cardapio.revisao")->with("success", "Pedido enviado com sucesso!");
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
}