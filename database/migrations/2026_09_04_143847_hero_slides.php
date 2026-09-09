<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /*
        |----------------------------------------------------------
        | HERO_SLIDES
        | Chaque slide du carrousel est gérable depuis l'admin.
        | L'admin peut ajouter autant de slides qu'il veut,
        | les activer/désactiver, les ordonner par drag & drop.
        | Si aucun slide actif → les 3 slides par défaut s'affichent.
        |----------------------------------------------------------
        */
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('tag');                          // ex: "Collection 2026"
            $table->string('title');                        // titre principal
            $table->string('title_highlight')->nullable();  // mot(s) mis en couleur gold
            $table->text('subtitle')->nullable();           // sous-titre descriptif
            $table->string('btn_primary_label')->nullable();   // ex: "Découvrir"
            $table->string('btn_primary_url')->nullable();     // ancre ou lien
            $table->string('btn_secondary_label')->nullable(); // ex: "Commander"
            $table->string('btn_secondary_url')->nullable();
            $table->string('image')->nullable();            // chemin storage
            $table->string('overlay_color')->default('red'); // red | blue | purple
            $table->integer('order')->default(0);           // ordre d'affichage
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('hero_slides'); }
};
