<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 0)->nullable();       // null = sur devis
            $table->string('main_image')->nullable();
            $table->json('images')->nullable();                 // galerie
            $table->string('badge')->nullable();
            $table->enum('badge_color', ['red','gold','blue'])->default('red');
            $table->boolean('is_custom')->default(false);       // sur-mesure
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);     // coup de cœur (admin override)
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('orders_count')->default(0);
            $table->decimal('heart_score', 8, 2)->default(0);  // likes*2 + views*0.5
            $table->integer('stock')->nullable();               // null = illimité
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('products'); }
};
