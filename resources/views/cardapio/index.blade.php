<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cardápio</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          @if(session('success'))
          Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: {!! json_encode(session('success')) !!},
            timer: 2500,
            showConfirmButton: false
          });
          @endif

          @if(session('error'))
          Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: {!! json_encode(session('error')) !!},
            confirmButtonText: 'Ok',
            confirmButtonColor: '#d63030',
            showConfirmButton: true
          });
          @endif
      });

      document.addEventListener('click', function (e) {
        // botão que tenha data-confirm attribute
        const btn = e.target.closest('[data-confirm]');
        if (!btn) return;

        e.preventDefault();
        const form = btn.closest('form');
        const message = btn.getAttribute('data-confirm') || 'Deseja confirmar?';

        Swal.fire({
          title: 'Atenção',
          text: message,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Sim',
          confirmButtonColor: '#d63030',
          cancelButtonText: 'Cancelar',
          cancelButtonColor: '#5ad630',
          allowOutsideClick: false,
          allowEscapeKey: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed)
            form.submit();
        });
      });
    </script>
</head>
<body class="bg-black text-white">
<div class="relative min-h-screen" style="background-color: black;">

    <div class="fixed inset-0 z-0 pointer-events-none" style="background-image:url('{{ asset('images/bg-3840x2400.jpg') }}');
                background-size:cover;
                background-position:center;
                background-attachment:fixed;
                opacity:.2"></div>

    <div class="relative z-10 min-h-screen flex">
        <aside class="w-64 hidden md:block border-r border-white/10" style="background-color:#0f0f0f;">
            <div class="p-4 text-sm uppercase tracking-wide text-gray-300">Produtos</div>
            <nav class="px-2 space-y-1">
                @foreach($categorias as $c)
                    <a href="{{ route('cardapio.categoria', ['categoria'=>$c->cd_categoria, 'mesa'=>$mesa]) }}"
                       class="block px-3 py-2 rounded {{ (isset($categoriaAtiva) && $categoriaAtiva==$c->cd_categoria) ? 'bg-white text-black' : 'hover:bg-white/10' }}">
                        {{ $c->nm_categoria }}
                    </a>
                @endforeach
            </nav>
            <div class="p-4 text-sm uppercase tracking-wide text-gray-300">Adicional</div>
            <nav class="px-2 pb-4">
                <a href="{{ route('cardapio.adicionais', ['mesa'=>$mesa]) }}"
                   class="block px-3 py-2 rounded {{ ($modo==='adicional') ? 'bg-white text-black' : 'hover:bg-white/10' }}">
                    Adicionais
                </a>
            </nav>
        </aside>

        <main class="flex-1">
            <header class="flex items-center justify-between px-4 md:px-6 h-16 border-b border-white/10"
                    style="background-color:#0f0f0f;">
                <div class="font-semibold text-lg">Faça seu Pedido!</div>
                <div class="relative">
                    <button id="btnCart" class="px-3 py-1 rounded border border-white/20 text-black">🛒</button>
                    <span id="badgeCart"
                          class="absolute -top-2 -right-2 text-xs bg-red-600 text-white rounded-full px-1 hidden">0</span>
                </div>
            </header>

            <div class="p-4 md:p-6">
                <div class="flex justify-center">
                    <div class="inline-block bg-black text-white border border-white/20 rounded-lg p-5"
                         style="background-color:#0f0f0f; padding:20px; width:100%; max-width:1200px;">

                        <h1 class="text-2xl font-semibold mb-4"
                            style="text-align:center;">{{ $titulo ?? 'Cardápio' }}</h1>

                        <form id="formPedido" method="post" action="{{ route('cardapio.confirmar') }}"
                              class="space-y-8">
                            @csrf
                            <input type="hidden" name="mesa" value="{{ $mesa }}">
                            <input type="hidden" id="payload" name="payload">

                            @if($modo==='todos')
                                @foreach($categorias as $cat)
                                    @php $lista = ($produtosPorCategoria[$cat->cd_categoria] ?? collect()); @endphp
                                    @if($lista->isNotEmpty())
                                        <section>
                                            <h2 class="text-xl font-semibold mb-3">{{ $cat->nm_categoria }}</h2>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                                @foreach($lista as $p)
                                                    <div class="border border-white/10 rounded p-3"
                                                         style="background-color:#0f0f0f; margin-bottom:10px; padding:5px;">
                                                        <div class="flex items-center gap-4">
                                                            <div class="shrink-0 rounded overflow-hidden border border-white/10"
                                                                 style="width:120px;height:120px;background:#000;">
                                                                @if(!empty($p->img_b64) && !empty($p->img_mime))
                                                                    <img src="data:{{ $p->img_mime }};base64,{{ $p->img_b64 }}"
                                                                         alt="{{ $p->nm_produto }}"
                                                                         class="w-full h-full object-contain">
                                                                @endif
                                                            </div>

                                                            <div class="flex items-center gap-2">
                                                                <button type="button" data-type="produto"
                                                                        data-id="{{ $p->cd_produto }}"
                                                                        class="btnMenos px-3 py-1 rounded bg-white text-black"
                                                                        style="color:red; font-weight:bolder;">−
                                                                </button>
                                                                <span id="qtd-produto-{{ $p->cd_produto }}"
                                                                      class="w-6 text-center" style="text-align:center">0</span>
                                                                <button type="button" data-type="produto"
                                                                        data-id="{{ $p->cd_produto }}"
                                                                        class="btnMais px-3 py-1 rounded bg-white text-black"
                                                                        style="color:green; font-weight:bolder;">+
                                                                </button>
                                                                <span class="ml-3 text-sm text-gray-200">R$ {{ number_format($p->vl_valor,2,',','.') }}</span>
                                                            </div>

                                                            <div class="min-w-0">
                                                                <div class="font-medium truncate">{{ $p->nm_produto }}</div>
                                                                <div class="text-sm text-gray-300 break-words">{{ $p->ds_produto }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </section>
                                    @endif
                                @endforeach

                                @if(isset($adicionais) && $adicionais->isNotEmpty())
                                    <section>
                                        <h2 class="text-xl font-semibold mt-6 mb-3">Adicionais</h2>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($adicionais as $a)
                                                <div class="border border-white/10 rounded p-3"
                                                     style="background-color:#0f0f0f; margin-bottom:10px; padding:5px;">
                                                    <div class="flex items-center gap-4">
                                                        <div class="shrink-0 rounded overflow-hidden border border-white/10"
                                                             style="width:120px;height:120px;background:#000;">
                                                            @if(!empty($a->img_b64) && !empty($a->img_mime))
                                                                <img src="data:{{ $a->img_mime }};base64,{{ $a->img_b64 }}"
                                                                     alt="{{ $a->nm_adicional }}"
                                                                     class="w-full h-full object-contain">
                                                            @endif
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <button type="button" data-type="adicional"
                                                                    data-id="{{ $a->cd_adicional }}"
                                                                    class="btnMenos px-3 py-1 rounded bg-white text-black"
                                                                    style="color:red; font-weight:bolder;">−
                                                            </button>
                                                            <span id="qtd-adicional-{{ $a->cd_adicional }}"
                                                                  class="w-6 text-center"
                                                                  style="text-align:center">0</span>
                                                            <button type="button" data-type="adicional"
                                                                    data-id="{{ $a->cd_adicional }}"
                                                                    class="btnMais px-3 py-1 rounded bg-white text-black"
                                                                    style="color:green; font-weight:bolder;">+
                                                            </button>
                                                            <span class="ml-3 text-sm text-gray-200">R$ {{ number_format($a->vl_adicional,2,',','.') }}</span>
                                                        </div>

                                                        <div class="min-w-0">
                                                            <div class="font-medium truncate">{{ $a->nm_adicional }}</div>
                                                            <div class="text-sm text-gray-300 break-words">{{ $a->ds_adicional }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif

                            @elseif($modo==='categoria')
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($itens as $p)
                                        <div class="border border-white/10 rounded p-3"
                                             style="background-color:#0f0f0f; margin-bottom:10px; padding:5px;">
                                            <div class="flex items-center gap-4">
                                                <div class="shrink-0 rounded overflow-hidden border border-white/10"
                                                     style="width:120px;height:120px;background:#000;">
                                                    @if(!empty($p->img_b64) && !empty($p->img_mime))
                                                        <img src="data:{{ $p->img_mime }};base64,{{ $p->img_b64 }}"
                                                             alt="{{ $p->nm_produto }}"
                                                             class="w-full h-full object-contain">
                                                    @endif
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <button type="button" data-type="produto"
                                                            data-id="{{ $p->cd_produto }}"
                                                            class="btnMenos px-3 py-1 rounded bg-white text-black"
                                                            style="color:red; font-weight:bolder;">−
                                                    </button>
                                                    <span id="qtd-produto-{{ $p->cd_produto }}" class="w-6 text-center"
                                                          style="text-align:center">0</span>
                                                    <button type="button" data-type="produto"
                                                            data-id="{{ $p->cd_produto }}"
                                                            class="btnMais px-3 py-1 rounded bg-white text-black"
                                                            style="color:green; font-weight:bolder;">+
                                                    </button>
                                                    <span class="ml-3 text-sm text-gray-200">R$ {{ number_format($p->vl_valor,2,',','.') }}</span>
                                                </div>

                                                <div class="min-w-0">
                                                    <div class="font-medium truncate">{{ $p->nm_produto }}</div>
                                                    <div class="text-sm text-gray-300 break-words">{{ $p->ds_produto }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @elseif($modo==='adicional')
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($itens as $a)
                                        <div class="border border-white/10 rounded p-3"
                                             style="background-color:#0f0f0f; margin-bottom:10px; padding:5px;">
                                            <div class="flex items-center gap-4">
                                                <div class="shrink-0 rounded overflow-hidden border border-white/10"
                                                     style="width:120px;height:120px;background:#000;">
                                                    @if(!empty($a->img_b64) && !empty($a->img_mime))
                                                        <img src="data:{{ $a->img_mime }};base64,{{ $a->img_b64 }}"
                                                             alt="{{ $a->nm_adicional }}"
                                                             class="w-full h-full object-contain">
                                                    @endif
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <button type="button" data-type="adicional"
                                                            data-id="{{ $a->cd_adicional }}"
                                                            class="btnMenos px-3 py-1 rounded bg-white text-black"
                                                            style="color:red; font-weight:bolder;">−
                                                    </button>
                                                    <span id="qtd-adicional-{{ $a->cd_adicional }}"
                                                          class="w-6 text-center" style="text-align:center">0</span>
                                                    <button type="button" data-type="adicional"
                                                            data-id="{{ $a->cd_adicional }}"
                                                            class="btnMais px-3 py-1 rounded bg-white text-black"
                                                            style="color:green; font-weight:bolder;">+
                                                    </button>
                                                    <span class="ml-3 text-sm text-gray-200">R$ {{ number_format($a->vl_adicional,2,',','.') }}</span>
                                                </div>

                                                <div class="min-w-0">
                                                    <div class="font-medium truncate">{{ $a->nm_adicional }}</div>
                                                    <div class="text-sm text-gray-300 break-words">{{ $a->ds_adicional }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-6">
                                <label class="block text-sm mb-2">Observação</label>
                                <textarea id="obs" name="obs"
                                          class="w-full rounded bg-black text-white border border-white/20 focus:border-white/40 focus:ring-0 p-2.5"
                                          style="color: black;" rows="3"
                                          placeholder="Ex.: sem cebola, ponto da carne, etc."></textarea>
                            </div>

                            <div class="mt-6 flex justify-center">
                                <button id="btnConfirmar" type="submit"
                                        class="px-6 py-2 rounded bg-white text-black hover:opacity-90 transition"
                                        style="color: black;">Confirmar pedido
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
  const state = {produtos: {}, adicionais: {}, total: 0};

  function clamp(n) {
    return n < 0 ? 0 : n;
  }

  function setQty(kind, id, qty) {
    qty = clamp(qty);
    if (kind === 'produto') {
      state.produtos[id] = qty;
      document.getElementById('qtd-produto-' + id).textContent = qty;
    } else {
      state.adicionais[id] = qty;
      document.getElementById('qtd-adicional-' + id).textContent = qty;
    }
    refreshBadge();
  }

  function refreshBadge() {
    const s = Object.values(state.produtos).reduce((a, b) => a + b, 0) + Object.values(state.adicionais).reduce((a, b) => a + b, 0);
    const b = document.getElementById('badgeCart');
    if (s > 0) {
      b.textContent = s;
      b.classList.remove('hidden');
    } else {
      b.classList.add('hidden');
    }
  }

  document.querySelectorAll('.btnMais').forEach(btn => {
    btn.addEventListener('click', () => {
      const t = btn.dataset.type, id = btn.dataset.id;
      const cur = t === 'produto' ? (state.produtos[id] || 0) : (state.adicionais[id] || 0);
      setQty(t, id, cur + 1);
    });
  });
  document.querySelectorAll('.btnMenos').forEach(btn => {
    btn.addEventListener('click', () => {
      const t = btn.dataset.type, id = btn.dataset.id;
      const cur = t === 'produto' ? (state.produtos[id] || 0) : (state.adicionais[id] || 0);
      setQty(t, id, cur - 1);
    });
  });
  const form = document.getElementById('formPedido');
  if (form) {
    form.addEventListener('submit', (e) => {
      const payload = {
        produtos: Object.entries(state.produtos).filter(([id, q]) => q > 0).map(([id, q]) => ({
          id: parseInt(id),
          qtd: q
        })),
        adicionais: Object.entries(state.adicionais).filter(([id, q]) => q > 0).map(([id, q]) => ({
          id: parseInt(id),
          qtd: q
        })),
        obs: (document.getElementById('obs')?.value || '').trim()
      };
      document.getElementById('payload').value = JSON.stringify(payload);
    });
  }
</script>
@if(session('carrinho_preview') && request('restore')==1)
    <script>
      (function () {
        const restore = @json(session('carrinho_preview.restore'));
        if (restore && restore.produtos) {
          Object.entries(restore.produtos).forEach(([id, obj]) => {
            const q = parseInt(obj.qtd || 0);
            if (q > 0) {
              setQty('produto', id, q);
            }
          });
        }
        if (restore && restore.adicionais) {
          Object.entries(restore.adicionais).forEach(([id, obj]) => {
            const q = parseInt(obj.qtd || 0);
            if (q > 0) {
              setQty('adicional', id, q);
            }
          });
        }
        if (restore && typeof restore.obs === 'string') {
          const ta = document.getElementById('obs');
          if (ta) ta.value = restore.obs;
        }
      })();
    </script>
@endif
</body>
</html>