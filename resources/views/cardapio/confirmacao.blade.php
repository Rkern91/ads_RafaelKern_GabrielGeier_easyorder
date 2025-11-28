<x-public-layout>
    @include('cardapio.navigation')

    <div class="min-w-full pt-6 mt-10"></div>

    <div class="relative min-h-screen p-4 md:p-6">
        <div class="flex justify-center">

            <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5 w-full"
                 style="background-color:#0f0f0f; max-width:900px;">

                <h1 class="text-2xl font-semibold mb-6 text-center">
                    Conta da Mesa
                </h1>

                {{-- Se não tiver nenhum pedido --}}
                @if($Pedidos->isEmpty())
                    <div class="text-center text-gray-300">Mesa sem consumo</div>
                    <div class="text-center mt-4">
                        <a href="{{ route('cardapio.index') }}"
                           class="inline-block px-5 py-2 rounded bg-gray-500 text-white hover:opacity-90 transition">
                            Voltar ao menu
                        </a>
                    </div>
                @else

                    {{-- Loop dos pedidos --}}
                    <div class="space-y-8">

                        @foreach($Pedidos as $Pedido)
                            <div class="border border-white/10 rounded p-5" style="background-color:#141414;">

                                {{-- HEADER DO PEDIDO --}}
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <div class="text-lg font-semibold">
                                            Pedido #{{ $Pedido->cd_pedido }}
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            {{ \Carbon\Carbon::parse($Pedido->dt_pedido)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    @php
                                        $statusLabels = [
                                            0 => 'Em Aberto',
                                            1 => 'Preparando',
                                            2 => 'Servido'
                                        ];
                                        $statusColors = [
                                            0 => 'text-yellow-400',
                                            1 => 'text-blue-400',
                                            2 => 'text-green-400'
                                        ];
                                    @endphp

                                    <div class="text-sm font-semibold {{ $statusColors[$Pedido->id_status] }}">
                                        {{ $statusLabels[$Pedido->id_status] }}
                                    </div>
                                </div>

                                {{-- LISTA DE ITENS DO PEDIDO --}}
                                <div class="space-y-4">
                                    @foreach($Pedido->itens as $Item)
                                        <div class="border border-white/10 rounded p-3"
                                             style="background-color:#0f0f0f;">
                                            {{-- Produto principal --}}
                                            <div class="flex justify-between">
                                                <div>

                                                    <div class="font-semibold text-lg">
                                                        {{ $Item->produto->nm_produto }}
                                                    </div>

                                                    <div class="text-xs text-gray-300">
                                                        Quantidade: x{{ $Item->qt_produto }}
                                                    </div>

                                                    <div class="text-xs text-gray-300">
                                                        Unitário: R$
                                                        {{ number_format($Item->produto->vl_valor,2,',','.') }}
                                                    </div>
                                                </div>
                                                <div class="font-semibold" style="color: green;">
                                                    R$ {{ number_format($Item->produto->vl_valor * $Item->qt_produto,2,',','.') }}
                                                </div>
                                            </div>

                                            {{-- Adicionais --}}
                                            @if($Item->adicionais->isNotEmpty())
                                                <div class="mt-3 text-sm text-gray-400 uppercase">
                                                    Adicionais
                                                </div>

                                                @foreach($Item->adicionais as $arrAdicionalPedido)
                                                    <div class="flex justify-between text-sm mt-1 border border-white/10 rounded p-2">
                                                        <div>{{ $arrAdicionalPedido->adicional->nm_adicional }}</div>
                                                        <div style="color: green;">
                                                            R$ {{ number_format($arrAdicionalPedido->adicional->vl_adicional * $Item->qt_produto,2,',','.') }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                    @endforeach
                                </div>

                                {{-- Observações --}}
                                @if (!empty($Pedido->ds_observacao))
                                    <div class="mt-4">
                                        <div class="text-sm uppercase tracking-wide text-gray-400 mb-2">
                                            Observação
                                        </div>
                                        <div class="border border-white/10 rounded p-3">
                                            {{ $Pedido->ds_observacao }}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach

                            {{-- TOTAL DO PEDIDO --}}
                            <div class="mt-6 flex justify-between border-t border-white/10 pt-4">
                                <div class="text-lg font-semibold">Total do Pedido</div>
                                <div class="text-lg font-semibold" style="color: green;">
                                    R$ {{ number_format($vlTotalPedidos,2,',','.') }}
                                </div>
                            </div>

                            {{-- Botão para pagamento - implementar chamada da rota de pagamento --}}
                            <div class="mt-6 text-center">
                                <button class="px-6 py-2 rounded text-white hover:opacity-90 transition"
                                        style="background-color: darkgreen;"
                                        disabled>
                                    Realizar pagamento
                                </button>
                            </div>

                    </div>

                @endif
            </div>
        </div>
    </div>

</x-public-layout>
