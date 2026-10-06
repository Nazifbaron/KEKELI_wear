<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            /*
            | Conseil morphologie affiché sur la page détail produit
            | Ex: "Recommandé pour morphologies Sablier et Poire"
            */
            $table->text('morphology_notes')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('morphology_notes');
        });
    }
};
