{{-- ============================================================
     components/stats-bar.blade.php
     Barre sous la nav — alimentée par les stats BD au chargement
     puis rafraîchie toutes les 60s via /api/stats (kekeli.js)
============================================================ --}}
<div id="stats-bar">
    <span>
        🛍 <strong id="sb-total">{{ $stats['total_products'] ?? 0 }}</strong>
        créations disponibles
    </span>
    <span class="stats-sep">|</span>
    <span>
        ❤️ <strong id="sb-likes">{{ $stats['total_likes'] ?? 0 }}</strong>
        likes ce mois
    </span>
    <span class="stats-sep">|</span>
    <span>
        👁 <strong id="sb-views">{{ $stats['total_views'] ?? 0 }}</strong>
        vues aujourd'hui
    </span>
    <span class="stats-sep">|</span>
    <span>
        ⭐ Note moyenne :
        <strong id="sb-rating">{{ number_format($stats['avg_rating'] ?? 4.9, 1, ',', '') }} / 5</strong>
    </span>
    <span class="stats-sep">|</span>
    <span class="stats-open">🟢 Commandes WhatsApp ouvertes</span>
</div>
