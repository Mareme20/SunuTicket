<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\PromoCode;
use App\Services\Fees;
use App\Services\OrderFulfillment;
use App\Services\PayDunya;
use App\Support\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /** Crée la commande puis envoie l'acheteur payer chez PayDunya (Wave / Orange Money). */
    public function store(Request $request, Event $event, PayDunya $paydunya)
    {
        abort_unless($event->is_published && $event->starts_at->isFuture(), 404);

        $request->merge(['buyer_phone' => Phone::normalize($request->input('buyer_phone'))]);

        $data = $request->validate([
            'buyer_name' => ['required', 'string', 'max:100'],
            'buyer_phone' => ['required', 'regex:'.Phone::RULE],
            'buyer_email' => ['required', 'email', 'max:150'],
            'quantity' => ['required', 'integer', 'min:1', 'max:'.config('sunuticket.max_per_order')],
            'ticket_type' => ['nullable', 'string', 'max:80'],
            'promo_code' => ['nullable', 'string', 'max:80'],
        ]);

        $ticketTypeName = $request->input('ticket_type');
        $selectedType = $ticketTypeName ? $event->ticketType($ticketTypeName) : null;

        if ($ticketTypeName && ! $selectedType) {
            return back()->withInput()->withErrors(['ticket_type' => 'Ce tarif n’existe pas pour cet événement.']);
        }

        $unitPrice = $event->priceFor($ticketTypeName);
        $promoCode = $request->filled('promo_code') ? PromoCode::resolveFor($event, $request->input('promo_code')) : null;

        if ($request->filled('promo_code') && ! $promoCode) {
            return back()->withInput()->withErrors(['promo_code' => 'Ce code promo est invalide, expiré ou épuisé.']);
        }

        $discountAmount = $promoCode ? $promoCode->discountFor($unitPrice * (int) $data['quantity']) : 0;

        // On verrouille l'événement le temps de vérifier les places, pour éviter de vendre deux fois la dernière.
        $order = DB::transaction(function () use ($event, $data, $unitPrice, $discountAmount, $promoCode, $ticketTypeName) {
            $locked = Event::whereKey($event->id)->lockForUpdate()->first();

            if ($locked->remaining() < $data['quantity']) {
                return null;
            }

            return Order::create(array_merge($data, Fees::compute($unitPrice, $data['quantity'], $discountAmount), [
                'event_id' => $locked->id,
                'ticket_type' => $ticketTypeName,
                'promo_code' => $promoCode?->code,
                'discount_amount' => $discountAmount,
                'status' => 'pending',
            ]));
        });

        if (! $order) {
            return back()->withInput()->withErrors(['quantity' => 'Il ne reste pas assez de places pour cette quantité.']);
        }

        try {
            $invoice = $paydunya->createInvoice($order);
            $order->update(['paydunya_token' => $invoice['token']]);

            return redirect()->away($invoice['url']);
        } catch (\Throwable $e) {
            report($e);
            $order->update(['status' => 'failed']);

            $message = config('app.debug')
                ? 'Erreur PayDunya : '.$e->getMessage()
                : 'Le paiement est momentanément indisponible. Réessayez dans quelques minutes.';

            return back()->withInput()->withErrors([
                'payment' => $message,
            ]);
        }
    }

    /** Page de retour après PayDunya : on vérifie le paiement côté serveur avant de montrer les billets. */
    public function result(Order $order, OrderFulfillment $fulfillment)
    {
        $order = $fulfillment->sync($order)->loadMissing('event');

        if ($order->status === 'paid') {
            return redirect()->route('tickets.show', $order);
        }

        return view('checkout.status', compact('order'));
    }

    /** Notification serveur (IPN) envoyée par PayDunya. */
    public function ipn(Request $request, OrderFulfillment $fulfillment)
    {
        $data = (array) $request->input('data', []);
        $expected = hash('sha512', (string) config('sunuticket.paydunya.master_key'));

        abort_unless(hash_equals($expected, (string) ($data['hash'] ?? '')), 403);

        $token = $data['invoice']['token'] ?? null;
        $order = $token ? Order::where('paydunya_token', $token)->first() : null;

        if ($order) {
            $fulfillment->sync($order);
        }

        return response('ok');
    }
}
