<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Usuários</h2>
            <a href="{{ route('usuarios.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md">Novo usuário</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
            @endif

            <form method="get" class="mb-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Buscar por nome, e-mail, apelido ou CPF/CNPJ" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                    <select name="ds_cargo" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">Todos os cargos</option>
                        <option value="cozinha" @selected($cargo==='cozinha')>Cozinha</option>
                        <option value="adm" @selected($cargo==='adm')>ADM</option>
                        <option value="caixa" @selected($cargo==='caixa')>Caixa</option>
                    </select>
                    <button class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700">Filtrar</button>
                </div>
            </form>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium">Código</th>
                        <th class="px-4 py-3 text-left text-xs font-medium">Nome</th>
                        <th class="px-4 py-3 text-left text-xs font-medium">Apelido</th>
                        <th class="px-4 py-3 text-left text-xs font-medium">E-mail</th>
                        <th class="px-4 py-3 text-left text-xs font-medium">Cargo</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($usuarios as $u)
                        <tr>
                            <td class="px-4 py-2">{{ $u->cd_pessoa }}</td>
                            <td class="px-4 py-2">{{ $u->nm_pessoa }}</td>
                            <td class="px-4 py-2">{{ $u->ds_apelido }}</td>
                            <td class="px-4 py-2">{{ $u->ds_email }}</td>
                            <td class="px-4 py-2 uppercase">{{ $u->ds_cargo }}</td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('usuarios.edit', $u) }}" class="px-3 py-1 rounded bg-blue-600 text-white">Editar</a>
                                <form action="{{ route('usuarios.destroy', $u) }}" method="post" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 rounded bg-red-600 text-white" onclick="return confirm('Excluir este usuário?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Nenhum registro.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $usuarios->links() }}</div>
        </div>
    </div>
</x-app-layout>