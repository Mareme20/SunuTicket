<?php

return [
    // Pourcentages entiers : commission de la plateforme (ajoutée au prix) et frais PayDunya (prélevés sur le total payé).
    'commission_percent' => (int) env('COMMISSION_PERCENT', 7),
    'gateway_percent' => (int) env('GATEWAY_PERCENT', 3),

    // Une commande "en attente" bloque ses places pendant ce nombre de minutes.
    'hold_minutes' => (int) env('ORDER_HOLD_MINUTES', 20),
    'max_per_order' => (int) env('MAX_TICKETS_PER_ORDER', 10),

    'paydunya' => [
        'mode' => env('PAYDUNYA_MODE', 'test'),
        'master_key' => env('PAYDUNYA_MASTER_KEY'),
        'private_key' => env('PAYDUNYA_PRIVATE_KEY'),
        'public_key' => env('PAYDUNYA_PUBLIC_KEY'),
        'token' => env('PAYDUNYA_TOKEN'),
    ],

    'admin' => [
        'name' => env('ADMIN_NAME', 'Marième'),
        'phone' => env('ADMIN_PHONE', '770000000'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD', 'changez-moi-vite'),
    ],
];
