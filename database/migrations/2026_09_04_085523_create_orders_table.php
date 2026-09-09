<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /*
        |----------------------------------------------------------
        | ORDERS — une commande est créée quand le client
        | valide son panier et initie le paiement.
        |
        | Statuts :
        |   pending   → panier validé, paiement non encore effectué
        |   paid      → paiement confirmé
        |   processing→ en cours de confection
        |   shipped   → expédiée
        |   delivered → livrée
        |   cancelled → annulée
        |   refunded  → remboursée
        |----------------------------------------------------------
        */
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();           // ex: KW-2026-00142
            $table->string('session_id', 100);              // visiteur non connecté

            // Infos client
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->string('customer_email')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_country')->default('Bénin');

            // Montants
            $table->decimal('subtotal', 10, 0);             // avant remise
            $table->decimal('discount_amount', 10, 0)->default(0);
            $table->decimal('delivery_fee', 10, 0)->default(0);
            $table->decimal('total', 10, 0);                // montant final facturé

            // Promo
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();

            // Paiement
            $table->enum('payment_method', ['mtn_momo','moov_money','card','whatsapp'])->default('whatsapp');
            $table->enum('payment_status', ['pending','paid','failed','refunded'])->default('pending');
            $table->string('payment_reference')->nullable(); // référence retournée par la passerelle
            $table->timestamp('paid_at')->nullable();

            // Statut global
            $table->enum('status', ['pending','paid','processing','shipped','delivered','cancelled','refunded'])
                  ->default('pending');

            // Mensuration liée (si commande sur-mesure)
            $table->foreignId('measurement_id')->nullable()->constrained('measurements')->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
        });

        /*
        |----------------------------------------------------------
        | ORDER_ITEMS — lignes de la commande
        | Snapshot du produit au moment de la commande
        | (prix/nom peuvent changer après)
        |----------------------------------------------------------
        */
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');                 // snapshot nom
            $table->string('category_name')->nullable();    // snapshot catégorie
            $table->decimal('unit_price', 10, 0);          // snapshot prix unitaire
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 10, 0);            // unit_price * quantity
            $table->boolean('is_custom')->default(false);   // sur-mesure
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
