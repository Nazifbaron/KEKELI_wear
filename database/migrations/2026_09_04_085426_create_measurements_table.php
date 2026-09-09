<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('whatsapp', 20);
            $table->string('morphology')->nullable();       // sablier, poire, etc.
            $table->decimal('back_size', 5, 1)->nullable();
            $table->decimal('chest', 5, 1)->nullable();
            $table->decimal('waist', 5, 1)->nullable();
            $table->decimal('hips', 5, 1)->nullable();
            $table->decimal('height', 5, 1)->nullable();
            $table->decimal('dress_length', 5, 1)->nullable();
            $table->decimal('top_length', 5, 1)->nullable();
            $table->decimal('skirt_length', 5, 1)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->enum('status', ['received','processing','ordered'])->default('received');
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('measurements'); }
};
