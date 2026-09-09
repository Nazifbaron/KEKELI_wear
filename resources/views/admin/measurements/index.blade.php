{{-- ============================================================
     admin/measurements/index.blade.php
     Liste des mensurations reçues avec statut et contact WA
============================================================ --}}
@extends('admin.layouts.app')
@section('title','Mensurations')

@section('content')

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>WhatsApp</th>
                <th>Morphologie</th>
                <th>Mesures clés</th>
                <th>Produit</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($measurements as $m)
            <tr>
                <td><strong>{{ $m->full_name }}</strong></td>
                <td>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $m->whatsapp) }}"
                       target="_blank"
                       style="color:var(--gold);text-decoration:none;font-size:12px">
                        📱 {{ $m->whatsapp }}
                    </a>
                </td>
                <td>
                    {{ \App\Models\Measurement::morphologyLabel($m->morphology ?? '') }}
                </td>
                <td style="font-size:11px;color:#aaa">
                    @if($m->chest) P:{{ $m->chest }} @endif
                    @if($m->waist) T:{{ $m->waist }} @endif
                    @if($m->hips) H:{{ $m->hips }} @endif
                    @if($m->height) ↕{{ $m->height }} @endif
                    cm
                </td>
                <td style="font-size:12px">
                    {{ $m->product?->name ?? '—' }}
                </td>
                <td>
                    <span class="status-badge
                        {{ match($m->status) {
                            'received'   => 'status-orange',
                            'processing' => 'status-blue',
                            'ordered'    => 'status-green',
                        } }}">
                        {{ match($m->status) {
                            'received'   => 'Reçue',
                            'processing' => 'En cours',
                            'ordered'    => 'Commandée',
                        } }}
                    </span>
                </td>
                <td style="font-size:11px;color:#888">
                    {{ $m->created_at->format('d/m/Y H:i') }}
                </td>
                <td>
                    <form method="POST"
                          action="{{ route('admin.measurements.status', $m) }}">
                        @csrf @method('PATCH')
                        <select class="form-admin-input"
                                name="status"
                                style="padding:4px 6px;font-size:11px;margin-bottom:4px"
                                onchange="this.form.submit()">
                            <option value="received"   {{ $m->status === 'received'   ? 'selected' : '' }}>Reçue</option>
                            <option value="processing" {{ $m->status === 'processing' ? 'selected' : '' }}>En cours</option>
                            <option value="ordered"    {{ $m->status === 'ordered'    ? 'selected' : '' }}>Commandée</option>
                        </select>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-msg">Aucune mensuration reçue.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px">{{ $measurements->links() }}</div>
</div>

@endsection
