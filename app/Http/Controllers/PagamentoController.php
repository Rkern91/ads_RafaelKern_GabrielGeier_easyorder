<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

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

    $valor = max((float)$p->vl_pedido, (float)env('ASAAS_MIN_PIX', 5.00));
    $base = $this->baseUrl();
    $http = $this->http();

    $customerId = $p->ds_asaas_customer_id;

    if (!$customerId)
    {
      $q = $http->get("$base/customers", [
        'externalReference' => "pedido-{$p->cd_pedido}",
        'limit' => 1
      ])->throw()->json();

      $customerId = $q['data'][0]['id'] ?? null;

      if (!$customerId)
      {
        $c = $http->post("$base/customers", [
          'name' => 'Cliente Mesa ' . ($p->cd_mesa ?? '—'),
          'cpfCnpj' => env('ASAAS_DEFAULT_CPF', '04044566003'),
          'email' => env('ASAAS_DEFAULT_EMAIL', 'gabrielgeier12@gmail.com'),
          'phone' => env('ASAAS_DEFAULT_PHONE', '54997056446'),
          'externalReference' => "pedido-{$p->cd_pedido}",
        ])->throw()->json();

        $customerId = $c['id'];
      }

      DB::table('pedido')->where('cd_pedido', $p->cd_pedido)->update(['ds_asaas_customer_id' => $customerId]);
      $p->ds_asaas_customer_id = $customerId;
    }

    $payId = $p->ds_asaas_payment_id;

    if (!$payId)
    {
      $resp = $http->post("$base/payments", [
        'customer' => $customerId,
        'billingType' => 'PIX',
        'value' => $valor,
        'description' => 'Pedido #' . $p->cd_pedido,
        'externalReference' => 'pedido-' . $p->cd_pedido,
        'dueDate' => now()->toDateString(),
      ])->throw()->json();

      $payId = $resp['id'] ?? null;

      DB::table('pedido')->where('cd_pedido', $p->cd_pedido)->update(['ds_asaas_payment_id' => $payId]);
      $p->ds_asaas_payment_id = $payId;
    }

    $payment   = $http->get("$base/payments/$payId")->throw()->json();
    $asaasStatus = $payment['status'] ?? null;

    DB::table('pedido')->where('cd_pedido', $pedidoId)->update([
      'ds_asaas_response' => json_encode($payment, JSON_UNESCAPED_UNICODE),
    ]);

    if (in_array($asaasStatus, ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED'], true))
    {
      DB::table('pedido')->where('cd_pedido', $pedidoId)->update(['id_status' => 3]);

      return redirect()
        ->route('cardapio.index', ['mesa' => $p->cd_mesa])
        ->with('success', 'Pagamento confirmado! Obrigado.');
    }

    $qr = null;
    $qrImg = null;

    if (in_array($asaasStatus, ['PENDING', 'AWAITING_RISK_ANALYSIS'], true))
    {
      try
      {
        $qr = $http->get("$base/payments/$payId/pixQrCode")->throw()->json();
        $qrImg = $qr['encodedImage'] ?? null;
      }
      catch (RequestException $e)
      {
        $qr = null;
        $qrImg = null;
      }
    }

    $categorias = DB::table('produto_categoria')->orderBy('nm_categoria')->get();
    $id_categoria_ativa = false;

    return view('pagamento.index', [
      'pedido' => $p,
      'qr' => $qr,
      'qrImg' => $qrImg,
      'payload' => $qr['payload'] ?? null,
      'isSandbox' => $this->isSandbox(),
      'categorias' => $categorias,
      'id_categoria_ativa' => $id_categoria_ativa,
      'asaasStatus' => $asaasStatus,
    ]);
  }

  public function simular($pedidoId, Request $request)
  {
    $payload = (string)$request->input('payload', '');

    if ($payload === '')
      return back()->with('error', 'Payload PIX ausente.');

    $p = DB::table('pedido')->where('cd_pedido', $pedidoId)->first();

    if (!$p || empty($p->ds_asaas_payment_id))
      return back()->with('error', 'Pagamento não encontrado para este pedido.');

    $base = $this->baseUrl();
    $http = $this->http();
    $payId = $p->ds_asaas_payment_id;

    try
    {
      $http->post("$base/payments/{$payId}/pixQrCode/simulate", ['qrCode' => $payload])->throw();
    }
    catch (Throwable $e) {}

    $payment = $http->get("$base/payments/$payId")->throw()->json();

    DB::table('pedido')->where('cd_pedido', $pedidoId)->update([
      'ds_asaas_response' => json_encode($payment, JSON_UNESCAPED_UNICODE),
    ]);

    return redirect()->route('pagamento.show', $pedidoId)->with('info', 'Processando pagamento...');
  }

  public function status($pedidoId)
  {
    $p = DB::table('pedido')->where('cd_pedido', $pedidoId)->first();
    abort_if(!$p, 404);
    abort_if(!$p->ds_asaas_payment_id, 404);

    $base = $this->baseUrl();
    $payment = $this->http()->get("$base/payments/{$p->ds_asaas_payment_id}")->throw()->json();

    DB::table('pedido')->where('cd_pedido', $pedidoId)->update([
      'ds_asaas_response' => json_encode($payment, JSON_UNESCAPED_UNICODE),
    ]);

    $status = $payment['status'] ?? null;
    $isPaid = in_array($status, ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED'], true);

    if ($isPaid)
    {
      DB::table('pedido')->where('cd_pedido', $pedidoId)->update(['id_status' => 3]);
      session()->flash('success', 'Pagamento confirmado! Obrigado.');
    }

    return response()->json([
      'status' => $status,
      'isPaid' => $isPaid,
    ]);
  }
}