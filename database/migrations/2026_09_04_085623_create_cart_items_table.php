<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /*
        |----------------------------------------------------------
        | CART_ITEMS — panier temporaire par session.
        | Converti en order_items lors du checkout.
        | Nettoyé automatiquement après 48h (schedule).
        |----------------------------------------------------------
        */
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100)->index();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();
            $table->foreignId('measurement_id')->nullable()->constrained('measurements')->nullOnDelete();
            $table->timestamps();
            $table->unique(['session_id', 'product_id']); // 1 ligne par produit par session
        });
    }

    public function down(): void { Schema::dropIfExists('cart_items'); }
};
