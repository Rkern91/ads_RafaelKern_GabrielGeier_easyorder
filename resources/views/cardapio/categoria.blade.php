<div class="min-w-full pt-6 mt-5">
    --
</div>
<div class="min-w-full">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        @foreach($produtos as $produto)
            <div class="bg-neutral-primary-soft block max-w-sm p-6 border border-default rounded-xl shadow-xs">

                <div class="rounded-base sm:flex flex-1 border border-white/10 mb-4"
                     style="width:100%; height:160px; background:#000;">
                    @if(!empty($produto->img_b64) && !empty($produto->img_mime))
                        <img src="data:{{ $produto->img_mime }};base64,{{ $produto->img_b64 }}"
                             alt="{{ $produto->nm_produto }}"
                             class="w-full h-full object-contain">
                    @endif
                </div>

                <div>
                  <h5 class="text-white mb-3 text-2xl font-semibold tracking-tight text-heading leading-8">
                      {{ $produto->nm_produto }}
                  </h5>

                  <p class="text-white text-body mb-4">
                      {{ $produto->ds_produto }}
                  </p>
                </div>

                <div class="text-xl font-semibold text-green-800 mb-4">
                    R$ {{ number_format($produto->vl_valor, 2, ',', '.') }}
                </div>

                <a href="{{ route('cardapio.produto.adicional', ['produto' => $produto]) }}" data-type="produto" data-id="{{ $produto->cd_produto }}"
                   class="inline-flex items-center hover:opacity-90 transition cursor-pointer w-full justify-center text-white dark:bg-white/10 dark:hover:bg-white/5
                          focus:ring-4 focus:ring-green-500 shadow-xs font-medium leading-5 rounded-lg text-sm px-4 py-2.5 transition">
                    Adicionar ao pedido
                    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5 text-white" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         fill="none" viewBox="0 0 24 24">
                        <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 12H5m14 0-4 4m4-4-4-4"/>
                    </svg>
                </a>
            </div>
        @endforeach
    </div>
</div>
