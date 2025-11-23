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
  public function obterCardapioCompleto()
  {
    session(["mesa" => 1]);
    
    $categorias         = ProdutoCategoria::orderBy('nm_categoria')->get();
    $id_categoria_ativa = $categorias[0]->cd_categoria;
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

  public function confirmarEnviarPedido(Request $request)
  {
    //TODO: Ajustar método de confirmação do pedido
  }

  public function visualizarCarrinhoCompras()
  {
    $carrinhoSession  = session('carrinho_preview');
    $cdMesa           = session("mesa");
    $carrinhoProdutos = [];
    $subTotalGeral    = 0;
    
    if (isset($carrinhoSession["arrMesa"]) && count($carrinhoSession["arrMesa"][$cdMesa]["arrProdutos"]))
    {
      foreach ($carrinhoSession["arrMesa"][$cdMesa]["arrProdutos"] as $index => $arrDadosProduto)
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
              "obj_adicional" => $Adicional
            ];
          }
        }

        $carrinhoProdutos[$index] = [
          "obj_produto"    => $Produto,
          "arr_adicionais" => $arrDadosAdicionais
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
    
    return $this->obterCardapioCompleto();
  }
  
  public function alterarAdicionalProduto($index, Request $request)
  {
    $cdMesa          = session("mesa");
    $cart            = session("carrinho_preview");
    $arrIdAdicionais = $request->get('adicionais') != null ? $request->get('adicionais') : [];
  
    $cart["arrMesa"][$cdMesa]["arrProdutos"][$index]["arrCdAdicionais"] = $arrIdAdicionais;
    
    session(["carrinho_preview" => $cart]);
    
    return $this->obterCardapioCompleto();
  }

  public function removerItemCarrinho($index)
  {
    $cdMesa = session("mesa");
    $cart   = session("carrinho_preview");
    
    unset($cart["arrMesa"][$cdMesa]["arrProdutos"][$index]);
    
    session(["carrinho_preview" => $cart]);
    
    return $this->visualizarCarrinhoCompras();
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

  public function editarAdicionalProdutoCarrinho($index)
  {
    $cdMesa       = session("mesa");
    $cart         = session("carrinho_preview");
    $indexProduto = $cart["arrMesa"][$cdMesa]["arrProdutos"][$index];
    $selectedAdds = $cart["arrMesa"][$cdMesa]["arrProdutos"][$index]["arrCdAdicionais"];
    $Produto      = Produto::where("cd_produto", $indexProduto["cd_produto"])->get()->first();
    $Adicionais   = Adicional::where("cd_categoria", $Produto->cd_categoria)->orderBy("nm_adicional")->get();
    
    return view(
      "cardapio.selecionar-adicional",
      compact(
        "Produto",
        "Adicionais",
        "selectedAdds",
        "index"
      )
    );
  }
}