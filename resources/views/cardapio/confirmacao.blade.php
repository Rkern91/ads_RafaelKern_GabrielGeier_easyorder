<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmação do Pedido</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-black text-white">
<div class="relative min-h-screen" style="background-color:black;">
    <div class="fixed inset-0 z-0 pointer-events-none"
         style="background-image:url('{{ asset('images/bg-3840x2400.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed;opacity:.2"></div>

    <div class="relative z-10 min-h-screen flex">
        <aside class="w-64 hidden md:block border-r border-white/10" style="background-color:#0f0f0f;">
            <div class="p-4 text-sm uppercase tracking-wide text-gray-300">Produtos</div>
            <nav class="px-2 space-y-1">
                @foreach($categorias as $c)
                    <a href="{{ route('cardapio.categoria', ['categoria'=>$c->cd_categoria, 'mesa'=>$mesa]) }}"
                       class="block px-3 py-2 rounded hover:bg-white/10">
                        {{ $c->nm_categoria }}
                    </a>
                @endforeach
            </nav>
            <div class="p-4 text-sm uppercase tracking-wide text-gray-300">Adicional</div>
            <nav class="px-2 pb-4">
                <a href="{{ route('cardapio.adicionais', ['mesa'=>$mesa]) }}"
                   class="block px-3 py-2 rounded hover:bg-white/10">Adicionais</a>
            </nav>
        </aside>

        <main class="flex-1">
            <header class="flex items-center justify-between px-4 md:px-6 h-16 border-b border-white/10"
                    style="background-color:#0f0f0f;">
                <div class="font-semibold text-lg">Confirme seu Pedido</div>
                <div class="relative">
                    <button class="px-3 py-1 rounded border border-white/20 text-black">🛒</button>
                </div>
            </header>

            <div class="p-4 md:p-6">
                <div class="flex justify-center">
                    <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5"
                         style="background-color:#0f0f0f; padding:20px; width:100%; max-width:900px;">

                        <h1 class="text-2xl font-semibold mb-4" style="text-align:center;">Revisão do Pedido</h1>

                        @php
                            $produtos = collect($cart['produtos'] ?? []);
                            $adicionais = collect($cart['adicionais'] ?? []);
                            $total = (float)($cart['total'] ?? 0);
                            $obs = $cart['obs'] ?? '';
                        @endphp

                        @if($produtos->isEmpty() && $adicionais->isEmpty())
                            <div class="text-center text-gray-300">Nenhum item selecionado.</div>
                        @else
                            @if($produtos->isNotEmpty())
                                <div class="mb-4">
                                    <div class="text-sm uppercase tracking-wide text-gray-300 mb-2">Produtos</div>
                                    <div class="space-y-2">
                                        @foreach($produtos as $i)
                                            <div class="flex items-center justify-between border border-white/10 rounded p-3"
                                                 style="background-color:#0f0f0f; padding: 5px; margin-bottom: 10px;">
                                                <div class="min-w-0">
                                                    <div class="font-medium">{{ $i['qtd'] }} × {{ $i['nome'] }}</div>
                                                    <div class="text-xs text-gray-300">
                                                        R$ {{ number_format($i['preco'],2,',','.') }} cada
                                                    </div>
                                                </div>
                                                <div class="font-medium">
                                                    R$ {{ number_format($i['subtotal'],2,',','.') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($adicionais->isNotEmpty())
                                <div class="mb-4">
                                    <div class="text-sm uppercase tracking-wide text-gray-300 mb-2">Adicionais</div>
                                    <div class="space-y-2">
                                        @foreach($adicionais as $i)
                                            <div class="flex items-center justify-between border border-white/10 rounded p-3"
                                                 style="background-color:#0f0f0f; padding: 5px; margin-bottom: 10px;">
                                                <div class="min-w-0">
                                                    <div class="font-medium">{{ $i['qtd'] }} × {{ $i['nome'] }}</div>
                                                    <div class="text-xs text-gray-300">
                                                        R$ {{ number_format($i['preco'],2,',','.') }} cada
                                                    </div>
                                                </div>
                                                <div class="font-medium">
                                                    R$ {{ number_format($i['subtotal'],2,',','.') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4">
                                <div class="text-sm uppercase tracking-wide text-gray-300 mb-2">Observação</div>
                                <div class="border border-white/10 rounded p-3"
                                     style="background-color:#0f0f0f; padding: 5px;">
                                    {{ $obs ?: '—' }}
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4">
                                <div class="text-lg font-semibold">Total</div>
                                <div class="text-lg font-semibold" style="color: green;">
                                    R$ {{ number_format($total,2,',','.') }}</div>
                            </div>

                            <div class="mt-6 flex justify-center gap-3">
                                <a href="{{ route('cardapio.index', ['mesa'=>$cart['mesa'], 'restore'=>1]) }}"
                                   class="px-6 py-2 rounded bg-gray-500 text-black hover:opacity-90 transition"
                                   style="color:white;">
                                    Alterar
                                </a>
                                <form method="post" action="{{ route('pedido.finalizar') }}">
                                    @csrf
                                    <button class="px-6 py-2 rounded text-black hover:opacity-90 transition"
                                            style="color:white; background-color: darkgreen">
                                        Enviar pedido
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>