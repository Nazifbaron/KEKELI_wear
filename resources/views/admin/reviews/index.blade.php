{{-- ============================================================
     admin/reviews/index.blade.php
     Modération des avis : approuver, masquer, supprimer
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Avis Clients')

@section('content')

{{-- Compteurs rapides --}}
<div style="display:flex;gap:16px;margin-bottom:24px;flex-wrap:wrap">
    <div class="admin-card" style="padding:14px 20px;display:flex;align-items:center;gap:12px;flex:1;min-width:140px">
        <span style="font-size:24px">⭐</span>
        <div>
            <div style="font-size:20px;font-weight:700;color:var(--a-black)">{{ $reviews->total() }}</div>
            <div style="font-size:11px;color:var(--a-soft)">Avis reçus</div>
        </div>
    </div>
    <div class="admin-card" style="padding:14px 20px;display:flex;align-items:center;gap:12px;flex:1;min-width:140px">
        <span style="font-size:24px">✅</span>
        <div>
            <div style="font-size:20px;font-weight:700;color:#16a34a">{{ $approvedCount }}</div>
            <div style="font-size:11px;color:var(--a-soft)">Publiés</div>
        </div>
    </div>
    <div class="admin-card" style="padding:14px 20px;display:flex;align-items:center;gap:12px;flex:1;min-width:140px">
        <span style="font-size:24px">⏳</span>
        <div>
            <div style="font-size:20px;font-weight:700;color:#c2410c">{{ $pendingCount }}</div>
            <div style="font-size:11px;color:var(--a-soft)">En attente</div>
        </div>
    </div>
    <div class="admin-card" style="padding:14px 20px;display:flex;align-items:center;gap:12px;flex:1;min-width:140px">
        <span style="font-size:24px">📊</span>
        <div>
            <div style="font-size:20px;font-weight:700;color:var(--a-black)">{{ $avgRating }}</div>
            <div style="font-size:11px;color:var(--a-soft)">Note moyenne / 5</div>
        </div>
    </div>
</div>

{{-- Filtres --}}
<div class="filter-tabs" style="margin-bottom:20px">
    <a href="{{ route('admin.reviews') }}"
       class="filter-tab {{ !request('filter') ? 'active' : '' }}">
        Tous ({{ $reviews->total() }})
    </a>
    <a href="{{ route('admin.reviews', ['filter' => 'pending']) }}"
       class="filter-tab {{ request('filter') === 'pending' ? 'active' : '' }}">
        ⏳ En attente ({{ $pendingCount }})
    </a>
    <a href="{{ route('admin.reviews', ['filter' => 'approved']) }}"
       class="filter-tab {{ request('filter') === 'approved' ? 'active' : '' }}">
        ✅ Publiés ({{ $approvedCount }})
    </a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Ville</th>
                <th>Note</th>
                <th style="min-width:260px">Avis</th>
                <th>Statut</th>
                <th>Date</th>
                <th style="min-width:180px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr id="review-row-{{ $review->id }}">
                <td><strong>{{ $review->first_name }}</strong></td>
                <td style="color:var(--a-soft);font-size:12px">{{ $review->city ?? '—' }}</td>
                <td>
                    <span style="color:#edc530;font-size:13px;letter-spacing:1px">
                        @for($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                    </span>
                    <span style="font-size:11px;color:var(--a-soft)">({{ $review->rating }}/5)</span>
                </td>
                <td style="font-size:12px;color:var(--a-mid);font-style:italic;max-width:300px">
                    "{{ Str::limit($review->content, 110) }}"
                </td>
                <td>
                    @if($review->is_approved)
                        <span class="status-badge status-green">✓ Publié</span>
                    @else
                        <span class="status-badge status-orange">⏳ En attente</span>
                    @endif
                </td>
                <td style="font-size:11px;color:var(--a-soft);white-space:nowrap">
                    {{ $review->created_at->format('d/m/Y') }}<br>
                    <span style="font-size:10px">{{ $review->created_at->format('H:i') }}</span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">

                        {{-- Approuver / Masquer --}}
                        <form method="POST"
                              action="{{ route('admin.reviews.approve', $review) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="btn-sm {{ $review->is_approved ? '' : 'btn-sm-green' }}">
                                {{ $review->is_approved ? '👁 Masquer' : '✓ Approuver' }}
                            </button>
                        </form>

                        {{-- Supprimer --}}
                        <form method="POST"
                              action="{{ route('admin.reviews.destroy', $review) }}"
                              onsubmit="return confirm('Supprimer définitivement cet avis de {{ addslashes($review->first_name) }} ? Cette action est irréversible.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm btn-sm-red">
                                🗑 Supprimer
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty-msg">
                    @if(request('filter') === 'pending')
                        ✓ Aucun avis en attente de validation.
                    @elseif(request('filter') === 'approved')
                        Aucun avis publié pour le moment.
                    @else
                        Aucun avis reçu pour le moment.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="padding:16px 20px">
        {{ $reviews->links() }}
    </div>
</div>

@endsection
