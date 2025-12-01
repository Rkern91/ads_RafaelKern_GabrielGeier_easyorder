<style>
  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }
  @keyframes payspin { to { transform: rotate(360deg); } }

  .spinner {
    display: inline-block;
    width: 18px;
    height: 18px;
    vertical-align: middle;
    border: 2px solid rgba(255, 255, 255, .25);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .8s linear infinite;
  }

  .spinner--off {
    display: none;
  }

  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
  }
</style>

<x-public-layout>
    @include('cardapio.navigation')

    <div class="min-w-full pt-6 mt-10">--</div>

    <div class="relative min-h-screen p-4 md:p-6">
        <div class="flex justify-center">
            <div
                    class="text-white border border-white/20 rounded-lg p-5 mx-auto"
                    style="background-color:#0f0f0f; width:fit-content; max-width:100%; padding: 30px 100px;"
            >
                <h1 class="text-2xl font-semibold mb-6 text-center">Pague com PIX</h1>

                <div class="flex justify-center mb-6">
                    @php $img = $qr['encodedImage'] ?? null; @endphp
                    @if($img)
                        <img src="data:image/png;base64,{{ $img }}" alt="QR Code PIX"
                             class="w-64 h-64 object-contain border border-white/10 rounded bg-black">
                    @else
                        <div class="w-64 h-64 border border-white/10 rounded bg-black flex items-center justify-center text-gray-400">
                            QR indisponível
                        </div>
                    @endif
                </div>

                <div class="text-center mb-2">
                    <div class="text-sm text-gray-300">Pedido</div>
                    <div class="text-lg font-semibold">#{{ $pedido->cd_pedido }}</div>
                </div>

                <div class="text-center mb-2">
                    <div class="text-sm text-gray-300">Valor</div>
                    <div class="text-2xl font-bold">
                        R$ {{ number_format((float)$pedido->vl_pedido, 2, ',', '.') }}
                    </div>
                </div>

                <div class="text-center mb-2">
                    <div class="text-sm text-gray-300">Tempo para pagar</div>
                    <div id="timer" class="text-2xl font-bold">05:00</div>
                </div>

                @php
                    $statusMap = [
                      'RECEIVED'                => 'Processando Pagamento',
                      'PENDING'                 => 'Aguardando Pagamento',
                      'AWAITING_RISK_ANALYSIS'  => 'Análise de risco',
                    ];
                    $label = $statusMap[$asaasStatus ?? 'PENDING'] ?? 'Aguardando Pagamento';
                @endphp

                <div class="text-center mb-4">
                    <div class="text-sm text-gray-300">Status do pagamento</div>
                    <div class="mt-1 inline-flex items-center gap-2 text-base font-medium">
                        <span id="payStatusText">{{ $label }}</span>
                        <span id="paySpinner"
                              style="display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,.25);
                 border-top-color:#fff;border-radius:50%;animation:payspin .8s linear infinite;"></span>
                    </div>

                    @if(session('info'))
                        <div class="mt-2 text-xs text-gray-400">{{ session('info') }}</div>
                    @endif
                </div>

                @php $payload = $qr['payload'] ?? null; @endphp
                @if($payload)
                    <div class="mt-4 text-center">
                        <button id="copyPayload"
                                class="inline-flex items-center px-4 py-2 rounded bg-white text-black hover:opacity-90 transition">
                            Copiar código PIX (copia e cola)
                        </button>
                        <textarea id="payloadText" class="sr-only">{{ $payload }}</textarea>
                    </div>
                @endif

                @if($payload && $isSandbox)
                    <div class="mt-6 text-center">
                        <form method="post" action="{{ route('pagamento.simular', $pedido->cd_pedido) }}"
                              class="inline-block">
                            @csrf
                            <input type="hidden" name="payload" value="{{ $payload }}">
                            <button class="inline-flex items-center px-4 py-2 rounded hover:opacity-90"
                                    style="background-color:darkgreen; color: white;">
                                Simular pagamento
                            </button>
                        </form>
                    </div>
                @endif

                <div class="mt-6 text-center">
                    <a href="{{ route('cardapio.index', ['mesa' => $pedido->cd_mesa]) }}"
                       class="inline-flex items-center px-5 py-2 rounded bg-gray-500 text-white hover:opacity-90 transition">
                        Voltar ao Cardápio
                    </a>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <script>
      (function () {
        var seconds = 5 * 60, el = document.getElementById('timer');
        var t = setInterval(function () {
          var m = Math.floor(seconds / 60), s = seconds % 60;
          el.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
          seconds--;
          if (seconds < 0) {
            clearInterval(t);
          }
        }, 1000);

        var btn = document.getElementById('copyPayload');
        if (btn) {
          btn.addEventListener('click', function () {
            var ta = document.getElementById('payloadText');
            if (!ta) return;
            ta.classList.remove('sr-only');
            ta.select();
            try {
              document.execCommand('copy');
            } catch (e) {
            }
            ta.classList.add('sr-only');
            btn.textContent = 'Código copiado!';
            setTimeout(function () {
              btn.textContent = 'Copiar código PIX (copia e cola)';
            }, 2000);
          });
        }

        var statusUrl  = "{{ route('pagamento.status', $pedido->cd_pedido) }}";
        var redirectOk = "{{ route('cardapio.index', ['mesa' => $pedido->cd_mesa]) }}" + "?success=1";
        var txt  = document.getElementById('payStatusText');
        var spin = document.getElementById('paySpinner');

        function labelFor(status){
          if (status === 'AWAITING_RISK_ANALYSIS') return 'Análise de risco';
          return 'Aguardando Pagamento';
        }

        function check(){
          fetch(statusUrl, { headers: { 'X-Requested-With':'XMLHttpRequest' }})
            .then(r => r.json())
            .then(j => {
              if (!j) return;

              if (['PENDING','AWAITING_RISK_ANALYSIS'].includes(j.status)) {
                if (txt)  txt.textContent = labelFor(j.status);
                if (spin) spin.style.display = 'inline-block';
              }

              if (['CONFIRMED','RECEIVED_IN_CASH','RECEIVED'].includes(j.status)) {
                if (txt)  txt.textContent = 'Pagamento confirmado!';
                if (spin) spin.style.display = 'none';

                sessionStorage.setItem('paid_swal', '1');

                setTimeout(function () {
                  window.location.href = redirectOk;
                }, 5000);
              }
            })
            .catch(()=>{});
        }

        check();
        setInterval(check, 5000);
      })();
    </script>
</x-public-layout>