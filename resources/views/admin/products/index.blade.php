{{-- ============================================================
     admin/products/index.blade.php
     Liste paginée de tous les produits avec actions CRUD
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Produits')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
    <p style="color:#888;font-size:13px">{{ $products->total() }} produits au total</p>
    <a href="{{ route('admin.products.create') }}" class="btn-admin-primary">
        + Nouveau produit
    </a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Likes</th>
                <th>Vues</th>
                <th>Score</th>
                <th>Cœur</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                {{-- Miniature --}}
                <td>
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                             alt="{{ $product->name }}"
                             style="width:48px;height:48px;object-fit:cover;border-radius:4px" />
                    @else
                        <div style="width:48px;height:48px;background:#1a1a1a;border-radius:4px;
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:18px">👗</div>
                    @endif
                </td>

                <td>
                    <strong>{{ $product->name }}</strong>
                    @if($product->badge)
                        <br>
                        <span style="font-size:10px;color:#888">{{ $product->badge }}</span>
                    @endif
                    @if($product->is_custom)
                        <span class="status-badge status-blue" style="font-size:9px">Sur-mesure</span>
                    @endif
                </td>

                <td>{{ $product->category->name }}</td>

                <td>
                    @if($product->price)
                        {{ number_format($product->price, 0, ',', ' ') }} XOF
                    @else
                        <span style="color:#888">Sur devis</span>
                    @endif
                </td>

                <td style="text-align:center">{{ $product->likes }}</td>
                <td style="text-align:center">{{ number_format($product->views) }}</td>

                <td style="text-align:center">
                    <span class="badge-score">{{ number_format($product->heart_score, 0) }}</span>
                </td>

                {{-- Coup de cœur — toggle depuis admin --}}
                <td style="text-align:center">
                    @if($product->is_featured)
                        <span style="font-size:18px" title="Coup de cœur actif">🔥</span>
                    @else
                        <span style="font-size:18px;opacity:.3">🤍</span>
                    @endif
                </td>

                {{-- Actif --}}
                <td style="text-align:center">
                    @if($product->is_active)
                        <span class="status-badge status-green">Actif</span>
                    @else
                        <span class="status-badge status-gray">Inactif</span>
                    @endif
                </td>

                {{-- Actions --}}
                <td>
                    <div style="display:flex;gap:6px">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="btn-sm btn-sm-blue">Éditer</a>

                        <form method="POST"
                              action="{{ route('admin.products.destroy', $product) }}"
                              onsubmit="return confirm('Supprimer ce produit ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm btn-sm-red">Suppr.</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div style="margin-top:20px">
        {{ $products->links() }}
    </div>
</div>

@endsection
