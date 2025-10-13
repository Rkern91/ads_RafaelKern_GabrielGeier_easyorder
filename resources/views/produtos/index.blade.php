<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Produtos</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="flex justify-center mb-6">
                    <div class="px-4 py-2 rounded text-green-400 border border-green-600" style="background-color:#000; display:inline-block; text-align:center; color: green; font-weight: bold;">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="flex justify-center mb-6">
                    <div class="px-4 py-2 rounded text-red-400 border border-red-600" style="background-color:#000; display:inline-block; text-align:center; color: red; font-weight: bold;">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="flex justify-center">
                <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5" style="background-color:#0f0f0f; padding:20px;">
                    <form method="get" class="mb-4" style="margin:20px; text-align:center;">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 w-full">
                            <input name="q" value="{{ $q }}" placeholder="Buscar por nome" class="rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0">
                            <select name="cd_categoria" class="rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0" style="color:black;">
                                <option value="">Todas categorias</option>
                                @foreach($categorias as $c)
                                    <option value="{{ $c->cd_categoria }}" @selected($cat==$c->cd_categoria)>{{ $c->nm_categoria }}</option>
                                @endforeach
                            </select>
                            <button class="px-4 py-2 rounded bg-white text-black hover:opacity-90 transition" style="color:black;">Buscar</button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-auto text-white bg-black">
                            <thead class="bg-black">
                            <tr class="border-b border-white/20">
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Código</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Nome</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Categoria</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Valor</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                            </thead>
                            <tbody class="bg-black">
                            @forelse($produtos as $p)
                                <tr class="border-b border-white/10">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $p->cd_produto }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $p->nm_produto }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ optional($p->categoria)->nm_categoria }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">R$ {{ number_format($p->vl_valor,2,',','.') }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('produtos.edit', $p) }}" class="px-3 py-1 rounded hover:opacity-90" style="background:#fff;color:#000;margin-right:10px;">Editar</a>
                                            <form method="post" action="{{ route('produtos.destroy', $p) }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button class="px-3 py-1 rounded bg-red-600 text-white hover:opacity-90" onclick="return confirm('Excluir este produto?')">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Nenhum registro.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 flex justify-center" style="margin-top:20px;">
                        <a href="{{ route('produtos.create') }}" class="inline-flex items-center px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" style="color:black;">Novo produto</a>
                    </div>

                    <div class="mt-4">{{ $produtos->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>