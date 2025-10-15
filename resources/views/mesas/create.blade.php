<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Nova mesa</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center">
                <div class="inline-block text-white border border-white/20 rounded-lg p-6" style="background-color:#0f0f0f; width:100%; max-width:600px;">
                    <form id="formCreateMesa" method="post" action="{{ route('mesas.store') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="nm_mesa" class="block text-sm mb-2 text-white">Nome da mesa</label>
                            <input id="nm_mesa" name="nm_mesa" style="color: black;" value="{{ old('nm_mesa') }}" placeholder="Ex: Mesa 01" class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0 p-2.5">
                            @error('nm_mesa')
                            <div class="text-sm text-red-400 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>

                    <div class="flex justify-center gap-4 pt-6">
                        <a href="{{ route('mesas.index') }}" class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" style="background-color: gray;">Voltar</a>
                        <button type="submit" form="formCreateMesa" class="px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" style="color: black">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>