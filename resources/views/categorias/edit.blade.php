<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">
            Editar categoria
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block text-white border border-white/20 rounded-lg p-6" style="background-color:#0f0f0f; width:100%; max-width:600px;">
                    <form method="post" action="{{ route('categorias.update', $categoria) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="nm_categoria" class="block text-sm mb-2 text-white">Nome da categoria</label>
                            <input id="nm_categoria" name="nm_categoria" style="color: black;" value="{{ old('nm_categoria', $categoria->nm_categoria) }}" placeholder="Ex: Bebidas, Comidas, Sobremesas..." class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('nm_categoria')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex justify-center gap-4 pt-6">
                            <a href="{{ route('categorias.index') }}" class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" style="background-color: gray;">Voltar</a>
                            <button type="submit" class="px-5 py-2 rounded bg-green-600 text-white hover:opacity-90 transition" style="background-color: white; color: black;">
                                Salvar
                            </button>
                            <form method="post" action="{{ route('categorias.destroy', $categoria) }}" onsubmit="return confirm('Excluir esta categoria?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-5 py-2 rounded bg-red-600 text-white hover:opacity-90 transition">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>