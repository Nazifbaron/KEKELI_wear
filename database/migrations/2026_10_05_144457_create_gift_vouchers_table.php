<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gift_vouchers', function (Blueprint $table) {
            $table->id();
            /*
            | Code unique du bon — ex: KW-GIFT-A3X9
            | Entré par le bénéficiaire comme un code promo
            */
            $table->string('code', 20)->unique();
            $table->decimal('initial_amount', 10, 0);
            $table->decimal('balance',        10, 0);
            /*
            | Acheteur du bon
            */
            $table->string('buyer_name',    120)->nullable();
            $table->string('buyer_phone',    20)->nullable();
            $table->string('buyer_email',   120)->nullable();
            /*
            | Bénéficiaire (optionnel — si l'acheteur précise pour qui)
            */
            $table->string('recipient_name',  120)->nullable();
            $table->string('recipient_phone',  20)->nullable();
            /*
            | Paiement KKiaPay
            */
            $table->string('payment_transaction_id')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])
                  ->default('pending');
            /*
            | Statut du bon
            | active   → peut être utilisé
            | used     → solde = 0, entièrement consommé
            | expired  → date dépassée
            | cancelled→ annulé par l'admin
            */
            $table->enum('status', ['pending', 'active', 'used', 'expired', 'cancelled'])
                  ->default('pending');
            /*
            | Dates
            */
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
        /*
        | Table des utilisations du bon
        | Chaque fois qu'un bon est utilisé sur une commande,
        | on enregistre ici le montant déduit et la commande liée.
        | Cela permet de voir l'historique complet des utilisations.
        */
        Schema::create('gift_voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_voucher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount_used',    10, 0);
            $table->decimal('balance_before', 10, 0);
            $table->decimal('balance_after',  10, 0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_voucher_usages');
        Schema::dropIfExists('gift_vouchers');
    }
};

