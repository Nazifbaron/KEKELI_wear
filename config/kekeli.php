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
    'whatsapp' => env('KEKELI_WHATSAPP', '22901401349'),

    /*
    | Passerelle de paiement (fedapay | cinetpay | none)
    */
    'payment_gateway' => env('KEKELI_PAYMENT_GATEWAY', 'none'),
];
