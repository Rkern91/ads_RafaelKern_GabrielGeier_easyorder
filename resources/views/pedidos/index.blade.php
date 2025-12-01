@php use Illuminate\Support\Carbon; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align:center;">Pedidos</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5"
                     style="background-color:#0f0f0f; padding:20px;">

                    @if(session('success'))
                        <div class="mb-4 px-4 py-2 rounded text-green-400 border border-green-600"
                             style="background:#000; display:inline-block;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="get" class="mb-4" style="margin:20px; text-align:center;">
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 w-full">
                            <input
                                name="q"
                                value="{{ $q }}"
                                placeholder="Buscar por #pedido / mesa / nome"
                                class="rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0"/>

                            <input
                                name="mesa"
                                value="{{ $mesa }}"
                                placeholder="Filtrar por mesa"
                                class="rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0"/>

                            <select
                                name="status"
                                class="rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0">
                                <option value="">Todos os status</option>
                                <option value="0" @selected($status==='0')>Em Aberto</option>
                                <option value="1" @selected($status==='1')>Preparando</option>
                                <option value="2" @selected($status==='2')>Servido</option>
                                <option value="3" @selected($status==='3')>Concluído</option>
                            </select>

                            <button class="px-4 py-2 rounded bg-white text-black hover:opacity-90 transition">
                                Buscar
                            </button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto text-white bg-black">
                            <thead class="bg-black">
                            <tr class="border-b border-white/20">
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide">#</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide">Mesa</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide">Valor</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide">Data</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                            </thead>
                            <tbody class="bg-black">
                            @forelse($pedidos as $p)
                                @php
                                    // Cores atualizadas (0 amarelo, 1 azul, 2 laranja, 3 verde)
                                    $badge = match((int)$p->id_status){
                                      0 => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/40',
                                      1 => 'bg-blue-500/20 text-blue-300 border-blue-500/40',
                                      2 => 'bg-orange-500/20 text-orange-300 border-orange-500/40',
                                      3 => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40',
                                      default => 'bg-white/10 text-gray-300 border-white/10'
                                    };
                                    $txt = match((int)$p->id_status){
                                      0 => 'Em Aberto',
                                      1 => 'Preparando',
                                      2 => 'Servido',
                                      3 => 'Concluído',
                                      default => '—'
                                    };
                                @endphp
                                <tr class="border-b border-white/10">
                                    <td class="px-4 py-2 whitespace-nowrap">#{{ $p->cd_pedido }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $p->nm_mesa ?? $p->cd_mesa ?? '—' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        R$ {{ number_format($p->vl_pedido,2,',','.') }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="text-xs px-2 py-1 rounded border {{ $badge }}">{{ $txt }}</span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        {{ Carbon::parse($p->dt_pedido)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if((int)$p->id_status !== 3)
                                            <form method="post" action="{{ route('pedidos.finalizar', $p->cd_pedido) }}" class="inline">
                                                @csrf
                                                <button class="px-3 py-1 rounded bg-white text-black hover:opacity-90">
                                                    Concluir
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-400">Nenhum registro.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $pedidos->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
