<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Editar usuário</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="post" action="{{ route('usuarios.update', $usuario) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @csrf
                    @method('PUT')
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-sm">Nome</label>
                        <input name="nm_pessoa" value="{{ old('nm_pessoa', $usuario->nm_pessoa) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('nm_pessoa') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">Apelido</label>
                        <input name="ds_apelido" value="{{ old('ds_apelido', $usuario->ds_apelido) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('ds_apelido') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">CPF/CNPJ</label>
                        <input name="nr_cpf_cnpj" value="{{ old('nr_cpf_cnpj', $usuario->nr_cpf_cnpj) }}" maxlength="14" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('nr_cpf_cnpj') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">E-mail</label>
                        <input type="email" name="ds_email" value="{{ old('ds_email', $usuario->ds_email) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('ds_email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">Senha</label>
                        <input type="password" name="ds_senha" maxlength="20" placeholder="Deixe em branco para manter" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('ds_senha') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-sm">Cargo</label>
                        <select name="ds_cargo" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                            <option value="cozinha" @selected(old('ds_cargo', $usuario->ds_cargo ?? '')==='cozinha')>Cozinha</option>
                            <option value="adm" @selected(old('ds_cargo', $usuario->ds_cargo ?? '')==='adm')>ADM</option>
                            <option value="caixa" @selected(old('ds_cargo', $usuario->ds_cargo ?? '')==='caixa')>Caixa</option>
                        </select>
                        @error('ds_cargo') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="sm:col-span-2 flex gap-2 mt-2">
                        <a href="{{ route('usuarios.index') }}" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700">Cancelar</a>
                        <button class="px-4 py-2 rounded bg-gray-800 text-white">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>