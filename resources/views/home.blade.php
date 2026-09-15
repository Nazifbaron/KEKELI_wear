{{-- ============================================================
     home.blade.php — Vue one-page principale KEKELI WEAR
     Toutes les sections sont des @include séparés pour
     faciliter la maintenance et les futures évolutions.
     Les données viennent du HomeController.
============================================================ --}}
@extends('layouts.app')

@section('content')

    {{-- Hero Carousel --}}
    @include('sections.hero', ['slides' => $slides])

        {{-- Avantages clés (entre hero et about) --}}
    @include('sections.advantages')


    {{-- À propos
    @include('sections.about')--}}

    {{-- Catalogue — 4 univers --}}
    @include('sections.catalogue', ['categories' => $categories])

    {{-- Coups de cœur --}}
    @include('sections.featured', ['featured' => $featured])

    {{-- Boutique — grille produits filtrables --}}
    @include('sections.shop', ['products' => $products, 'categories' => $categories])

    {{-- Morphologie + formulaire mensurations --}}
    @include('sections.morphology')

    {{-- Témoignages clients --}}
    @include('sections.reviews', ['reviews' => $reviews, 'avgRating' => $avgRating])

    {{-- Contact --}}
    @include('sections.contact')

@endsection

@push('scripts')
<script>
    {{-- Stats BD injectées au chargement, refresh AJAX toutes les 60s --}}
    window.KEKELI_STATS  = @json($stats);
    window.WA_NUMBER     = '{{ config('kekeli.whatsapp') }}';
    window.CSRF_TOKEN    = '{{ csrf_token() }}';

    document.addEventListener('DOMContentLoaded', function () {
        if (window.KEKELI_STATS) {
            var s = window.KEKELI_STATS;
            setStatEl('sb-total', s.total_products);
            setStatEl('sb-likes', s.total_likes);
            setStatEl('sb-views', s.total_views);
            setStatEl('sb-rating', s.avg_rating + ' / 5');

            {{-- Compteurs par catégorie depuis la BD --}}
            var cats = s.per_category || {};
            Object.keys(cats).forEach(function (slug) {
                var el = document.getElementById('cnt-' + slug);
                if (el) el.textContent = cats[slug];
            });
        }
    });

    function setStatEl(id, val) {
        var el = document.getElementById(id);
        if (el) el.textContent = val;
    }
</script>
@endpush
