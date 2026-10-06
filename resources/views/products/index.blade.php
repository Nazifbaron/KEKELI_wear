{{-- products/index.blade.php — Page catalogue complète avec pagination --}}
@extends('layouts.app')
@section('content')

<div class="catalogue-hero">
    <div class="container">
        <div class="sec-label">Boutique</div>
        <h1 class="catalogue-title">Toutes nos créations</h1>
        <p class="catalogue-sub">{{ $products->total() }} pièce{{ $products->total() > 1 ? 's' : '' }} disponible{{ $products->total() > 1 ? 's' : '' }}</p>
    </div>
</div>

<div class="catalogue-page">
    <div class="container">

        <div class="catalogue-filters">
            <a href="{{ route('boutique') }}" class="filter-btn {{ !$currentCat ? 'active' : '' }}">
                Tout ({{ $products->total() }})
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('boutique', ['cat' => $cat->slug]) }}"
                   class="filter-btn {{ $currentCat === $cat->slug ? 'active' : '' }}">
                    {{ $cat->icon }} {{ $cat->name }}
                    <span class="filter-count">{{ $cat->product_count }}</span>
                </a>
            @endforeach
        </div>

        @include('products.partials.promo-form')

        @if($products->isEmpty())
            <div class="catalogue-empty">
                <div style="font-size:48px;margin-bottom:16px">🧵</div>
                <p style="color:var(--soft);margin-bottom:20px">Aucun produit dans cette catégorie pour le moment.</p>
                <a href="{{ route('boutique') }}" class="btn-gold">Voir toutes les créations</a>
            </div>
        @else
            <div class="products-grid" style="grid-template-columns:repeat(4,1fr)">
                @foreach($products as $product)
                <div class="prod-card" data-category-id="{{ $product->category_id }}">
                    <div class="prod-img">
                        <a class="prod-image-link" href="{{ route('product.show', $product->slug) }}" aria-label="Découvrir {{ $product->name }}">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" loading="lazy" />
                            @else
                                <span class="prod-image-placeholder">✦</span>
                            @endif
                            <span class="prod-image-discover">Découvrir la pièce <span aria-hidden="true">↗</span></span>
                        </a>
                        @if($product->badge)
                            <div class="prod-badge">
                                <span class="badge badge-{{ $product->badge_color ?? 'red' }}">{{ $product->badge }}</span>
                            </div>
                        @endif
                        @if($product->extraImages->count() > 0)
                            <div class="prod-img-count">+{{ $product->extraImages->count() }} photo{{ $product->extraImages->count() > 1 ? 's' : '' }}</div>
                        @endif
                        <div class="prod-views">👁 {{ number_format($product->views) }}</div>
                    </div>
                    <div class="prod-info">
                        <a class="prod-info-link" href="{{ route('product.show', $product->slug) }}">
                            <div class="prod-type">{{ $product->category->name }}</div>
                            <div class="prod-title">{{ $product->name }}</div>
                            @if($product->description)
                                <div class="prod-desc-short">{{ Str::limit($product->description, 60) }}</div>
                            @endif
                            <div class="prod-footer">
                                @include('products.partials.price', ['product' => $product])
                                <span class="prod-card-arrow" aria-hidden="true">↗</span>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="catalogue-pagination">
                    {{ $products->links() }}
                </div>
            @endif
        @endif

        <div style="text-align:center;margin-top:32px">
            <a href="{{ route('home') }}#shop" style="font-size:13px;color:var(--soft);text-decoration:none">
                ← Retour à l'accueil
            </a>
        </div>
    </div>
</div>

@endsection
