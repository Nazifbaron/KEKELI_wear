{{-- ============================================================
     sections/reviews.blade.php
     Avis clients validés (is_approved = true)
     + note moyenne calculée depuis la BD
     Les avis non validés sont gérés depuis l'admin
============================================================ --}}
<section id="reviews">
    <div class="container">

        <div class="sec-label">Avis clients</div>
        <div class="sec-title" style="font-size:clamp(32px,4.5vw,52px);margin-bottom:16px; font-family: 'Fraunces', serif">
            Elles ont choisi KEKELI <br> Wear, elles en parlent
        </div>

        {{-- Carrousel des avis --}}
        @if($reviews->isNotEmpty())
            <div class="temo-carousel" data-review-count="{{ $reviews->count() }}">
                <div class="temo-viewport">
                    <div class="temo-track">
                        @foreach($reviews as $review)
                            <article class="temo-card">
                                <div class="stars" aria-label="Note : {{ $review->rating }} sur 5">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                                <div class="temo-text">"{{ $review->content }}"</div>
                                <div class="temo-author">{{ $review->first_name }}</div>
                                @if($review->city)
                                    <div class="temo-city">{{ $review->city }}</div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>

                @if($reviews->count() > 1)
                    <div class="temo-controls">
                        <button class="temo-arrow" type="button" data-review-prev aria-label="Avis précédent">←</button>
                        <div class="temo-dots" aria-label="Choisir un avis"></div>
                        <button class="temo-arrow" type="button" data-review-next aria-label="Avis suivant">→</button>
                    </div>
                @endif
            </div>
        @else
            <p class="temo-empty">
                Aucun avis pour le moment. Soyez la première à partager votre expérience !
            </p>
        @endif

        {{-- Score moyen global --}}
        <div class="avg-score" style="margin-top:22px;">
            <div class="avg-num" style="color: red;">{{ number_format($avgRating, 1, ',', '') }}</div>
            <div class="avg-detail">
                <div style="color:var(--red);font-size:16px;margin-bottom:2px">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= round($avgRating) ? '★' : '☆' }}
                    @endfor
                </div>
                <div>Note moyenne · {{ $reviews->count() }} avis vérifiés</div>
            </div>
        </div>

        {{-- Bouton pour ouvrir le formulaire d'avis --}}
        <div class="review-toggle-btn" style="margin-top:12px;">
            <button class="btn-gold" onclick="toggleReviewForm()" id="btn-review-toggle">
                ✦ Laisser mon avis
            </button>
            <span style="font-size:13px;color:var(--soft)">
                Votre avis sera publié après validation par notre équipe.
            </span>
        </div>

        {{-- Formulaire — masqué par défaut, s'ouvre au clic --}}
        <div class="review-form-wrap" id="review-form-wrap">

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px">
                <div>
                    <div style="font-size:16px;font-weight:700;color:var(--white)">
                        Partagez votre expérience ✦
                    </div>
                    <div style="font-size:12px;color:var(--soft);margin-top:4px">
                        KEKELI WEAR — Mode afrofusion, Cotonou Bénin
                    </div>
                </div>
                <button onclick="toggleReviewForm()"
                        style="background:none;border:none;font-size:22px;color:var(--soft);cursor:pointer;line-height:1"
                        aria-label="Fermer">✕</button>
            </div>

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

            {{-- Sélecteur étoiles --}}
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label">Votre note *</label>
                <div class="star-picker" id="star-picker">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star-pick {{ $i <= 5 ? 'active' : '' }}"
                              data-val="{{ $i }}"
                              onclick="pickStar({{ $i }})">★</span>
                    @endfor
                </div>
                <input type="hidden" id="rv-rating" value="5" />
            </div>

            <div class="form-group" style="margin-bottom:22px">
                <label class="form-label">Votre avis *</label>
                <textarea class="form-input" id="rv-content"
                          rows="4"
                          placeholder="Partagez votre expérience avec KEKELI Wear — la qualité des créations, le service, la livraison..."
                          required></textarea>
            </div>

            <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                <button type="button" class="btn-gold" onclick="submitReview()">
                    Soumettre mon avis
                </button>
                <button type="button"
                        onclick="toggleReviewForm()"
                        class="btn-outline-dark">
                    Annuler
                </button>
            </div>

            <div id="review-feedback"
                 style="display:none;margin-top:16px;font-size:13px;
                        padding:12px 16px;border-radius:4px">
            </div>

        </div>{{-- /review-form-wrap --}}

    </div>
</section>
