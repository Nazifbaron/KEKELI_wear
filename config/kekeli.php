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
    | Passerelle de paiement (fedapay | cinetpay | none)
    */
    'payment_gateway' => env('KEKELI_PAYMENT_GATEWAY', 'none'),
    /*
    | Passerelle de paiement FedaPay
    | Documentation : https://docs.fedapay.com
    | Modes : sandbox (test) | live (production)
    | Clé publique → injectée dans le widget JS frontend
    | Clé secrète  → utilisée côté serveur pour vérifier les transactions
    */
    'fedapay_env'        => env('FEDAPAY_ENV', 'sandbox'),
    'fedapay_public_key' => env('FEDAPAY_PUBLIC_KEY', 'pk_sandbox_xxx'),
    'fedapay_secret_key' => env('FEDAPAY_SECRET_KEY', 'sk_sandbox_xxx'),
];
