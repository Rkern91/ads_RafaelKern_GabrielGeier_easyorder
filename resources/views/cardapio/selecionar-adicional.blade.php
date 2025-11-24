<x-public-layout>
    <div class="relative min-h-screen p-4 md:p-6">

        <div class="flex justify-center">
            <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5 w-full max-w-900"
                 style="background-color:#0f0f0f; max-width:900px;">

                <h1 class="text-2xl font-semibold mb-4 text-center">
                    Escolher adicionais para {{ $Produto->nm_produto }}
                </h1>

                @if($Adicionais->isEmpty())
                    <div class="text-center text-gray-300">
                        Nenhum adicional disponível para este produto.
                    </div>
                @else
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
                        <div class="space-y-3">
                            @foreach($Adicionais as $a)
                                <label class="flex items-start justify-between border border-white/10 rounded p-3 cursor-pointer hover:bg-white/5"
                                       style="background-color:#0f0f0f;">
                                    <div class="flex items-start gap-3">
                                        @php
                                            $isChecked = isset($selectedAdds) && in_array($a->cd_adicional, $selectedAdds);
                                        @endphp
                                        <input type="checkbox"
                                               name="adicionais[]"
                                               value="{{ $a->cd_adicional }}"
                                               @checked($isChecked)
                                               class="mt-1 h-5 w-5 rounded border-gray-400 bg-black focus:ring-white">

                                        <div>
                                            <div class="text-base font-semibold">
                                                {{ $a->nm_adicional }}
                                            </div>

                                            @if($a->ds_adicional)
                                                <div class="text-sm text-gray-300 mt-1 leading-tight">
                                                    {{ $a->ds_adicional }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-base font-semibold whitespace-nowrap"
                                         style="color: green;">
                                        R$ {{ number_format($a->vl_adicional,2,',','.') }}
                                    </div>
                                </label>
                            @endforeach
                        </div>

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
                @endif

            </div>
        </div>
    </div>

</x-public-layout>
