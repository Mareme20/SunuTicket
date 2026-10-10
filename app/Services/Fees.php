<?php

namespace App\Services;

/**
 * Calcul des frais (tout en FCFA entiers).
 *
 *  - l'acheteur paie : prix + commission (7 %)
 *  - PayDunya prélève 3 % du total payé
 *  - l'organisateur reçoit le prix complet
 *  - la plateforme garde : commission - frais PayDunya
 *
 * Exemple : billet à 5 000 → commission 350, total 5 350, PayDunya 160, bénéfice 190.
 */
final class Fees
{
    public static function compute(int $unitPrice, int $quantity, int $discountAmount = 0): array
    {
        $baseSubtotal = $unitPrice * $quantity;
        $subtotal = max(0, $baseSubtotal - $discountAmount);
        $commission = (int) round($subtotal * config('sunuticket.commission_percent') / 100);
        $total = $subtotal + $commission;
        $gatewayFee = intdiv($total * config('sunuticket.gateway_percent'), 100);

        return [
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
            'commission' => $commission,
            'total' => $total,
            'gateway_fee' => $gatewayFee,
            'admin_net' => $commission - $gatewayFee,
        ];
    }
}
