<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Categorias</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5" style="background-color:#0f0f0f; padding: 20px;">
                    <form method="get" class="mb-4" style="margin:20px; text-align: center;">
                        <div class="flex gap-2">
                            <input name="q" value="{{ $q }}" placeholder="Buscar por nome"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0">
                            <button class="px-4 py-2 rounded bg-white text-black hover:opacity-90 transition"
                                    style="margin-left:10px; color: black;">Buscar
                            </button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-auto text-white bg-black">
                            <thead class="bg-black">
                            <tr class="border-b border-white/20">
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Código</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Nome</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                            </thead>
                            <tbody class="bg-black">
                            @forelse($categorias as $categoria)
                                <tr class="border-b border-white/10">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $categoria->cd_categoria }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $categoria->nm_categoria }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('categorias.edit', $categoria) }}"
                                               class="px-3 py-1 rounded hover:opacity-90"
                                               style="background:#fff;color:#000;margin-right:10px;">Editar</a>
                                            <form method="post" action="{{ route('categorias.destroy', $categoria) }}"
                                                  class="inline">
                                                @csrf @method('DELETE')
                                                <button data-confirm="Deseja remover esta Categoria?"
                                                        class="px-3 py-1 rounded bg-red-600 text-white hover:opacity-90">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-400">Nenhum registro.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 flex justify-center" style="margin-top:20px;">
                        <a href="{{ route('categorias.create') }}"
                           class="inline-flex items-center px-5 py-2 rounded bg-white text-black hover:opacity-90 transition"
                           style="color: black">Nova categoria</a>
                    </div>

                    <div class="mt-4">{{ $categorias->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
