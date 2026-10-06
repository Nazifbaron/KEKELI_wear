<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            /*
            | is_on_sale   → produit en promotion (activé par l'admin)
            | sale_price   → prix promotionnel (doit être < price)
            | sale_ends_at → date de fin de promo (null = sans limite)
            */
            $table->boolean('is_on_sale')->default(false)->after('price');
            $table->decimal('sale_price', 10, 0)->nullable()->after('is_on_sale');
            $table->timestamp('sale_ends_at')->nullable()->after('sale_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_on_sale', 'sale_price', 'sale_ends_at']);
        });
    }
};
