<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /*
        |----------------------------------------------------------
        | PRODUCT_IMAGES — Images supplémentaires d'un produit
        | Max 6 images par produit (vérification dans le controller)
        | L'image principale reste dans products.main_image
        | Ces images sont les vues complémentaires (angles, détails)
        |----------------------------------------------------------
        */
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('path');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
