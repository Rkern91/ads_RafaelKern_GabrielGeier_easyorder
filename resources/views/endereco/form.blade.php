<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Endereço do Restaurante</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="flex justify-center mb-6">
                    <div class="px-4 py-2 rounded text-green-400 border border-green-600" style="background-color:#000; display:inline-block; text-align:center; color: green; font-weight: bold">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
                @if(session('error'))
                    <div class="flex justify-center mb-6">
                        <div class="px-4 py-2 rounded text-red-400 border border-red-600" style="background-color:#000; display:inline-block; text-align:center; color: red; font-weight: bold;">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

            <div class="flex justify-center">
                <div class="inline-block text-white border border-white/20 rounded-lg p-6" style="background-color:#0f0f0f; width:100%; max-width:720px;">
                    <form method="post" action="{{ route('endereco.save') }}" class="grid grid-cols-2 gap-4">
                        @csrf

                        <div class="col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">Cidade</label>
                            <input style="color: black;" name="nm_cidade" value="{{ old('nm_cidade', optional($endereco)->nm_cidade) }}" class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                            @error('nm_cidade') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">UF</label>
                            @php($ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'])
                            <select style="color: black;" name="nm_uf" class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                                <option value="">Selecione</option>
                                @foreach($ufs as $uf)
                                    <option value="{{ $uf }}" @selected(old('nm_uf', optional($endereco)->nm_uf) === $uf)>{{ $uf }}</option>
                                @endforeach
                            </select>
                            @error('nm_uf') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">Bairro</label>
                            <input style="color: black;" name="nm_bairro" value="{{ old('nm_bairro', optional($endereco)->nm_bairro) }}" class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                            @error('nm_bairro') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">Logradouro</label>
                            <input style="color: black;" name="nm_logradouro" value="{{ old('nm_logradouro', optional($endereco)->nm_logradouro) }}" class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                            @error('nm_logradouro') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">CEP</label>
                            <input style="color: black;" type="text" name="nr_cep" value="{{ old('nr_cep', optional($endereco)->nr_cep) }}" pattern="\d{5}-?\d{3}" maxlength="9" inputmode="text" placeholder="00000-000" class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                            @error('nr_cep') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">Número</label>
                            <input style="color: black;" type="number" name="nr_endereco" value="{{ old('nr_endereco', optional($endereco)->nr_endereco) }}" class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                            @error('nr_endereco') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-span-2 mb-2">
                            <label class="block mb-1 text-sm text-white">Complemento</label>
                            <input style="color: black;" name="ds_complemento" value="{{ old('ds_complemento', optional($endereco)->ds_complemento) }}" class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2">
                            @error('ds_complemento') <div class="text-sm text-red-400 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-span-2 flex justify-center gap-4 mt-4 mb-2">
                            <a href="{{ url()->previous() }}" class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" style="background-color: gray">Voltar</a>
                            <button class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" type="submit" style="color: black">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>