<x-public-layout>
    @include('cardapio.navigation')
    <div class="min-w-full pt-6 mt-10">
        --
    </div>
    <div class="relative min-h-screen p-4 md:p-6">
        <div class="flex justify-center">
            <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5 w-full"
                 style="background-color:#0f0f0f; max-width:900px;">

                <h1 class="text-2xl font-semibold mb-6 text-center">
                    Revisão do seu Carrinho
                </h1>

                @if(empty($carrinhoProdutos))
                    <div class="text-center text-gray-300">Seu carrinho está vazio.</div>
                    <div class="text-center mt-4">
                        <a href="{{ route('cardapio.index') }}"
                           class="inline-block px-5 py-2 rounded bg-gray-500 text-white hover:opacity-90 transition">
                            Voltar ao menu
                        </a>
                    </div>
                @else

                    <div class="space-y-4">

                        @foreach($carrinhoProdutos as $index => $arrItem)
                            @php
                                $Produto       = $arrItem["obj_produto"];
                                $arrAdicionais = $arrItem['arr_adicionais'];
                            @endphp

                            <div class="border border-white/10 rounded p-4 flex justify-between gap-4"
                                 style="background-color:#0f0f0f;">

                                {{-- Lado esquerdo: Produto + adicionais --}}
                                <div class="flex-1">

                                    {{-- Produto --}}
                                    <div class="font-semibold text-lg">
                                        {{ $Produto->nm_produto }}
                                    </div>

                                    @if(!empty($Produto->ds_produto))
                                        <div class="text-sm text-gray-300 mt-1 leading-tight">
                                            {{ $Produto->ds_produto }}
                                        </div>
                                    @endif

                                    <div class="mt-2 font-semibold" style="color: green;">
                                        R$ {{ number_format($Produto->vl_valor, 2, ',', '.') }}
                                    </div>

                                    {{-- Adicionais do produto --}}
                                    @if(!empty($arrAdicionais))
                                        <div class="mt-4 text-sm uppercase tracking-wide text-gray-400">
                                            Adicionais
                                        </div>

                                        <div class="mt-1 space-y-1">
                                            @foreach($arrAdicionais as $arrAdicional)
                                                @php
                                                  $Adicional = $arrAdicional["obj_adicional"]
                                                @endphp

                                                <div class="flex justify-between text-sm border border-white/10 rounded p-2">
                                                    <div>{{ $Adicional->nm_adicional }}</div>
                                                    <div style="color: green;">
                                                        R$ {{ number_format($Adicional->vl_adicional, 2, ',', '.') }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>

                                <div class="flex flex-col justify-between items-end gap-2">

                                    <a {{-- href="{{ route('cardapio.carrinho.editar', ['Produto' => $arrProduto]) }}"
                                       class="px-3 py-1 text-white rounded bg-blue-600 hover:opacity-80 transition text-sm" --}}>
                                        Editar
                                    </a>
                                                <form method="post" action="{{ route('cardapio.carrinho.remover', ['index' => $index]) }}">
                                                    @csrf
                                                    <button class="px-3 py-1 text-white rounded bg-red-600 hover:opacity-80 transition text-sm">
                                                        Remover
                                                    </button>
                                                </form>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                    {{-- Observação --}}
                    <form method="post" action="{{ route('cardapio.confirmar') }}">
                        @csrf

                        <div class="mt-8">
                            <div class="text-sm uppercase tracking-wide text-white mb-2 mt-6">
                                Observações do pedido (opcional)
                            </div>
                            <textarea name="obs" rows="4" class="w-full rounded bg-black border border-white/20 p-3 text-white" placeholder="Alguma obserção especial?"></textarea>
                        </div>

                        {{-- Total --}}
                        <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4">
                            <div class="text-lg font-semibold">Total</div>
                            <div class="text-lg font-semibold" style="color: green;">
                                R$ {{ number_format($subTotalGeral,2,',','.') }}
                            </div>
                        </div>

                        {{-- Botões --}}
                        <div class="mt-6 flex justify-center gap-4">
                            <button class="px-6 py-2 rounded text-white hover:opacity-90 transition"
                                    style="background-color: darkgreen;">
                                Confirmar Pedido
                            </button>

                            <a href="{{ route('cardapio.index') }}"
                               class="px-5 py-2 rounded bg-gray-500 text-white hover:opacity-90 transition">
                                Voltar
                            </a>
                        </div>

                    </form>

                @endif

            </div>
        </div>
    </div>

</x-public-layout>
