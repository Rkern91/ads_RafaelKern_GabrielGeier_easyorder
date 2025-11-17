<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pagamento — Pedido #{{ $pedido->cd_pedido }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-black text-white">
<div class="relative min-h-screen" style="background-color:black;">
    <div class="fixed inset-0 z-0 pointer-events-none" style="background-image:url('{{ asset('images/bg-3840x2400.jpg') }}');background-size:cover;background-position:center;background-attachment:fixed;opacity:.2"></div>

    <div class="relative z-10 min-h-screen flex flex-col">
        <header class="flex items-center justify-between px-4 md:px-6 h-16 border-b border-white/10" style="background-color:#0f0f0f;">
            <div class="font-semibold text-lg">Pagamento</div>
            <div class="text-sm text-gray-300">Pedido #{{ $pedido->cd_pedido }}</div>
        </header>

        <main class="flex-1 p-4 md:p-6">
            <div class="max-w-xl mx-auto">
                @if(session('error') || !empty($error))
                    <div class="flex justify-center mb-4">
                        <div class="px-4 py-2 rounded text-red-400 border border-red-600" style="background-color:#000;">
                            {{ session('error') ?? $error }}
                        </div>
                    </div>
                @endif

                @if(session('debug'))
                    <details class="mb-4">
                        <summary class="cursor-pointer text-sm text-gray-300">DEBUG</summary>
                        <pre class="text-xs whitespace-pre-wrap break-words mt-2">
                            {{ json_encode(session('debug'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) }}
                        </pre>
                    </details>
                @endif

                <div class="w-full border border-white/20 rounded-lg p-5 text-center" style="background-color:#0f0f0f;">
                    <h1 class="text-2xl font-semibold mb-6 w-fit mx-auto" style="text-align: center; margin-top: 20px;">Pague com PIX</h1>

                    <div class="flex justify-center mb-6">
                        @if(!empty($qrImg))
                            <img src="data:image/png;base64,{{ $qrImg }}" alt="QR Code PIX" class="w-64 h-64 object-contain border border-white/10 rounded bg-black">
                        @else
                            <div class="w-64 h-64 border border-white/10 rounded bg-black flex items-center justify-center text-gray-400">
                                QR indisponível
                            </div>
                        @endif
                    </div>

                    @if($payload && $isSandbox)
                        <div class="text-center mb-4" style="text-align: center">
                            <form method="post" action="{{ route('pagamento.simular', $pedido->cd_pedido) }}" class="inline-block">
                                @csrf
                                <input type="hidden" name="payload" value="{{ $payload }}">
                                <button class="inline-flex items-center px-4 py-2 rounded text-black hover:opacity-90" style="background-color:darkgreen">
                                    Simular pagamento (Sandbox)
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="mb-2 flex flex-col items-center">
                        <div class="text-sm text-gray-300">Valor</div>
                        <div class="text-2xl font-bold">
                            R$ {{ number_format((float) $pedido->vl_pedido, 2, ',', '.') }}
                        </div>
                    </div>

                    <div class="mb-6 flex flex-col items-center">
                        <div class="text-sm text-gray-300">Tempo para pagar</div>
                        <div id="timer" class="text-2xl font-bold">05:00</div>
                    </div>

                    @if($payload)
                        <div class="mt-4 text-center" style="text-align: center">
                            <button id="copyPayload" style="color: black" class="inline-flex items-center px-4 py-2 rounded bg-white text-black hover:opacity-90 transition mx-auto">
                                Copiar código PIX (copia e cola)
                            </button>
                            <textarea id="payloadText" class="sr-only">{{ $payload }}</textarea>
                        </div>
                    @endif

                    <div class="mt-6 text-center" style="text-align: center">
                        <a href="{{ route('cardapio.index', ['mesa' => $pedido->cd_mesa]) }}" class="inline-flex items-center px-5 py-2 rounded bg-white text-black hover:opacity-90 transition" style="background-color:dimgray;margin-bottom:20px;">
                            Voltar ao Cardápio
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
  (function(){
    var seconds = 5 * 60;

    var el = document.getElementById('timer');

    var t = setInterval(function(){
      var m = Math.floor(seconds / 60);
      var s = seconds % 60;

      el.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
      seconds--;

      if (seconds < 0)
        clearInterval(t);
    }, 1000);

    var btn = document.getElementById('copyPayload');

    if (btn)
    {
      btn.addEventListener('click', function() {
        var ta = document.getElementById('payloadText');

        if (!ta)
          return;

        ta.classList.remove('sr-only');
        ta.select();
        try {
          document.execCommand('copy');
        }
        catch(e) {}

        ta.classList.add('sr-only');
        btn.textContent = 'Código copiado!';

        setTimeout(function(){ btn.textContent = 'Copiar código PIX (copia e cola)'; }, 2000);
      });
    }
  })();
</script>
</body>
</html>