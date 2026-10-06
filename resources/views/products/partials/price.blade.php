{{-- Prix de carte partagé : une promotion produit est distincte d'un code promo. --}}
<div class="prod-price-wrap">
    @if($product->is_currently_on_sale)
        <div class="price-row">
            <span class="prod-price-original">{{ $product->formatted_original_price }}</span>
            <span class="prod-price-sale">{{ $product->formatted_sale_price }}</span>
        </div>
        <span class="prod-promo-badge" aria-label="Promotion, réduction {{ $product->discount_percent }}">
            <span>Promotion</span>
            <span aria-hidden="true">·</span>
            <span>{{ $product->discount_percent }}</span>
        </span>
    @else
        <span class="{{ $priceClass ?? 'prod-price' }}"
              data-base="{{ $product->price ?? 0 }}"
              data-on-sale="0">{{ $product->formatted_price }}</span>
        <span class="prod-price-promo" style="display:none"></span>
    @endif
</div>
