<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;

class PagamentoController extends Controller
{
  private function baseUrl(): string
  {
    $env = env('ASAAS_ENV', 'sandbox');

    return $env === 'production' ? 'https://api.asaas.com/v3' : 'https://api-sandbox.asaas.com/v3';
  }

  private function isSandbox(): bool
  {
    return env('ASAAS_ENV', 'sandbox') !== 'production';
  }

  private function http()
  {
    $key = env('ASAAS_API_KEY');

    return Http::withHeaders([
      'accept' => 'application/json',
      'content-type' => 'application/json',
      'access_token' => $key,
    ])->timeout(20);
  }

  public function show($pedidoId)
  {
    $p = DB::table('pedido')->where('cd_pedido', $pedidoId)->first();

    abort_if(!$p, 404);

    $valor = max((float) $p->vl_pedido, (float) env('ASAAS_MIN_PIX', 5.00));
    $base  = $this->baseUrl();
    $http  = $this->http();

    $costumerId = $p->ds_asaas_customer_id;

    if (!$costumerId)
    {
      $q = $http->get("$base/customers", ['externalReference' => "pedido-{$p->cd_pedido}", 'limit' => 1])->throw()->json();

      $costumerId = $q['data'][0]['id'] ?? null;

      if (!$costumerId)
      {
        $c = $http->post("$base/customers", [
          'name'              => 'Cliente Mesa '.($p->cd_mesa ?? '—'),
          'cpfCnpj'           => env('ASAAS_DEFAULT_CPF','04044566003'),
          'email'             => env('ASAAS_DEFAULT_EMAIL','gabrielgeier12@gmail.com'),
          'phone'             => env('ASAAS_DEFAULT_PHONE','54997056446'),
          'externalReference' => "pedido-{$p->cd_pedido}",
        ])->throw()->json();

        $costumerId = $c['id'];
      }

      DB::table('pedido')->where('cd_pedido', $p->cd_pedido)->update(['ds_asaas_customer_id' => $costumerId]);

      $p->ds_asaas_customer_id = $costumerId;
    }

    $pay = $p->ds_asaas_payment_id;

    if (!$pay)
    {
      $resp = $http->post("$base/payments", [
        'customer'          => $costumerId,
        'billingType'       => 'PIX',
        'value'             => $valor,
        'description'       => 'Pedido #'.$p->cd_pedido,
        'externalReference' => 'pedido-'.$p->cd_pedido,
        'dueDate'           => now()->toDateString(),
      ])->throw()->json();

      $pay = $resp['id'] ?? null;

      DB::table('pedido')->where('cd_pedido',$p->cd_pedido)->update(['ds_asaas_payment_id'=>$pay]);

      $p->ds_asaas_payment_id = $pay;
    }

    $qr = $http->get("$base/payments/$pay/pixQrCode")->throw()->json();

    return view('pagamento.index', [
      'pedido'    => $p,
      'qr'        => $qr,
      'qrImg'     => $qr['encodedImage'] ?? null,
      'payload'   => $qr['payload'] ?? null,
      'isSandbox' => env('ASAAS_ENV', 'sandbox') !== 'production',
    ]);
  }

  public function simular($pedidoId, \Illuminate\Http\Request $request)
  {
    $payload = (string) $request->input('payload', '');

    if ($payload === '')
      return back()->with('error', 'Payload PIX ausente.');

    $p = DB::table('pedido')->where('cd_pedido', $pedidoId)->first();

    if (!$p || empty($p->ds_asaas_payment_id))
      return back()->with('error', 'Pagamento não encontrado para este pedido.');

    $base  = $this->baseUrl();
    $http  = $this->http();
    $payId = $p->ds_asaas_payment_id;

    try
    {
      $http->post("$base/sandbox/payment/{$payId}/confirm")->throw();

      // ToDo: terá que ser consultado a cada 5 segundos
      $status = $http->get("$base/payments/{$payId}")->throw()->json();

      DB::table('pedido')->where('cd_pedido', $pedidoId)->update([
        'ds_asaas_response' => json_encode($status, JSON_UNESCAPED_UNICODE),
      ]);

      return redirect()->route('cardapio.index', ['mesa' => $p->cd_mesa])->with('success', 'Pagamento confirmado (sandbox).');
    }
    catch (\Illuminate\Http\Client\RequestException $e)
    {
      $json = $e->response?->json();
      $msg  = $json['errors'][0]['description'] ?? $e->response?->body() ?? 'Falha ao simular.';

      return back()->with('error', $msg);
    }
  }

  public function status($pedidoId)
  {
    $p = DB::table('pedido')->where('cd_pedido', $pedidoId)->first();

    abort_if(!$p, 404);
    abort_if(!$p->ds_asaas_payment_id, 404);

    $r = $this->http()->get($this->baseUrl() . "/payments/{$p->ds_asaas_payment_id}")->throw()->json();

    return response()->json(['status' => $r['status'] ?? null]);
  }
}