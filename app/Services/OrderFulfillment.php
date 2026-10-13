<?php

namespace App\Services;

use App\Mail\TicketsMail;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Vérifie auprès de PayDunya qu'une commande est réellement payée,
 * puis génère les billets (une seule fois, même si l'IPN et la page de retour arrivent en même temps).
 */
class OrderFulfillment
{
    public function __construct(private PayDunya $paydunya)
    {
    }

    public function sync(Order $order): Order
    {
        if ($order->status !== 'pending' || ! $order->paydunya_token) {
            return $order;
        }

        try {
            $result = $this->paydunya->confirm($order->paydunya_token);
        } catch (\Throwable $e) {
            report($e);

            return $order;
        }

        $status = $result['status'] ?? 'pending';

        if ($status === 'completed') {
            $paidAmount = (int) ($result['invoice']['total_amount'] ?? 0);

            if ($paidAmount !== (int) $order->total) {
                Log::warning('PayDunya: montant différent de la commande, billets non émis', [
                    'order' => $order->reference, 'attendu' => $order->total, 'reçu' => $paidAmount,
                ]);

                return $order;
            }

            return $this->fulfil($order);
        }

        if (in_array($status, ['cancelled', 'failed'], true)) {
            $order->update(['status' => 'failed']);
        }

        return $order->refresh();
    }

    private function fulfil(Order $order): Order
    {
        $newlyPaid = false;

        DB::transaction(function () use ($order, &$newlyPaid) {
            $locked = Order::whereKey($order->id)->lockForUpdate()->first();

            if ($locked->status === 'paid') {
                return;
            }

            $locked->update(['status' => 'paid', 'paid_at' => now()]);

            for ($i = 0; $i < $locked->quantity; $i++) {
                Ticket::create([
                    'order_id' => $locked->id,
                    'event_id' => $locked->event_id,
                    'code' => bin2hex(random_bytes(16)),
                    'holder_name' => $locked->buyer_name,
                ]);
            }

            $newlyPaid = true;
        });

        $order->refresh();

        if ($newlyPaid) {
            try {
                Mail::to($order->buyer_email)->queue(new TicketsMail($order));
            } catch (\Throwable $e) {
                // Le paiement est bon : on ne bloque pas l'acheteur si l'email échoue (il a la page de billets).
                report($e);
            }
        }

        return $order;
    }
}
