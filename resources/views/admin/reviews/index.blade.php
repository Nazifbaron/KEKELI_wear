{{-- ============================================================
     admin/reviews/index.blade.php
     Modération des avis clients — approuver ou masquer
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Avis Clients')

@section('content')

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Ville</th>
                <th>Note</th>
                <th>Avis</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td><strong>{{ $review->first_name }}</strong></td>
                <td style="color:#888;font-size:12px">{{ $review->city ?? '—' }}</td>
                <td>
                    <span style="color:var(--gold)">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $review->rating ? '★' : '☆' }}
                        @endfor
                    </span>
                    <span style="font-size:11px;color:#888">({{ $review->rating }}/5)</span>
                </td>
                <td style="max-width:320px;font-size:12px;color:#ccc;font-style:italic">
                    "{{ Str::limit($review->content, 100) }}"
                </td>
                <td>
                    @if($review->is_approved)
                        <span class="status-badge status-green">✓ Publié</span>
                    @else
                        <span class="status-badge status-orange">⏳ En attente</span>
                    @endif
                </td>
                <td style="font-size:11px;color:#888">
                    {{ $review->created_at->format('d/m/Y') }}
                </td>
                <td>
                    <form method="POST"
                          action="{{ route('admin.reviews.approve', $review) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="btn-sm {{ $review->is_approved ? 'btn-sm-red' : 'btn-sm-green' }}">
                            {{ $review->is_approved ? 'Masquer' : 'Approuver' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty-msg">Aucun avis pour le moment.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px">{{ $reviews->links() }}</div>
</div>

@endsection
