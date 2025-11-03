<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Adicionais</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5"
                     style="background-color:#0f0f0f; padding:20px;">
                    <form method="get" class="mb-4" style="margin:20px; text-align:center;">
                        <div class="flex gap-2">
                            <input name="q" value="{{ $q }}" placeholder="Buscar por nome"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0">
                            <button class="px-4 py-2 rounded bg-white text-black hover:opacity-90 transition"
                                    style="margin-left:10px; color:black;">Buscar
                            </button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-auto text-white bg-black">
                            <thead class="bg-black">
                            <tr class="border-b border-white/20">
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Código</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Nome</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Valor</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Descrição</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                            </thead>
                            <tbody class="bg-black">
                            @forelse($adicionais as $a)
                                <tr class="border-b border-white/10">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $a->cd_adicional }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $a->nm_adicional }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        R$ {{ number_format($a->vl_adicional,2,',','.') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $a->ds_adicional }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('adicionais.edit', $a) }}"
                                               class="px-3 py-1 rounded hover:opacity-90"
                                               style="background:#fff;color:#000;margin-right:10px;">Editar</a>
                                            <form method="post" action="{{ route('adicionais.destroy', $a) }}"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button data-confirm="Deseja remover este Adicional?"
                                                        class="px-3 py-1 rounded bg-red-600 text-white hover:opacity-90">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">Nenhum registro.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 flex justify-center" style="margin-top:20px;">
                        <a href="{{ route('adicionais.create') }}" style="color:black"
                           class="inline-flex items-center px-5 py-2 rounded bg-white text-black hover:opacity-90 transition">Novo
                            adicional</a>
                    </div>

                    <div class="mt-4">{{ $adicionais->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>