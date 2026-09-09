{{-- ============================================================
     sections/reviews.blade.php
     Avis clients validés (is_approved = true)
     + note moyenne calculée depuis la BD
     Les avis non validés sont gérés depuis l'admin
============================================================ --}}
<section id="reviews">
    <div class="container">

        <div class="sec-label">Avis clients</div>
        <div class="sec-title" style="font-size:28px;margin-bottom:16px">
            Ce qu'elles en disent
        </div>

        {{-- Score moyen global --}}
        <div class="avg-score">
            <div class="avg-num">{{ number_format($avgRating, 1, ',', '') }}</div>
            <div class="avg-detail">
                <div style="color:var(--kgold);font-size:16px;margin-bottom:2px">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= round($avgRating) ? '★' : '☆' }}
                    @endfor
                </div>
                <div>Note moyenne · {{ $reviews->count() }} avis vérifiés</div>
            </div>
        </div>

        {{-- Grille des avis --}}
        <div class="temo-grid">

            @forelse($reviews as $review)
            <div class="temo-card">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $review->rating ? '★' : '☆' }}
                    @endfor
                </div>
                <div class="temo-text">"{{ $review->content }}"</div>
                <div class="temo-author">{{ $review->first_name }}</div>
                @if($review->city)
                    <div class="temo-city">{{ $review->city }}</div>
                @endif
            </div>
            @empty
            <p style="color:var(--kgray);grid-column:1/-1;text-align:center;padding:20px">
                Aucun avis pour le moment. Soyez la première à partager votre expérience !
            </p>
            @endforelse

        </div>

        {{-- ===== FORMULAIRE SOUMISSION AVIS ===== --}}
        <div class="review-form-wrap">
            <div class="sec-label" style="margin-top:48px">Partagez votre expérience</div>
            <div style="font-size:14px;color:var(--kgray);margin-bottom:24px">
                Votre avis sera publié après validation. Merci de votre confiance ✦
            </div>

            <form id="review-form" style="max-width:560px">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input class="form-input" id="rv-name"
                               type="text" placeholder="Votre prénom" required />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ville</label>
                        <input class="form-input" id="rv-city"
                               type="text" placeholder="Ex: Cotonou, Bénin" />
                    </div>
                </div>

                {{-- Sélecteur d'étoiles --}}
                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label">Note *</label>
                    <div class="star-picker" id="star-picker">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star-pick {{ $i <= 5 ? 'active' : '' }}"
                                  data-val="{{ $i }}"
                                  onclick="pickStar({{ $i }})">★</span>
                        @endfor
                    </div>
                    <input type="hidden" id="rv-rating" value="5" />
                </div>

                <div class="form-group" style="margin-bottom:20px">
                    <label class="form-label">Votre avis *</label>
                    <textarea class="form-input" id="rv-content"
                              rows="3"
                              placeholder="Partagez votre expérience avec KEKELI Wear..."
                              required></textarea>
                </div>

                <button type="button" class="btn-gold" onclick="submitReview()">
                    ✦ Soumettre mon avis
                </button>

                <div id="review-feedback" style="display:none;margin-top:14px;font-size:13px"></div>
            </form>
        </div>

    </div>
</section>
