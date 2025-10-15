<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Mesas</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="flex justify-center mb-6">
                    <div class="px-4 py-2 rounded text-green-400 border border-green-600" style="background-color:#000; color: green; font-weight: bold; display:inline-block; text-align:center;">
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
                <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5" style="background-color:#0f0f0f; padding:20px;">
                    <form method="get" class="mb-4" style="margin:20px; text-align:center;">
                        <div class="flex gap-2">
                            <input name="q" value="{{ $q }}" placeholder="Buscar por nome" class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0">
                            <button style="color:black; margin-left:10px;" class="px-4 py-2 rounded bg-white text-black hover:opacity-90 transition">Buscar</button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-auto text-white bg-black">
                            <thead class="bg-black">
                            <tr class="border-b border-white/20">
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Código</th>
                                <th class="px-4 py-2 text-xs font-semibold uppercase tracking-wide">Nome</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                            </thead>
                            <tbody class="bg-black">
                            @forelse($mesas as $mesa)
                                <tr class="border-b border-white/10">
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $mesa->cd_mesa }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $mesa->nm_mesa }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('mesas.edit', $mesa) }}" class="px-3 py-1 rounded hover:opacity-90" style="background-color:white; color:black; margin-right:10px;">Editar</a>
                                            <form action="{{ route('mesas.destroy', $mesa) }}" method="post" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-3 py-1 rounded bg-red-600 text-white hover:opacity-90" onclick="return confirm('Excluir esta mesa?')">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-400">Nenhum registro.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 flex justify-center" style="margin-top:20px;">
                        <a style="color:black;" href="{{ route('mesas.create') }}" class="inline-flex items-center px-5 py-2 rounded bg-white text-black hover:opacity-90 transition">Nova mesa</a>
                    </div>

                    <div class="mt-4">{{ $mesas->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>