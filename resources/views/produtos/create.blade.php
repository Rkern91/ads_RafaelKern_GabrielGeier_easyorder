<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Novo produto</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="post" action="{{ route('produtos.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block mb-1 text-sm">Categoria</label>
                        <select name="cd_categoria" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Selecione</option>
                            @foreach($categorias as $c)
                                <option value="{{ $c->cd_categoria }}" @selected(old('cd_categoria')==$c->cd_categoria)>{{ $c->nm_categoria }}</option>
                            @endforeach
                        </select>
                        @error('cd_categoria') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">Nome</label>
                        <input name="nm_produto" value="{{ old('nm_produto') }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('nm_produto') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">Valor</label>
                        <input type="number" name="vl_valor" value="{{ old('vl_valor') }}" step="0.01" min="0" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('vl_valor') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">Descrição</label>
                        <input name="ds_produto" value="{{ old('ds_produto') }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('ds_produto') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('produtos.index') }}" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700">Cancelar</a>
                        <button class="px-4 py-2 rounded bg-gray-800 text-white">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>