<x-public-layout>
    <div class="relative min-h-screen p-4 md:p-6">

        <div class="flex justify-center">
            <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5 w-full max-w-900"
                 style="background-color:#0f0f0f; max-width:900px;">

                <h1 class="text-2xl font-semibold text-center">
                    <div class="flex-1">
                        {{-- Produto --}}
                        @php
                          $qtProduto = isset($qtProduto) ? $qtProduto : 1;
                        @endphp
                        <div class="font-semibold text-lg">
                            {{ $Produto->nm_produto }}
                        </div>
                        <input type="hidden" id="vl_unitario" value="{{ $Produto->vl_valor }}">
                        <div id="vl_produto_fmt" class="mt-2 font-semibold" style="color: green;">
                            R$ {{ number_format(($Produto->vl_valor * $qtProduto), 2, ',', '.') }}
                        </div>
                        {{-- Quantidade --}}
                        <div class="mt-4 flex items-center justify-center gap-4">
                            <button type="button"
                                    onclick="alterarQtd(-1)"
                                    class="px-3 py-1 bg-red-700 text-white rounded text-xl font-bold hover:bg-red-800">
                                –
                            </button>

                            <input type="text"
                                   id="qt_produto"
                                   name="qt_produto"
                                   value="{{isset($qtProduto) ? $qtProduto : 1}}"
                                   class="w-14 text-center text-lg font-semibold bg-black border border-white/20 rounded"
                                   readonly>

                            <button type="button"
                                    onclick="alterarQtd(1)"
                                    class="px-3 py-1 bg-green-700 text-white rounded text-xl font-bold hover:bg-green-800">
                                +
                            </button>
                        </div>
                    </div>
                </h1>

                @php
                    $nmRota            = "cardapio.carrinho.adicionar";
                    $arrParams         = ["produto" => $Produto];
                    $dsLabelBtnSubmit  = "Adicionar ao Carrinho";
                    $dsAlertaAlteracao = "";
                    $dsBtnRotaVoltar   = "cardapio.index";

                    if (isset($index))
                    {
                      $nmRota            = "cardapio.carrinho.alterar";
                      $arrParams         = ["index" => $index];
                      $dsLabelBtnSubmit  = "Atualizar Carrinho";
                      $dsAlertaAlteracao = "Deseja realmente atualizar os adicionais?";
                      $dsBtnRotaVoltar   = "cardapio.revisao";
                    }
                @endphp

                <form method="post" action="{{ route($nmRota, $arrParams) }}">
                    @csrf
                    <input type="hidden"
                           id="qt_produto_submit"
                           name="qt_produto_submit"
                           value="1">
                    @if($Adicionais->isEmpty())
                        <div class="text-center text-gray-300">
                            Nenhum adicional disponível para este produto.
                        </div>
                    @else
                        <div class="mt-6 text-center text-gray-300">
                            Selecione abaixo os adicionais desejados para acompanhar o pedido
                        </div>

                        <div class="space-y-3">
                            @foreach($Adicionais as $idLinha => $Adicional)
                                <label class="flex items-start justify-between border border-white/10 rounded p-3 cursor-pointer hover:bg-white/5"
                                       style="background-color:#0f0f0f;">
                                    <div class="flex items-start gap-3">
                                        @php
                                            $isChecked = isset($selectedAdds) && in_array($Adicional->cd_adicional, $selectedAdds);
                                        @endphp
                                        <input type="checkbox"
                                               id="id_linha_adicional_{{$idLinha}}"
                                               name="arr_adicionais[]"
                                               onclick="alterarQtd(0)" {{-- Chama o alterarQtd com 0 só para recalcular tudo quando clica em um adicional --}}
                                               value="{{ $Adicional->cd_adicional }}"
                                               @checked($isChecked)
                                               class="mt-1 h-5 w-5 rounded border-gray-400 bg-black focus:ring-white">

                                        <div>
                                            <input type="hidden" id="id_valor_adicional_{{$idLinha}}" value="{{ $Adicional->vl_adicional }}">
                                            <div class="text-base font-semibold">
                                                {{ $Adicional->nm_adicional }}
                                            </div>

                                            @if($Adicional->ds_adicional)
                                                <div class="text-sm text-gray-300 mt-1 leading-tight">
                                                    {{ $Adicional->ds_adicional }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-base font-semibold whitespace-nowrap"
                                         style="color: green;">
                                        R$ {{ number_format($Adicional->vl_adicional, 2, ",", ".") }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    <div class="mt-6 flex justify-center gap-4">
                        <button class="px-6 py-2 rounded text-white hover:opacity-90 transition"
                                @isset($dsAlertaAlteracao) data-confirm="{{ $dsAlertaAlteracao }}" @endisset
                                style="background-color: darkgreen;">
                            {{$dsLabelBtnSubmit}}
                        </button>
                        <a href="{{ route($dsBtnRotaVoltar) }}"
                           class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition"
                           style="background-color: gray;">Voltar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        alterarQtd(0);
      });

      function calcularAdicionaisSelecionados() {
        var total      = 0;
        var checkboxes = document.querySelectorAll('[id^=id_linha_adicional_]');

        if (checkboxes.length)
        {
          checkboxes.forEach(function (checkbox){
            if (checkbox.checked)
            {
              var idLinha          = checkbox.id.split('_').pop();
              var vlValorAdicional = parseFloat(document.getElementById("id_valor_adicional_" + idLinha).value);

              total += vlValorAdicional;
            }
          });
        }

        return total;
      }

      function formatarMoedaBR(valor)
      {
        return new Intl.NumberFormat('pt-BR', {
          style: 'currency',
          currency: 'BRL'
        }).format(valor);
      }

      function alterarQtd(add)
      {
        var objCampoVlProduto       = document.getElementById('vl_produto_fmt');
        var objCampoQtProdutoSubmit = document.getElementById('qt_produto_submit');
        var objCampoQtProduto       = document.getElementById('qt_produto');
        var vlUnitarioProduto       = parseFloat(document.getElementById('vl_unitario').value);

        var qtAtual = parseInt(objCampoQtProduto.value);
        var qtNova  = qtAtual + add;

        if (qtNova < 1)
          qtNova = 1;

        objCampoQtProduto.value       = qtNova;
        objCampoQtProdutoSubmit.value = qtNova;
        var vlTotalAdicionais         = calcularAdicionaisSelecionados();
        var novoTotal                 = (vlUnitarioProduto * qtNova) + (vlTotalAdicionais * qtNova);

        // Atualiza o campo com número formatado no padrão brasileiro
        objCampoVlProduto.innerHTML = formatarMoedaBR(novoTotal);
      }
    </script>
</x-public-layout>
