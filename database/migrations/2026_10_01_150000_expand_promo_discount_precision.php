<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            // Les remises fixes sont en XOF et peuvent dépasser 999,99.
            $table->decimal('discount', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->decimal('discount', 5, 2)->change();
        });
    }
};
