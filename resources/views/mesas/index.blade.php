<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight" style="text-align: center;">Mesas</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-center">
                <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5"
                     style="background-color:#0f0f0f; padding:20px;">
                    <form method="get" class="mb-4" style="margin:20px; text-align:center;">
                        <div class="flex gap-2">
                            <input name="q" value="{{ $q }}" placeholder="Buscar por nome"
                                   class="w-full rounded bg-black text-white placeholder-gray-400 border border-white/20 focus:border-white/40 focus:ring-0">
                            <button style="color:black; margin-left:10px;"
                                    class="px-4 py-2 rounded bg-white text-black hover:opacity-90 transition">Buscar
                            </button>
                        </div>
                    </form>

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
                                        <a href="{{ route('mesas.edit', $mesa) }}"
                                           class="px-3 py-1 rounded hover:opacity-90"
                                           style="background-color:white; color:black; margin-right:10px;">Editar</a>
                                        <form action="{{ route('mesas.destroy', $mesa) }}" method="post"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-confirm="Deseja remover esta Mesa?"
                                                    class="px-3 py-1 rounded bg-red-600 text-white">
                                                Excluir
                                            </button>
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

                    <div class="mt-5 flex justify-center" style="margin-top:20px;">
                        <a style="color:black;" href="{{ route('mesas.create') }}"
                           class="inline-flex items-center px-5 py-2 rounded bg-white text-black hover:opacity-90 transition">Nova
                            mesa</a>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="mb-6 border-white/20 rounded-lg p-4"
                             style="background-color:#0f0f0f; margin-top: 20px;">
                            <div class="text-sm text-gray-300 mb-2">Vincular este dispositivo a uma mesa</div>
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                <select id="selectMesaDispositivo"
                                        class="rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 w-full sm:w-auto">
                                    <option value="">Selecione a mesa…</option>
                                    @foreach($mesas as $m)
                                        <option value="{{ $m->cd_mesa }}">{{ $m->nm_mesa ?? 'Mesa' }}</option>
                                    @endforeach
                                </select>

                                <button id="btnSalvarMesaDispositivo"
                                        class="px-4 py-2 rounded bg-white text-black hover:opacity-90 transition"
                                        style="color:black; margin-top: 10px;">Definir neste dispositivo
                                </button>

                                <button id="btnLimparMesaDispositivo" style="background-color: gray;"
                                        class="px-4 py-2 rounded bg-gray-600 text-white hover:opacity-90 transition">
                                    Limpar
                                </button>

                                <span id="mesaAtualInfo" class="text-sm text-gray-300 sm:ml-3"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">{{ $mesas->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
      function fireSwal(text, icon = 'success', title = 'Sucesso') {
        Swal.fire({
          icon: icon,
          title: title,
          text: text,
          timer: 2500,
          showConfirmButton: false
        });
      }

      (function () {
        const KEY = 'cd_mesa';
        const sel = document.getElementById('selectMesaDispositivo');
        const info = document.getElementById('mesaAtualInfo');
        const btnSave = document.getElementById('btnSalvarMesaDispositivo');
        const btnClear = document.getElementById('btnLimparMesaDispositivo');

        function renderInfo() {
          const v = localStorage.getItem(KEY);
          info.textContent = v ? ('') : 'Nenhuma mesa definida neste dispositivo';
          if (v) sel.value = v;
        }

        btnSave.addEventListener('click', function () {
          const v = sel.value;
          if (!v) {
            fireSwal('Selecione uma mesa.', 'error', 'Erro');
            return;
          }
          try {
            localStorage.setItem(KEY, String(v));
            renderInfo();
            fireSwal('Mesa ' + v + ' definida para este dispositivo.');
          } catch (e) {
            console.error(e);
            fireSwal('Não foi possível salvar no dispositivo.', 'error', 'Erro');
          }
        });

        btnClear.addEventListener('click', function () {
          try {
            localStorage.removeItem(KEY);
            sel.value = '';
            renderInfo();
            fireSwal('Mesa deste dispositivo removida.');
          } catch (e) {
            console.error(e);
            fireSwal('Não foi possível limpar no dispositivo.', 'error', 'Erro');
          }
        });

        renderInfo();
      })();
    </script>
</x-app-layout>