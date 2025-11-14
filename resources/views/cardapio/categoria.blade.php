<div class="text-white min-h-screen">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach($produtos as $p)
            <div class="border border-white/10 rounded p-6">
                <div class="flex items-center gap-4">
                    <div class="shrink-0 rounded overflow-hidden border border-white/10" style="width:120px;height:120px;background:#000;">
                        @if(!empty($p->img_b64) && !empty($p->img_mime))
                            <img src="data:{{ $p->img_mime }};base64,{{ $p->img_b64 }}" alt="{{ $p->nm_produto }}" class="w-full h-full object-contain">
                        @endif
                    </div>

                    <div class="max-w-0">
                        <div class="font-medium truncate">{{ $p->nm_produto }}</div>
                        <div class="text-sm text-gray-300">{{ $p->ds_produto }}</div>
                    </div>

                    <div class="flex items-center gap-4">
                        <span id="qtd-produto-{{ $p->cd_produto }}" class="w-6 text-center">0</span>
                            <button type="button" data-type="produto" data-id="{{ $p->cd_produto }}" class="btnMais px-3 py-1 rounded bg-white text-black font-bold text-green-600">+</button>
                        <span class="ml-3 text-sm text-gray-200">R$ {{ number_format($p->vl_valor,2,',','.') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
