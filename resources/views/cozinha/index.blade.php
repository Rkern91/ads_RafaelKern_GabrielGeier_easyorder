<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cozinha — Pedidos</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-black text-white">
<div class="relative min-h-screen" style="background-color:black;">
    <div class="fixed inset-0 z-0 pointer-events-none"
         style="background-image:url('{{ asset('images/bg-3840x2400.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed;opacity:.2"></div>

    <div class="relative z-10 min-h-screen flex flex-col">
        <header class="flex items-center justify-between px-4 md:px-6 h-16 border-b border-white/10" style="background-color:#0f0f0f;">
            <div class="flex items-center gap-3">
                <div class="font-semibold text-lg">Cozinha</div>
                <span class="text-xs text-gray-300 uppercase tracking-wide">Pedidos em andamento</span>
            </div>
            <div class="flex items-center gap-3">
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm px-3 py-1 rounded bg-white text-black hover:opacity-90 transition">Sair</button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-6">
            <div class="max-w-6xl mx-auto">
                <div class="inline-block w-full bg-black/60 text-white border border-white/20 rounded-lg p-5" style="background-color:#0f0f0f;">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-2 rounded text-green-400 border border-green-600" style="background-color:#000;display:inline-block;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h1 class="text-2xl font-semibold mb-4" style="text-align:center;">Pedidos em aberto / preparando</h1>

                    @if($pedidos->isEmpty())
                        <div class="text-center text-gray-300 py-10">Nenhum pedido no momento.</div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($pedidos as $p)
                                @php
                                    $listaProdutos = $itensPorPedido[$p->cd_pedido] ?? collect();
                                    $listaExtras = $adicionaisPorPedido[$p->cd_pedido] ?? collect();
                                @endphp
                                <div class="border border-white/10 rounded-lg p-4" style="background-color:#000;">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <div class="text-sm text-gray-300">Pedido #{{ $p->cd_pedido }}</div>
                                            <div class="text-xs text-gray-400">Mesa: {{ $p->nm_mesa ?? $p->cd_mesa ?? '—' }}</div>
                                        </div>
                                        <div>
                                            @if($p->id_status == 0)
                                                <span class="text-xs px-2 py-1 rounded bg-yellow-500/20 text-yellow-400 border border-yellow-500/40">Em aberto</span>
                                            @elseif($p->id_status == 1)
                                                <span class="text-xs px-2 py-1 rounded bg-blue-500/20 text-blue-400 border border-blue-500/40">Preparando</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="text-xs uppercase tracking-wide text-gray-400 mb-1">Produtos</div>
                                        @if($listaProdutos->isEmpty())
                                            <div class="text-sm text-gray-500">—</div>
                                        @else
                                            <ul class="space-y-1">
                                                @foreach($listaProdutos as $linha)
                                                    <li class="flex items-center justify-between gap-3">
                                                        <span class="text-sm">{{ $linha->qt_produto }} × {{ $linha->nm_produto }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <div class="text-xs uppercase tracking-wide text-gray-400 mb-1">Adicionais</div>
                                        @if($listaExtras->isEmpty())
                                            <div class="text-sm text-gray-500">—</div>
                                        @else
                                            <ul class="flex flex-wrap gap-1">
                                                @foreach($listaExtras as $ex)
                                                    <li class="text-xs px-2 py-1 rounded bg-white/5 border border-white/10">{{ $ex->nm_adicional }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <div class="text-xs uppercase tracking-wide text-gray-400 mb-1">Observação</div>
                                        <div class="text-sm text-gray-200">{{ $p->ds_observacao ?: '—' }}</div>
                                    </div>

                                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-white/10">
                                        <div class="text-sm text-gray-300">Total: R$ {{ number_format($p->vl_pedido, 2, ',', '.') }}</div>
                                        <div class="flex gap-2">
                                            @if($p->id_status == 0)
                                                <form method="post" action="{{ route('cozinha.preparar', $p->cd_pedido) }}">
                                                    @csrf
                                                    <button class="px-4 py-1 rounded bg-yellow-400 text-black text-sm hover:opacity-90 transition">Preparar</button>
                                                </form>
                                            @elseif($p->id_status == 1)
                                                <form method="post" action="{{ route('cozinha.servir', $p->cd_pedido) }}">
                                                    @csrf
                                                    <button class="px-4 py-1 rounded bg-green-400 text-black text-sm hover:opacity-90 transition">Servir</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>