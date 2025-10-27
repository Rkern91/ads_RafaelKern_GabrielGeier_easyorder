<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Editar adicional</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block text-white border border-white/20 rounded-lg p-6" style="background-color:#0f0f0f; width:100%; max-width:640px;">
                    <form id="formUpdateAdicional" method="post" action="{{ route('adicionais.update', $adicional) }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nm_adicional" class="block text-sm mb-2 text-white">Nome</label>
                            <input style="color: black;" id="nm_adicional" name="nm_adicional" value="{{ old('nm_adicional', $adicional->nm_adicional) }}" class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('nm_adicional') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="vl_adicional" class="block text-sm mb-2 text-white">Valor</label>
                            <input style="color: black;" id="vl_adicional" type="number" step="0.01" min="0" name="vl_adicional" value="{{ old('vl_adicional', $adicional->vl_adicional) }}" class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('vl_adicional') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="ds_adicional" class="block text-sm mb-2 text-white">Descrição</label>
                            <input style="color: black;" id="ds_adicional" name="ds_adicional" value="{{ old('ds_adicional', $adicional->ds_adicional) }}" class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('ds_adicional') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm text-white">Imagem</label>

                            @if(!empty($adicional->img_b64) && !empty($adicional->img_mime))
                                <div class="flex justify-center">
                                    <img id="preview_atual" src="data:{{ $adicional->img_mime }};base64,{{ $adicional->img_b64 }}" alt="Imagem do adicional" class="rounded border border-white/10 max-h-40 object-cover">
                                </div>
                            @endif

                            <div class="flex justify-center">
                                <img id="preview_novo" src="" alt="" class="rounded border border-white/10 max-h-40 object-cover hidden">
                            </div>

                            <input id="input_imagem" type="file" name="imagem" accept="image/*" class="w-full rounded bg-black text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-white file:text-black file:hover:opacity-90 border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                            <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="remover_imagem" value="1" class="rounded"> Remover imagem atual</label>
                            @error('imagem') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </form>

                    <div class="flex justify-center gap-4 pt-6">
                        <a href="{{ route('adicionais.index') }}" class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" style="background-color: gray">Voltar</a>
                        <button type="submit" form="formUpdateAdicional" class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" style="color: black">Salvar</button>
                        <form method="post" action="{{ route('adicionais.destroy', $adicional) }}" onsubmit="return confirm('Excluir este adicional?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-2 rounded bg-red-600 text-white hover:opacity-90 transition">Excluir</button>
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
          reader.onload = function(e) {
            novo.src = e.target.result;
            novo.classList.remove('hidden');
            if (atual) atual.classList.add('hidden');
          };
          reader.readAsDataURL(f);
        });
      }
    </script>
</x-app-layout>