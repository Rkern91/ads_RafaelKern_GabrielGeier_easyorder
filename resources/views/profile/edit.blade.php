<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Meu perfil</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') === 'profile-updated')
                <div class="flex justify-center mb-6">
                    <div class="px-4 py-2 rounded text-green-400 border border-green-600"
                         style="background-color:#000; display:inline-block; text-align:center;">
                        Dados salvos.
                    </div>
                </div>
            @endif

            <div class="flex justify-center">
                <div class="inline-block text-white border border-white/20 rounded-lg p-6"
                     style="background-color:#0f0f0f; width:100%; max-width:720px;">
                    <form method="post" action="{{ route('profile.update') }}"
                          class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @csrf
                        @method('PATCH')

                        <div class="sm:col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">Nome</label>
                            <input style="color: black" name="nm_pessoa"
                                   value="{{ old('nm_pessoa', $user->nm_pessoa) }}"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('nm_pessoa')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="sm:col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">Apelido</label>
                            <input style="color: black" name="ds_apelido"
                                   value="{{ old('ds_apelido', $user->ds_apelido) }}"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('ds_apelido')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="sm:col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">E-mail</label>
                            <input style="color: black" type="email" name="ds_email"
                                   value="{{ old('ds_email', $user->ds_email) }}"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('ds_email')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="sm:col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">Senha</label>
                            <input style="color: black" type="password" name="ds_senha"
                                   placeholder="Deixe em branco para manter"
                                   class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('ds_senha')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </form>

                    <div class="flex justify-center gap-4 pt-6">
                        <button form="none"
                                onclick="document.querySelector('form[action=\'{{ route('profile.update') }}\']').submit();"
                                class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition"
                                style="color: black">Salvar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>