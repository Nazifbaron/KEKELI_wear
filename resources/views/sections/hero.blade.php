{{-- ============================================================
     sections/hero.blade.php
     Carrousel hero — 2 sources possibles :
     1. Slides BD (configurées par l'admin → hero_slides table)
     2. Fallback 3 slides codées en dur si BD vide
     Le JS (kekeli.js) gère auto-play 5s, dots, flèches.
============================================================ --}}
<section id="hero" style="padding:0">

    {{-- ========== CAS 1 : Slides depuis la BD ========== --}}
    @if($slides->isNotEmpty())

        @foreach($slides as $index => $slide)
        <div class="slide {{ $index > 0 ? 'hidden' : '' }}" id="slide-{{ $index }}">

              {{-- Image BD si fournie, sinon image locale correspondante --}}
            <div class="slide-bg"
                  style="background-image:url('{{ $slide->image ? asset('storage/' . $slide->image) : asset('images/hero/hero-' . ($index + 1) . '.jpg') }}');
                        background-color:#2a1010">
            </div>

            {{-- Overlay coloré selon choix admin --}}
            <div class="slide-overlay" style="background:{{ $slide->overlay_css }}"></div>

            {{-- Contenu du slide --}}
            <div class="slide-content">
                <div class="slide-tag">{{ $slide->tag }}</div>
                <div class="slide-title">
                    {{-- Le titre peut avoir une partie en couleur gold --}}
                    @if($slide->title_highlight)
                        {{ $slide->title }}<br>
                        <span>{{ $slide->title_highlight }}</span>
                    @else
                        {{ $slide->title }}
                    @endif
                </div>
                @if($slide->subtitle)
                    <div class="slide-sub">{{ $slide->subtitle }}</div>
                @endif
                <div class="hero-btns">
                    @if($slide->btn_primary_label)
                        <a class="btn-gold" href="{{ $slide->btn_primary_url ?? '#catalogue' }}">
                            {{ $slide->btn_primary_label }}
                        </a>
                    @endif
                    @if($slide->btn_secondary_label)
                        <a class="btn-outline" href="{{ $slide->btn_secondary_url ?? '#' }}">
                            {{ $slide->btn_secondary_label }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

    {{-- ========== CAS 2 : Fallback si aucun slide en BD ========== --}}
    @else

        {{-- Slide 1 — Accueil général --}}
        <div class="slide" id="slide-0">
            <div class="slide-bg"
                 style="background-image:url('{{ asset('images/hero/hero-1.jpg') }}');
                        background-color:#2a1010">
            </div>
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <div class="slide-tag">Collection 2026</div>
                <div class="slide-title">
                    Une lumière pour<br>
                    <span>la mode au féminin.</span>
                </div>
                <div class="slide-sub">
                    Créations afrofusion uniques — Mode écoresponsable,
                    imaginée à Cotonou, au Bénin.
                </div>
                <div class="hero-btns">
                    <a class="btn-gold" href="#catalogue">Découvrir nos créations</a>
                    <a class="btn-outline"
                       href="https://wa.me/{{ config('kekeli.whatsapp') }}"
                       target="_blank">Commander sur WhatsApp</a>
                </div>
            </div>
        </div>

        {{-- Slide 2 — Tenues Réinventées --}}
        <div class="slide hidden" id="slide-1">
            <div class="slide-bg"
                 style="background-image:url('{{ asset('images/hero/hero-2.jpg') }}');
                        background-color:#0a1020">
            </div>
            <div class="slide-overlay"
                 style="background:linear-gradient(135deg,rgba(0,0,0,.75),rgba(24,65,131,.3) 60%,transparent)">
            </div>
            <div class="slide-content">
                <div class="slide-tag">Tenues Réinventées</div>
                <div class="slide-title">
                    L'upcycling<br>
                    <span>réinventé.</span>
                </div>
                <div class="slide-sub">
                    Pièces écoresponsables et uniques. Chaque création raconte
                    une histoire, porte un héritage, révèle votre éclat intérieur.
                </div>
                <div class="hero-btns">
                    <a class="btn-gold"
                       href="#shop"
                       onclick="filterByCategory('reinventees')">Voir la collection</a>
                </div>
            </div>
        </div>

        {{-- Slide 3 — Sur-Mesure --}}
        <div class="slide hidden" id="slide-2">
            <div class="slide-bg"
                 style="background-image:url('{{ asset('images/hero/hero-3.jpg') }}');
                        background-color:#1a0a1a">
            </div>
            <div class="slide-overlay"
                 style="background:linear-gradient(135deg,rgba(0,0,0,.75),rgba(58,38,101,.35) 60%,transparent)">
            </div>
            <div class="slide-content">
                <div class="slide-tag">Sur-Mesure Exclusif</div>
                <div class="slide-title">
                    Votre morphologie,<br>
                    <span>notre art.</span>
                </div>
                <div class="slide-sub">
                    Un vêtement créé pour vous, selon vos mesures exactes.
                    Renseignez votre profil morphologique et laissez-nous révéler votre éclat.
                </div>
                <div class="hero-btns">
                    <a class="btn-gold" href="#morphology">Découvrir mon profil</a>
                </div>
            </div>
        </div>
        

    @endif

    {{-- ========== Dots de navigation ========== --}}
    <div class="carousel-dots" id="carousel-dots">
        @php
            // Nombre de dots = nombre de slides (BD ou fallback)
            $slideCount = $slides->isNotEmpty() ? $slides->count() : 3;
        @endphp
        @for($i = 0; $i < $slideCount; $i++)
            <button class="cdot {{ $i === 0 ? 'active' : '' }}"
                    onclick="goSlide({{ $i }})"
                    aria-label="Slide {{ $i + 1 }}">
            </button>
        @endfor
    </div>

    {{-- ========== Flèches de navigation ========== --}}
    <div class="carousel-arrows">
        <button class="carrow" onclick="prevSlide()" aria-label="Slide précédent">←</button>
        <button class="carrow" onclick="nextSlide()" aria-label="Slide suivant">→</button>
    </div>

    {{-- ========== Indication scroll ========== --}}
    <div class="scroll-hint">
        <span>Découvrir</span>
        <span class="scroll-arrow">↓</span>
    </div>

</section>

{{-- Passer le nombre de slides au JS --}}
@push('scripts')
<script>
    {{-- Nombre de slides dynamique selon la source (BD ou fallback) --}}
    window.TOTAL_SLIDES = {{ $slides->isNotEmpty() ? $slides->count() : 3 }};
</script>
@endpush
