<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte administrateur en premier
        $this->call(AdminUserSeeder::class);
        /* ---- Categories ---- */
        $categories = [
            ['name'=>'Tenues Réinventées','slug'=>'reinventees','icon'=>'♻', 'color'=>'#e42829','order'=>1,
             'description'=>'Pièces écoresponsables et uniques, upcycling de tissus africains précieux.'],
            ['name'=>'Confections Maison','slug'=>'maison',     'icon'=>'✂', 'color'=>'#edc530','order'=>2,
             'description'=>'Capsules limitées à 10 pièces maximum. Exclusivité garantie.'],
            ['name'=>'Sur-Mesure',        'slug'=>'surmesure',  'icon'=>'📐','color'=>'#184183','order'=>3,
             'description'=>'Réalisation selon le style choisi par la cliente.'],
            ['name'=>'Accessoires',       'slug'=>'accessoires','icon'=>'💎','color'=>'#3a2665','order'=>4,
             'description'=>'Ceintures signature, turbans, bijoux de perles et cauris.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], array_merge($cat, ['is_active' => true]));
        }

        $catIds = Category::pluck('id', 'slug');

        /* ---- Products ---- */
        $products = [
            // Tenues Réinventées (8)
            ['name'=>'Robe Soleil de Minuit',    'slug'=>'robe-soleil-minuit',   'category_id'=>$catIds['reinventees'],'price'=>45000,'badge'=>'Pièce unique',  'views'=>142,'likes'=>24,'orders_count'=>8],
            ['name'=>'Jupe Upcyclée Sahel',      'slug'=>'jupe-upcyclee-sahel',  'category_id'=>$catIds['reinventees'],'price'=>28000,'badge'=>'Éco-responsable','views'=>88, 'likes'=>12,'orders_count'=>4],
            ['name'=>'Ensemble Wax Lumière',     'slug'=>'ensemble-wax-lumiere', 'category_id'=>$catIds['reinventees'],'price'=>52000,'badge'=>'Best-seller',   'views'=>210,'likes'=>31,'orders_count'=>14],
            ['name'=>'Robe Afro-Fusion Éclat',  'slug'=>'robe-afro-fusion',     'category_id'=>$catIds['reinventees'],'price'=>38000,'badge'=>'Pièce unique',  'views'=>65, 'likes'=>9, 'orders_count'=>3],
            ['name'=>'Top Kente Revisité',       'slug'=>'top-kente-revisite',   'category_id'=>$catIds['reinventees'],'price'=>22000,'badge'=>null,            'views'=>44, 'likes'=>6, 'orders_count'=>2],
            ['name'=>'Robe Batik Cotonou',       'slug'=>'robe-batik-cotonou',   'category_id'=>$catIds['reinventees'],'price'=>35000,'badge'=>'Éco-responsable','views'=>73, 'likes'=>11,'orders_count'=>5],
            ['name'=>'Combinaison Wax Moderne',  'slug'=>'combinaison-wax',      'category_id'=>$catIds['reinventees'],'price'=>48000,'badge'=>'Pièce unique',  'views'=>91, 'likes'=>16,'orders_count'=>6],
            ['name'=>'Robe Bogolan Épurée',      'slug'=>'robe-bogolan',         'category_id'=>$catIds['reinventees'],'price'=>41000,'badge'=>null,            'views'=>55, 'likes'=>8, 'orders_count'=>3],
            // Confections Maison (3)
            ['name'=>'Veste Héritage Kaba',      'slug'=>'veste-heritage-kaba',  'category_id'=>$catIds['maison'],    'price'=>38000,'badge'=>'Capsule limitée','views'=>98, 'likes'=>18,'orders_count'=>7],
            ['name'=>'Tailleur Afro-Chic',       'slug'=>'tailleur-afro-chic',   'category_id'=>$catIds['maison'],    'price'=>62000,'badge'=>'Capsule N°2/10','views'=>67, 'likes'=>13,'orders_count'=>5],
            ['name'=>'Ensemble Festif Lumière',  'slug'=>'ensemble-festif',      'category_id'=>$catIds['maison'],    'price'=>55000,'badge'=>'Capsule limitée','views'=>48, 'likes'=>10,'orders_count'=>4],
            // Sur-Mesure (2)
            ['name'=>'Robe Éclat Personnalisée', 'slug'=>'robe-eclat-sur-mesure','category_id'=>$catIds['surmesure'],'price'=>null, 'badge'=>'Sur-Mesure',    'views'=>195,'likes'=>9, 'orders_count'=>22,'is_custom'=>true],
            ['name'=>'Tenue Cérémonie Sur-Mesure','slug'=>'ceremonie-sur-mesure','category_id'=>$catIds['surmesure'],'price'=>null, 'badge'=>'Sur-Mesure',    'views'=>88, 'likes'=>7, 'orders_count'=>12,'is_custom'=>true],
            // Accessoires (4)
            ['name'=>'Ceinture Signature Cauris','slug'=>'ceinture-cauris',      'category_id'=>$catIds['accessoires'],'price'=>12000,'badge'=>'Fait main',   'views'=>76, 'likes'=>15,'orders_count'=>9],
            ['name'=>'Turban Kekeli Premium',    'slug'=>'turban-kekeli',        'category_id'=>$catIds['accessoires'],'price'=>8500, 'badge'=>'Exclusif',    'views'=>43, 'likes'=>8, 'orders_count'=>6],
            ['name'=>'Bijou Perles Afro-Chic',   'slug'=>'bijou-perles',         'category_id'=>$catIds['accessoires'],'price'=>15000,'badge'=>'Fait main',   'views'=>38, 'likes'=>6, 'orders_count'=>4],
            ['name'=>"Ceinture Pichi'Pichi",     'slug'=>'ceinture-pichi-pichi', 'category_id'=>$catIds['accessoires'],'price'=>9500, 'badge'=>null,          'views'=>29, 'likes'=>4, 'orders_count'=>3],
        ];

        foreach ($products as $p) {
            $data = array_merge(['is_active'=>true,'is_custom'=>false,'badge_color'=>'red'], $p);
            $data['heart_score'] = ($data['likes'] * 2) + ($data['views'] * 0.5);
            $data['is_featured'] = $data['heart_score'] >= config('kekeli.featured_threshold', 50);
            Product::firstOrCreate(['slug' => $data['slug']], $data);
        }

        /* ---- Promo codes ---- */
        $promos = [
            ['code'=>'KEKELI10','discount'=>10,'type'=>'percentage','is_active'=>true],
            ['code'=>'LUMIERE', 'discount'=>10,'type'=>'percentage','is_active'=>true],
            ['code'=>'KEKELI15','discount'=>15,'type'=>'percentage','max_uses'=>20,'is_active'=>true],
            ['code'=>'FETE2026','discount'=>20,'type'=>'percentage','max_uses'=>50,
             'starts_at'=>'2026-12-01','expires_at'=>'2027-01-05','is_active'=>true],
        ];

        foreach ($promos as $p) {
            PromoCode::firstOrCreate(['code' => $p['code']], $p);
        }

        /* ---- Reviews ---- */
        $reviews = [
            ['first_name'=>'Aminata K.', 'city'=>'Cotonou, Bénin',         'rating'=>5,'is_approved'=>true,
             'content'=>"Ma robe sur-mesure est parfaite. L'équipe a parfaitement compris ma morphologie et le résultat est extraordinaire. Je me sens lumineuse !"],
            ['first_name'=>'Fatoumata D.','city'=>"Abidjan, Côte d'Ivoire",'rating'=>5,'is_approved'=>true,
             'content'=>"Des créations uniques qui valorisent vraiment la femme africaine. J'ai porté ma pièce Kekeli à un mariage et tout le monde me l'a demandée !"],
            ['first_name'=>'Blessing A.','city'=>'Porto-Novo, Bénin',      'rating'=>5,'is_approved'=>true,
             'content'=>"Le guide morphologique m'a aidée à choisir la coupe parfaite. Service client au top. Je recommande les yeux fermés."],
            ['first_name'=>'Grace M.',   'city'=>'Sèmé-Kpodji, Bénin',    'rating'=>5,'is_approved'=>true,
             'content'=>"La ceinture cauris est magnifique. La qualité est au rendez-vous et le service est excellent."],
            ['first_name'=>'Clarisse N.','city'=>'Lagos, Nigeria',          'rating'=>4,'is_approved'=>true,
             'content'=>"Très belle collection afrofusion. Livraison internationale sans souci."],
        ];

        foreach ($reviews as $r) {
            Review::firstOrCreate(['first_name'=>$r['first_name'],'city'=>$r['city']], $r);
        }

        // Hero slides
        $this->call(HeroSlideSeeder::class);

        $this->command->info('✦ KEKELI WEAR — Base de données prête !');
    }
}
