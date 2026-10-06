<?php

return [
    /*
    | Score = likes × 2 + views × 0.5
    | Seuil pour marquage automatique "coup de cœur"
    */
    'featured_threshold' => env('KEKELI_FEATURED_THRESHOLD', 50),

    /*
    | Nombre max de coups de cœur affichés en section tendances
    */
    'max_featured' => env('KEKELI_MAX_FEATURED', 6),

    /*
    | Numéro WhatsApp principal de la boutique
    */
    'whatsapp' => env('KEKELI_WHATSAPP', '2290140134949'),
    /*
    | Numéro WhatsApp secondaire de la boutique (optionnel)
    */
    'whatsapp_secondary' => env('KEKELI_WHATSAPP_SECONDARY', '2290000000000'),
    'payment_gateway' => env('KEKELI_PAYMENT_GATEWAY', 'none'),
    'kkiapay_env'        => env('KKIAPAY_ENV', 'sandbox'),
    'kkiapay_public_key' => env('KKIAPAY_PUBLIC_KEY', ''),
    'kkiapay_private_key'=> env('KKIAPAY_PRIVATE_KEY', ''),
];


