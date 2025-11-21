<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Editar produto</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block text-white border border-white/20 rounded-lg p-6"
                     style="background-color:#0f0f0f; width:100%; max-width:640px;">
                    <form id="formUpdateProduto" method="post" action="{{ route('produtos.update', $produto) }}"
                          class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="cd_categoria" class="block text-sm mb-2 text-white">Categoria</label>
                            <select id="cd_categoria" name="cd_categoria"
                                    class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                                @foreach($categorias as $c)
                                    <option value="{{ $c->cd_categoria }}" @selected(old('cd_categoria', $produto->cd_categoria)==$c->cd_categoria)>{{ $c->nm_categoria }}</option>
                                @endforeach
                            </select>
                            @error('cd_categoria')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="nm_produto" class="block text-sm mb-2 text-white">Nome</label>
                            <input id="nm_produto" name="nm_produto"
                                   value="{{ old('nm_produto', $produto->nm_produto) }}"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('nm_produto')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="vl_valor" class="block text-sm mb-2 text-white">Valor</label>
                            <input id="vl_valor" type="number" name="vl_valor"
                                   value="{{ old('vl_valor', $produto->vl_valor) }}" step="0.01" min="0"
                                   class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('vl_valor')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="ds_produto" class="block text-sm mb-2 text-white">Descrição</label>
                            <input id="ds_produto" name="ds_produto"
                                   value="{{ old('ds_produto', $produto->ds_produto) }}"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('ds_produto')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm text-white">Imagem</label>

                            @if(!empty($produto->img_b64) && !empty($produto->img_mime))
                                <div class="flex justify-center">
                                    <img id="preview_atual"
                                         src="data:{{ $produto->img_mime }};base64,{{ $produto->img_b64 }}"
                                         alt="Imagem do produto"
                                         class="rounded border border-white/10 max-h-40 object-cover">
                                </div>
                            @endif

                            <div class="flex justify-center" style="padding: 20px;">
                                <img id="preview_novo" src="" alt=""
                                     class="rounded border border-white/10 max-h-40 object-cover hidden">
                            </div>

                            <input id="input_imagem" type="file" name="imagem" accept="image/*"
                                   class="w-full rounded bg-black text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-white file:text-black file:hover:opacity-90 border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                            <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox"
                                                                                         name="remover_imagem" value="1"
                                                                                         class="rounded"> Remover imagem
                                atual</label>
                            @error('imagem')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </form>

                    <div class="flex justify-center gap-4 pt-6">
                        <a href="{{ route('produtos.index') }}"
                           class="px-5 py-2 rounded text-black hover:opacity-90 transition" style="background-color:gray;">Voltar</a>
                        <button type="submit" form="formUpdateProduto"
                                class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition">Salvar
                        </button>
                        <form method="post" action="{{ route('produtos.destroy', $produto) }}"
                              onsubmit="return confirm('Excluir este produto?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-5 py-2 rounded bg-red-600 text-white hover:opacity-90 transition">Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
      const input = document.getElementById('input_imagem');
      const novo = document.getElementById('preview_novo');
      const atual = document.getElementById('preview_atual');
      if (input) {
        input.addEventListener('change', function () {
          const f = this.files && this.files[0] ? this.files[0] : null;
          if (!f) return;
          const reader = new FileReader();
          reader.onload = function (e) {
            novo.src = e.target.result;
            novo.classList.remove('hidden');
            if (atual) atual.classList.add('hidden');
          };
          reader.readAsDataURL(f);
        });
      }
    </script>
</x-app-layout>