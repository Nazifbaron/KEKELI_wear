{{-- ============================================================
     sections/hero.blade.php
    Carrousel hero — les slides BD remplacent leur position;
    les positions sans slide BD utilisent les 3 slides manuelles.
     Le JS (kekeli.js) gère auto-play 5s, dots, flèches.
============================================================ --}}
<section id="hero" style="padding:0">

    @php
        // Chaque slide admin occupe sa position d'ordre; les positions vides utilisent le fallback.
        $slidesByPosition = $slides->keyBy(fn($slide) => max(0, (int) $slide->order - 1));
        $lastAdminPosition = $slidesByPosition->keys()->max() ?? -1;
        $slideCount = max(3, $lastAdminPosition + 1);
        $manualSlides = [
            [
                'tag' => 'Collection 2026',
                'title' => 'Une lumière pour',
                'highlight' => 'la mode au féminin.',
                'subtitle' => 'Créations afrofusion uniques — Mode écoresponsable, imaginée à Cotonou, au Bénin.',
                'image' => 'hero-1.jpg',
                'overlay' => '',
                'primary_label' => 'Découvrir nos créations',
                'primary_url' => '#catalogue',
                'secondary_label' => 'Commander sur WhatsApp',
                'secondary_url' => 'https://wa.me/' . config('kekeli.whatsapp'),
            ],
            [
                'tag' => 'Tenues Réinventées',
                'title' => "L'upcycling",
                'highlight' => 'réinventé.',
                'subtitle' => "Pièces écoresponsables et uniques. Chaque création raconte une histoire, porte un héritage, révèle votre éclat intérieur.",
                'image' => 'hero-2.jpg',
                'overlay' => 'linear-gradient(135deg,rgba(0,0,0,.75),rgba(24,65,131,.3) 60%,transparent)',
                'primary_label' => 'Voir la collection',
                'primary_url' => '#shop',
                'secondary_label' => null,
                'secondary_url' => null,
            ],
            [
                'tag' => 'Sur-Mesure Exclusif',
                'title' => 'Votre morphologie,',
                'highlight' => 'notre art.',
                'subtitle' => 'Un vêtement créé pour vous, selon vos mesures exactes. Renseignez votre profil morphologique et laissez-nous révéler votre éclat.',
                'image' => 'hero-3.jpg',
                'overlay' => 'linear-gradient(135deg,rgba(0,0,0,.75),rgba(58,38,101,.35) 60%,transparent)',
                'primary_label' => 'Découvrir mon profil',
                'primary_url' => '#morphology',
                'secondary_label' => null,
                'secondary_url' => null,
            ],
        ];
    @endphp

    @for($index = 0; $index < $slideCount; $index++)
        @if($slidesByPosition->has($index))
            @php $slide = $slidesByPosition->get($index); @endphp
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
        @else
            @php $manual = $manualSlides[$index] ?? $manualSlides[0]; @endphp
            <div class="slide {{ $index > 0 ? 'hidden' : '' }}" id="slide-{{ $index }}">
                <div class="slide-bg"
                     style="background-image:url('{{ asset('images/hero/' . $manual['image']) }}');background-color:#2a1010">
                </div>
                <div class="slide-overlay" @if($manual['overlay']) style="background:{{ $manual['overlay'] }}" @endif></div>
                <div class="slide-content">
                    <div class="slide-tag">{{ $manual['tag'] }}</div>
                    <div class="slide-title">
                        {{ $manual['title'] }}<br>
                        <span>{{ $manual['highlight'] }}</span>
                    </div>
                    <div class="slide-sub">{{ $manual['subtitle'] }}</div>
                    <div class="hero-btns">
                        <a class="btn-gold" href="{{ $manual['primary_url'] }}"
                           @if($index === 1) onclick="filterByCategory('reinventees')" @endif>
                            {{ $manual['primary_label'] }}
                        </a>
                        @if($manual['secondary_label'])
                            <a class="btn-outline" href="{{ $manual['secondary_url'] }}" target="_blank">
                                {{ $manual['secondary_label'] }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endfor

    {{-- ========== Dots de navigation ========== --}}
    <div class="carousel-dots" id="carousel-dots">
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
    window.TOTAL_SLIDES = {{ $slideCount }};
</script>
@endpush
