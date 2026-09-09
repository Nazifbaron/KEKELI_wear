<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /*
        |----------------------------------------------------------
        | Ajoute une image de fond à chaque catégorie.
        | Affichée sur la carte univers dans la section #catalogue
        | et comme bannière en haut de la section filtrée boutique.
        |----------------------------------------------------------
        */
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};