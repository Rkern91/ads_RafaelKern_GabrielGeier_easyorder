<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Novo adicional</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block text-white border border-white/20 rounded-lg p-6"
                     style="background-color:#0f0f0f; width:100%; max-width:640px;">
                    <form id="formCreateAdicional" method="post" action="{{ route('adicionais.store') }}"
                          class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="nm_adicional" class="block text-sm mb-2 text-white">Nome</label>
                            <input style="color: black;" id="nm_adicional" name="nm_adicional"
                                   value="{{ old('nm_adicional') }}"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('nm_adicional')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="vl_adicional" class="block text-sm mb-2 text-white">Valor</label>
                            <input style="color: black;" id="vl_adicional" type="number" step="0.01" min="0"
                                   name="vl_adicional" value="{{ old('vl_adicional') }}"
                                   class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('vl_adicional')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="ds_adicional" class="block text-sm mb-2 text-white">Descrição</label>
                            <input id="ds_adicional" style="color: black;" name="ds_adicional"
                                   value="{{ old('ds_adicional') }}"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('ds_adicional')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-sm mb-2 text-white">Imagem</label>
                            <input type="file" name="imagem" accept="image/*"
                                   class="w-full rounded bg-black text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-white file:text-black file:hover:opacity-90 border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                            @error('imagem')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </form>

                    <div class="flex justify-center gap-4 pt-6">
                        <a href="{{ route('adicionais.index') }}"
                           class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition"
                           style="background-color: gray;">Voltar</a>
                        <button type="submit" form="formCreateAdicional"
                                class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition"
                                style="color: black">Salvar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>