<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('city')->nullable();
            $table->text('content');
            $table->tinyInteger('rating')->default(5); // 1 à 5
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('reviews'); }
};
