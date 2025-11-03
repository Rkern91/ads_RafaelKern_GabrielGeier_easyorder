<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Novo produto</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block text-white border border-white/20 rounded-lg p-6"
                     style="background-color:#0f0f0f; width:100%; max-width:640px;">
                    <form id="formCreateProduto" method="post" action="{{ route('produtos.store') }}" class="space-y-6"
                          enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="cd_categoria" class="block text-sm mb-2 text-white">Categoria</label>
                            <select id="cd_categoria" name="cd_categoria" style="color: black;"
                                    class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                                <option value="">Selecione</option>
                                @foreach($categorias as $c)
                                    <option value="{{ $c->cd_categoria }}" @selected(old('cd_categoria')==$c->cd_categoria)>{{ $c->nm_categoria }}</option>
                                @endforeach
                            </select>
                            @error('cd_categoria')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="nm_produto" class="block text-sm mb-2 text-white">Nome</label>
                            <input id="nm_produto" name="nm_produto" style="color: black;"
                                   value="{{ old('nm_produto') }}"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('nm_produto')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="vl_valor" class="block text-sm mb-2 text-white">Valor</label>
                            <input id="vl_valor" type="number" name="vl_valor" style="color: black;"
                                   value="{{ old('vl_valor') }}" step="0.01" min="0"
                                   class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('vl_valor')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="ds_produto" class="block text-sm mb-2 text-white">Descrição</label>
                            <input id="ds_produto" name="ds_produto" style="color: black;"
                                   value="{{ old('ds_produto') }}"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('ds_produto')
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
                        <a href="{{ route('produtos.index') }}" style="background-color: gray;"
                           class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition">Voltar</a>
                        <button type="submit" form="formCreateProduto" style="color: black;"
                                class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition">Salvar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>