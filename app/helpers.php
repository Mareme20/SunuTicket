<?php

if (! function_exists('fcfa')) {
    /** Formate un montant en FCFA : 5 350 FCFA */
    function fcfa(int|float $amount): string
    {
        return number_format($amount, 0, ',', "\u{00A0}")."\u{00A0}FCFA";
    }
}
