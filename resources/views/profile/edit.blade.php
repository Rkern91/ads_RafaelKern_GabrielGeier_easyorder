<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Meu perfil</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') === 'profile-updated')
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">Dados salvos.</div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="post" action="{{ route('profile.update') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @csrf
                    @method('PATCH')

                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-sm">Nome</label>
                        <input name="nm_pessoa" value="{{ old('nm_pessoa', $user->nm_pessoa) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('nm_pessoa') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">Apelido</label>
                        <input name="ds_apelido" value="{{ old('ds_apelido', $user->ds_apelido) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('ds_apelido') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">E-mail</label>
                        <input type="email" name="ds_email" value="{{ old('ds_email', $user->ds_email) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('ds_email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-sm">Senha</label>
                        <input type="password" name="ds_senha" placeholder="Deixe em branco para manter" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('ds_senha') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="sm:col-span-2 mt-2">
                        <button class="px-4 py-2 rounded bg-gray-800 text-white">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>