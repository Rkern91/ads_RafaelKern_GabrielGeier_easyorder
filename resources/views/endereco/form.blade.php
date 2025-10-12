<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Endereço do Restaurante</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="post" action="{{ route('endereco.save') }}" class="grid grid-cols-2 gap-4">
                    @csrf

                    <div>
                        <label class="block mb-1 text-sm">Cidade</label>
                        <input name="nm_cidade" value="{{ old('nm_cidade', optional($endereco)->nm_cidade) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('nm_cidade') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">UF</label>
                        @php($ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'])
                        <select name="nm_uf" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Selecione</option>
                            @foreach($ufs as $uf)
                                <option value="{{ $uf }}" @selected(old('nm_uf', optional($endereco)->nm_uf) === $uf)>{{ $uf }}</option>
                            @endforeach
                        </select>
                        @error('nm_uf') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-1 text-sm">Bairro</label>
                        <input name="nm_bairro" value="{{ old('nm_bairro', optional($endereco)->nm_bairro) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('nm_bairro') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-1 text-sm">Logradouro</label>
                        <input name="nm_logradouro" value="{{ old('nm_logradouro', optional($endereco)->nm_logradouro) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('nm_logradouro') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-sm">CEP</label>
                        <input type="text" name="nr_cep" value="{{ old('nr_cep', optional($endereco)->nr_cep) }}" pattern="\d{5}-?\d{3}" maxlength="9" inputmode="text" placeholder="00000-000" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('nr_cep') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">Número</label>
                        <input type="number" name="nr_endereco" value="{{ old('nr_endereco', optional($endereco)->nr_endereco) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('nr_endereco') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-1 text-sm">Complemento</label>
                        <input name="ds_complemento" value="{{ old('ds_complemento', optional($endereco)->ds_complemento) }}" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
                        @error('ds_complemento') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-span-2 flex gap-2 mt-2">
                        <button class="px-4 py-2 rounded bg-gray-800 text-white">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>