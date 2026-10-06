<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('gift_voucher_id')
                  ->nullable()
                  ->after('promo_code_id')
                  ->constrained('gift_vouchers')
                  ->nullOnDelete();
            $table->decimal('voucher_deduction', 10, 0)
                  ->default(0)
                  ->after('gift_voucher_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['gift_voucher_id']);
            $table->dropColumn(['gift_voucher_id', 'voucher_deduction']);
        });
    }
};
