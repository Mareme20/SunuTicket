<?php

namespace App\Support;

final class Phone
{
    /** Numéro mobile sénégalais à 9 chiffres : 70, 75, 76, 77 ou 78 + 7 chiffres. */
    public const RULE = '/^7[05678]\d{7}$/';

    /** Ramène "+221 77 123 45 67", "00221771234567" ou "77.123.45.67" à "771234567". */
    public static function normalize(?string $value): string
    {
        $digits = preg_replace('/\D+/', '', (string) $value);

        if (str_starts_with($digits, '00221')) {
            $digits = substr($digits, 5);
        } elseif (str_starts_with($digits, '221') && strlen($digits) === 12) {
            $digits = substr($digits, 3);
        }

        return $digits;
    }

    public static function pretty(?string $phone): string
    {
        return preg_replace('/^(\d{2})(\d{3})(\d{2})(\d{2})$/', '$1 $2 $3 $4', (string) $phone);
    }
}
