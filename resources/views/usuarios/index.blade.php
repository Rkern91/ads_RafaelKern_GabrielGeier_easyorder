<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Usuários</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5" style="background-color:#0f0f0f; padding:20px;">
                    <form method="get" class="mb-4" style="margin:20px; text-align:center;">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 w-full">
                            <input name="q" value="{{ $q }}" placeholder="Buscar por nome, e-mail, apelido ou CPF/CNPJ" class="rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0">
                            <select name="ds_cargo" class="rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0" style="color:black;">
                                <option value="">Todos os cargos</option>
                                <option value="cozinha" @selected($cargo==='cozinha')>Cozinha</option>
                                <option value="adm" @selected($cargo==='adm')>ADM</option>
                                <option value="caixa" @selected($cargo==='caixa')>Caixa</option>
                            </select>
                            <button class="px-4 py-2 rounded bg-white text-black hover:opacity-90 transition" style="color:black;">Filtrar</button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-auto text-white bg-black">
                            <thead class="bg-black">
                            <tr class="border-b border-white/20">
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Código</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Nome</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Apelido</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">E-mail</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Cargo</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                            </thead>
                            <tbody class="bg-black">
                            @forelse($usuarios as $u)
                                <tr class="border-b border-white/10">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $u->cd_pessoa }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $u->nm_pessoa }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $u->ds_apelido }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $u->ds_email }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap uppercase">{{ $u->ds_cargo }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('usuarios.edit', $u) }}" class="px-3 py-1 rounded hover:opacity-90" style="background:#fff;color:#000;margin-right:10px;">Editar</a>
                                            <form method="post" action="{{ route('usuarios.destroy', $u) }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button data-confirm="Deseja remover este Usuário?" class="px-3 py-1 rounded bg-red-600 text-white hover:opacity-90">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Nenhum registro.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 flex justify-center" style="margin-top:20px;">
                        <a href="{{ route('usuarios.create') }}" class="inline-flex items-center px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" style="color:black;">Novo usuário</a>
                    </div>

                    <div class="mt-4">{{ $usuarios->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>