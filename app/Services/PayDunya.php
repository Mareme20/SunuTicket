<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PayDunya
{
    private function baseUrl(): string
    {
        return config('sunuticket.paydunya.mode') === 'live'
            ? 'https://app.paydunya.com/api/v1'
            : 'https://app.paydunya.com/sandbox-api/v1';
    }

    private function http(): PendingRequest
    {
        $keys = config('sunuticket.paydunya');

        return Http::acceptJson()->asJson()->timeout(25)->withHeaders([
            'PAYDUNYA-MASTER-KEY' => (string) $keys['master_key'],
            'PAYDUNYA-PRIVATE-KEY' => (string) $keys['private_key'],
            'PAYDUNYA-PUBLIC-KEY' => (string) $keys['public_key'],
            'PAYDUNYA-TOKEN' => (string) $keys['token'],
        ]);
    }

    /** Crée la facture PayDunya et retourne ['token' => ..., 'url' => page de paiement]. */
    public function createInvoice(Order $order): array
    {
        $order->loadMissing('event');
        $event = $order->event;

        $response = $this->http()->post($this->baseUrl().'/checkout-invoice/create', [
            'invoice' => [
                'total_amount' => $order->total,
                'description' => "{$order->quantity} billet(s) - {$event->title}",
                'items' => [
                    'item_0' => [
                        'name' => $event->title,
                        'quantity' => $order->quantity,
                        'unit_price' => $order->unit_price,
                        'total_price' => $order->subtotal,
                        'description' => 'Billet d\'entrée',
                    ],
                ],
                'taxes' => [
                    'tax_0' => [
                        'name' => 'Frais de service SunuTicket',
                        'amount' => $order->commission,
                    ],
                ],
            ],
            'store' => ['name' => config('app.name')],
            'actions' => [
                'cancel_url' => route('checkout.return', $order->reference),
                'return_url' => route('checkout.return', $order->reference),
                'callback_url' => route('paydunya.ipn'),
            ],
            'custom_data' => ['order_reference' => $order->reference],
        ]);

        $data = $response->json() ?? [];

        if (! $response->successful() || ($data['response_code'] ?? null) !== '00' || empty($data['token'])) {
            Log::error('PayDunya: création de facture refusée', ['status' => $response->status(), 'body' => $data]);
            throw new RuntimeException($data['response_text'] ?? 'PayDunya est indisponible.');
        }

        return ['token' => $data['token'], 'url' => $data['response_text']];
    }

    /** Interroge PayDunya sur l'état réel d'une facture (source de vérité, jamais l'URL de retour). */
    public function confirm(string $token): array
    {
        $response = $this->http()->get($this->baseUrl().'/checkout-invoice/confirm/'.$token);

        $data = $response->json() ?? [];

        if (! $response->successful()) {
            Log::warning('PayDunya: confirmation de facture indisponible', [
                'status' => $response->status(),
                'token' => $token,
                'body' => $data,
            ]);
        }

        return $data;
    }
}
