<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('session_id', 100);
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'session_id']); // 1 like par visiteur par produit
        });
    }

    public function down(): void { Schema::dropIfExists('product_likes'); }
};
