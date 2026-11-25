<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            // image = slide classique | video = fond vidéo (l'image sert alors d'affiche/poster)
            $table->string('media_type', 10)->default('image')->after('subtitle');
            $table->string('video')->nullable()->after('media_type');
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'video']);
        });
    }
};
