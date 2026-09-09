<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |----------------------------------------------------------
        | 3 slides de démo — l'admin peut les modifier ou en ajouter
        | depuis /admin/hero-slides
        | Les images sont à placer dans storage/app/public/hero/
        |----------------------------------------------------------
        */
        $slides = [
            [
                'tag'                 => 'Collection 2026',
                'title'               => 'Une lumière pour',
                'title_highlight'     => 'la mode au féminin.',
                'subtitle'            => 'Créations afrofusion uniques qui associent l\'élégance contemporaine à la richesse de notre héritage culturel. Imaginées à Cotonou, au Bénin.',
                'btn_primary_label'   => 'Découvrir nos créations',
                'btn_primary_url'     => '#catalogue',
                'btn_secondary_label' => 'Commander sur WhatsApp',
                'btn_secondary_url'   => 'https://wa.me/2290140134949',
                'overlay_color'       => 'red',
                'order'               => 1,
                'is_active'           => true,
            ],
            [
                'tag'                 => 'Tenues Réinventées',
                'title'               => 'L\'upcycling',
                'title_highlight'     => 'réinventé.',
                'subtitle'            => 'Pièces écoresponsables et uniques. Chaque création raconte une histoire, porte un héritage, révèle votre éclat intérieur.',
                'btn_primary_label'   => 'Voir la collection',
                'btn_primary_url'     => '#shop',
                'btn_secondary_label' => null,
                'btn_secondary_url'   => null,
                'overlay_color'       => 'blue',
                'order'               => 2,
                'is_active'           => true,
            ],
            [
                'tag'                 => 'Sur-Mesure Exclusif',
                'title'               => 'Votre morphologie,',
                'title_highlight'     => 'notre art.',
                'subtitle'            => 'Un vêtement créé pour vous, selon vos mesures exactes. Renseignez votre profil morphologique et laissez-nous révéler votre éclat.',
                'btn_primary_label'   => 'Découvrir mon profil',
                'btn_primary_url'     => '#morphology',
                'btn_secondary_label' => null,
                'btn_secondary_url'   => null,
                'overlay_color'       => 'purple',
                'order'               => 3,
                'is_active'           => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::firstOrCreate(['order' => $slide['order']], $slide);
        }

        $this->command->info('✦ Hero slides créées.');
    }
}
