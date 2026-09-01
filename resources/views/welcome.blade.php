<!DOCTYPE html>
<html class="light" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>KEKELI Wear - L'Éclat de la Mode Afrofusion</title>
    <!-- Tailwind & Plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Configuration (Radiant Heritage / Kekeli) -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface": "#fcf9f3",
                        "background": "#fcf9f3",
                        "inverse-surface": "#31312d",
                        "on-tertiary-fixed-variant": "#2c4c4c",
                        "outline": "#877369",
                        "surface-dim": "#dcdad4",
                        "on-tertiary": "#ffffff",
                        "primary-container": "#8b4513",
                        "forest-deep": "#1B3022",
                        "on-primary": "#ffffff",
                        "surface-bright": "#fcf9f3",
                        "on-primary-fixed": "#321200",
                        "surface-tint": "#934b19",
                        "tertiary-container": "#3e5e5e",
                        "outline-variant": "#dac2b6",
                        "secondary-fixed-dim": "#e9c349",
                        "on-tertiary-container": "#b3d6d5",
                        "primary-fixed-dim": "#ffb68c",
                        "on-surface-variant": "#54433a",
                        "secondary-fixed": "#ffe088",
                        "on-error-container": "#93000a",
                        "on-background": "#1c1c18",
                        "on-error": "#ffffff",
                        "primary": "#6c2f00",
                        "error-container": "#ffdad6",
                        "primary-fixed": "#ffdbc9",
                        "on-primary-fixed-variant": "#753401",
                        "tertiary-fixed": "#c6e9e9",
                        "on-secondary-fixed-variant": "#574500",
                        "ivory-light": "#FDFAF5",
                        "on-secondary-container": "#745c00",
                        "charcoal-luxe": "#1A1A1A",
                        "inverse-on-surface": "#f3f0ea",
                        "tertiary-fixed-dim": "#abcdcd",
                        "surface-container-low": "#f6f3ed",
                        "on-primary-container": "#ffc29f",
                        "on-secondary-fixed": "#241a00",
                        "terracotta-earth": "#A0522D",
                        "on-secondary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-highest": "#e5e2dc",
                        "on-tertiary-fixed": "#002020",
                        "on-surface": "#1c1c18",
                        "secondary-container": "#fed65b",
                        "secondary": "#735c00",
                        "clay-muted": "#D2B48C",
                        "inverse-primary": "#ffb68c",
                        "mustard-gold": "#E3A018",
                        "surface-container": "#f0eee8",
                        "surface-variant": "#e5e2dc",
                        "surface-container-high": "#ebe8e2",
                        "tertiary": "#264646",
                        "error": "#ba1a1a"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "section-gap": "120px",
                        "gutter": "24px",
                        "container-max": "1280px",
                        "margin-desktop": "64px",
                        "unit": "8px",
                        "margin-mobile": "16px"
                    },
                    "fontFamily": {
                        "display-lg-mobile": [
                            "Libre Caslon Text"
                        ],
                        "display-lg": [
                            "Libre Caslon Text"
                        ],
                        "headline-md": [
                            "Libre Caslon Text"
                        ],
                        "quote": [
                            "Libre Caslon Text"
                        ],
                        "headline-lg": [
                            "Libre Caslon Text"
                        ],
                        "label-caps": [
                            "Hanken Grotesk"
                        ],
                        "body-lg": [
                            "Hanken Grotesk"
                        ],
                        "body-md": [
                            "Hanken Grotesk"
                        ]
                    },
                    "fontSize": {
                        "display-lg-mobile": [
                            "40px",
                            {
                                "lineHeight": "48px",
                                "letterSpacing": "-0.01em",
                                "fontWeight": "700"
                            }
                        ],
                        "display-lg": [
                            "64px",
                            {
                                "lineHeight": "72px",
                                "letterSpacing": "-0.02em",
                                "fontWeight": "700"
                            }
                        ],
                        "headline-md": [
                            "24px",
                            {
                                "lineHeight": "32px",
                                "fontWeight": "600"
                            }
                        ],
                        "quote": [
                            "20px",
                            {
                                "lineHeight": "30px",
                                "fontWeight": "400"
                            }
                        ],
                        "headline-lg": [
                            "32px",
                            {
                                "lineHeight": "40px",
                                "fontWeight": "600"
                            }
                        ],
                        "label-caps": [
                            "12px",
                            {
                                "lineHeight": "16px",
                                "letterSpacing": "0.1em",
                                "fontWeight": "700"
                            }
                        ],
                        "body-lg": [
                            "18px",
                            {
                                "lineHeight": "28px",
                                "fontWeight": "400"
                            }
                        ],
                        "body-md": [
                            "16px",
                            {
                                "lineHeight": "24px",
                                "fontWeight": "400"
                            }
                        ]
                    }
                },
            },
        }
    </script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;700&amp;family=Libre+Caslon+Text:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <style>
        html,
        body {
            background-color: #1B3022;
            color: theme('colors.on-background');
        }

        main {
            background-color: #fcf9f3;
        }

        .glass-panel {
            background: rgba(253, 250, 245, 0.7);
            /* Ivory-Light with opacity */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(210, 180, 140, 0.2);
            /* Clay-Muted border */
        }

        .amber-shadow {
            box-shadow: 0 10px 30px -10px rgba(160, 82, 45, 0.1);
            /* Terracotta-Earth subtle shadow */
        }

        .ambient-glow {
            position: absolute;
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, rgba(227, 160, 24, 0.15) 0%, transparent 70%);
            /* Mustard-Gold glow */
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(60px);
        }

        .nav-link,
        .mobile-nav-link {
            position: relative;
            color: #1c1c18;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            transition: all 0.2s ease;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            padding: 0.6rem 0.9rem;
            border-radius: 9999px;
            border: 1px solid transparent;
        }

        .nav-link::after,
        .mobile-nav-link::after {
            content: "";
            position: absolute;
            left: 0.9rem;
            right: 0.9rem;
            bottom: 0.35rem;
            height: 2px;
            background: rgba(227, 160, 24, 0.9);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.25s ease;
        }

        .nav-link:hover,
        .mobile-nav-link:hover {
            color: #6c2f00;
            background: rgba(227, 160, 24, 0.08);
            border-color: rgba(227, 160, 24, 0.2);
        }

        .nav-link.active,
        .mobile-nav-link.active {
            color: #6c2f00;
            background: rgba(227, 160, 24, 0.14);
            border-color: rgba(227, 160, 24, 0.4);
            box-shadow: 0 8px 18px -12px rgba(108, 47, 0, 0.6);
        }

        .nav-link.active::after,
        .mobile-nav-link.active::after,
        .nav-link:hover::after,
        .mobile-nav-link:hover::after {
            transform: scaleX(1);
        }

        .mobile-nav-link {
            display: flex;
            width: 100%;
            padding: 0.8rem 0.9rem;
            border-radius: 0.75rem;
            border: 1px solid transparent;
            color: #1c1c18;
        }
    </style>
</head>

<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden font-body-md text-body-md bg-surface">
    <?php $navItems = [
        ['label' => 'Accueil', 'href' => '/'],
        ['label' => 'Nos créations', 'href' => '/catalogue'],
        ['label' => 'Boutique', 'href' => '#boutique'],
        ['label' => 'Morphology Guide', 'href' => '#morphology-guide'],
        ['label' => 'Reviews', 'href' => '#reviews'],
    ]; ?>
    <!-- Ambient Background Glows -->
    <div class="ambient-glow top-[-10vw] left-[-10vw]"></div>
    <div class="ambient-glow bottom-[-10vw] right-[-10vw]" style="background: radial-gradient(circle, rgba(160, 82, 45, 0.1) 0%, transparent 70%);"></div>
    <!-- Navigation Bar -->
    <x-header :nav-items="$navItems" />
    <!-- Main Content Canvas -->
    <main class="flex-grow pt-20 relative z-10">
        <!-- Hero Section
        <section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden w-full">

            <div class="absolute inset-0 z-0">
                <div class="w-full h-full bg-cover bg-center object-cover transform scale-105 hover:scale-100 transition-transform duration-1000" data-alt="A stunning, high-fashion portrait of a majestic African woman wearing exquisite, hand-crafted Afrofusion attire. The garments feature complex West African woven textures intertwined with sleek, modern minimalist cuts. The color palette emphasizes rich terracotta, deep forest green, and radiant mustard gold against a soft, luminous ivory background. Soft, diffused 'golden hour' lighting creates a glowing, ethereal atmosphere, highlighting the elegant drape of the fabric and the quiet strength in her expression. Shot in a premium, airy gallery setting with subtle glassmorphic elements in the soft-focus background. Elegant, cultural, conscious aesthetic." style="background-image: url('{{ asset('images/hero-background.jpg') }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-surface-container-low/90 via-surface/40 to-transparent"></div>
            </div>

            <div class="relative z-10 w-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop flex flex-col items-start justify-center h-full mt-24 mb-2">
                <div class="glass-panel p-8 md:p-12 rounded-xl max-w-3xl amber-shadow transform translate-y-8 opacity-0 animate-fade-in-up" style="animation: fadeInUp 1s ease-out forwards; animation-delay: 0.2s;">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-12 h-[1px] bg-mustard-gold inline-block"></span>
                        <span class="font-label-caps text-label-caps text-terracotta-earth tracking-widest uppercase">Élégance Culturelle</span>
                    </div>
                    <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-charcoal-luxe mb-6 leading-tight">
                        Kekeli Wear — une lumière pour la mode au féminin
                    </h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant mb-10 max-w-xl">
                        Mode afrofusion — Pièces uniques - Cotonou, Bénin. Réveillez votre éclat intérieur avec des créations éco-responsables qui célèbrent notre héritage.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-6">
                        <a class="inline-flex items-center justify-center bg-mustard-gold text-charcoal-luxe font-label-caps text-label-caps px-8 py-4 rounded-full hover:bg-opacity-90 hover:-translate-y-1 transition-all duration-300 amber-shadow gap-2" href="https://wa.me/yourwhatsappnumber">
                            Commander sur WhatsApp
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                        <a class="inline-flex items-center justify-center border border-terracotta-earth text-terracotta-earth bg-transparent font-label-caps text-label-caps px-8 py-4 rounded-full hover:bg-terracotta-earth/5 hover:-translate-y-1 transition-all duration-300" href="#collections">
                            Découvrir nos créations
                        </a>
                    </div>
                </div>
            </div>
        </section>-->
        <section class="relative min-h-[88vh] w-full flex items-center overflow-hidden">
            {{-- IMAGE DE FOND --}}
            <div class="absolute inset-0">
                <img
                    src="{{ asset('images/hero-background.jpg') }}"
                    alt="Collection Kekeli Wear — mode afrofusion féminine"
                    class="w-full h-full object-cover object-center">
                {{-- Overlay élégant --}}
                <div class="absolute inset-0 bg-gradient-to-r from-black/65 via-black/30 to-transparent"></div>
                {{-- Léger voile en bas --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
            </div>
            {{-- CONTENU --}}
            <div class="relative z-10 w-full max-w-7xl mx-auto px-6 lg:px-12 pt-24">
                <div class="max-w-3xl text-white">
                    {{-- Signature --}}
                    <div class="flex items-center gap-4 mb-7">
                        <span class="w-14 h-px bg-mustard-gold"></span>
                        <span class="text-xs md:text-sm font-semibold uppercase tracking-[0.3em] text-mustard-gold">
                            Kekeli Wear
                        </span>
                    </div>
                    {{-- TITRE --}}
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold leading-[1.05] tracking-tight mb-7">
                        Une lumière pour
                        <span class="block text-mustard-gold">
                            la mode au féminin.
                        </span>
                    </h1>
                    {{-- DESCRIPTION --}}
                    <p class="text-base md:text-lg lg:text-xl leading-relaxed text-white/85 max-w-2xl mb-10">
                        Des créations afrofusion uniques qui associent l'élégance contemporaine
                        à la richesse de notre héritage culturel.
                        <span class="block mt-2 text-white/70">
                            Imaginées à Cotonou, au Bénin.
                        </span>
                    </p>
                    {{-- CTA --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        {{-- CTA principal --}}
                        <a
                            href="#collections"
                            class="inline-flex items-center justify-center gap-3
                           bg-mustard-gold text-charcoal-luxe
                           px-8 py-4 rounded-full
                           font-semibold text-sm uppercase tracking-wider
                           hover:bg-white hover:-translate-y-1
                           transition-all duration-300 shadow-xl">
                            Découvrir nos créations

                            <span class="material-symbols-outlined text-[19px]">
                                arrow_forward
                            </span>
                        </a>
                        {{-- CTA secondaire --}}
                        <a
                            href="https://wa.me/yourwhatsappnumber"
                            class="inline-flex items-center justify-center gap-3
                           border border-white/60
                           text-white
                           px-8 py-4 rounded-full
                           font-semibold text-sm uppercase tracking-wider
                           backdrop-blur-sm
                           hover:bg-white hover:text-charcoal-luxe
                           transition-all duration-300">
                            Commander sur WhatsApp
                        </a>

                    </div>

                </div>
            </div>
            {{-- INDICATION BAS DE PAGE --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 hidden md:flex flex-col items-center gap-3 text-white/70">
                <span class="text-[10px] uppercase tracking-[0.3em]">
                    Découvrir
                </span>
                <span class="material-symbols-outlined text-xl animate-bounce">
                    keyboard_arrow_down
                </span>
            </div>
        </section>
        <!-- Brand Story: L'éclat de Kekeli -->
        <section class="py-section-gap px-margin-mobile md:px-margin-desktop w-full max-w-container-max mx-auto relative" id="brand-story">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center">
                <!-- Imagery (Left Side) -->
                <div class="lg:col-span-6 relative h-full min-h-[600px] flex items-center justify-center">
                    <!-- Decorative Element behind images -->
                    <div class="absolute w-3/4 h-3/4 bg-surface-container-high rounded-full blur-3xl opacity-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-0"></div>
                    <div class="relative z-10 w-full h-full flex flex-col gap-6">
                        <!-- Image 1 (Mrs Dègnon) -->
                        <div class="w-4/5 ml-auto h-[400px] rounded-xl overflow-hidden amber-shadow relative transform hover:scale-[1.02] transition-transform duration-500">
                            <img class="w-full h-full object-cover" data-alt="A refined, professional portrait of Mrs Dègnon, co-founder of Kekeli Wear, standing confidently in a bright, modern minimalist studio. She is wearing an elegant Afrofusion blazer crafted from deep forest green indigenous West African textiles with delicate mustard gold geometric embroidery. The lighting is soft and cinematic, emphasizing her dignified expression and the premium texture of the fabric. The background features subtle, warm ivory tones and soft shadows, embodying an aesthetic of conscious, high-end cultural pride and entrepreneurial grace." src="{{ asset('images/dame1.jpg') }}" />
                            <!--<div class="absolute bottom-4 left-4 glass-panel px-4 py-2 rounded-full">
                                <span class="font-label-caps text-label-caps text-charcoal-luxe">Mme Dègnon</span>
                            </div>-->
                        </div>
                        <!-- Image 2 (Mlle Eunice) -->
                        <div class="w-3/4 mr-auto h-[350px] -mt-20 rounded-xl overflow-hidden amber-shadow relative z-20 border-4 border-surface transform hover:scale-[1.02] transition-transform duration-500">
                            <img class="w-full h-full object-cover" data-alt="A dynamic, creative portrait of Mlle Eunice, co-founder of Kekeli Wear, captured in a luminous, contemporary design space. She is adjusting a mannequin draped in a stunning terracotta earth-toned flowing garment that blends modern minimalist draping with traditional artisanal woven details. The bright, natural light from a nearby large window washes over the scene, highlighting the artisanal quality of the materials and her focused, artistic expression. The setting exudes a clean, airy, premium atelier atmosphere." src="{{ asset('images/dame2.jpg') }}" />
                            <!--<div class="absolute bottom-4 left-4 glass-panel px-4 py-2 rounded-full">
                                <span class="font-label-caps text-label-caps text-charcoal-luxe">Mlle Eunice</span>
                            </div>-->
                        </div>
                    </div>
                </div>
                <!-- Narrative Text (Right Side) -->
                <div class="lg:col-span-6 lg:pl-12 mt-12 lg:mt-0 relative z-10">
                    <h2 class="font-display-lg text-display-lg-mobile md:text-headline-lg text-charcoal-luxe mb-8">
                        L'élégance avec Kekeli
                    </h2>
                    <div class="font-quote text-quote text-on-surface-variant mb-10 space-y-6 border-l-2 border-clay-muted pl-6 py-2">
                        <p>
                            Née de la vision partagée de Mme Dègnon et Mlle Eunice, <strong>Kekeli</strong> (qui signifie "Lumière" en langue locale) est bien plus qu'une marque de vêtements. C'est une célébration de la beauté authentique et de l'artisanat ouest-africain.
                        </p>
                        <p class="font-body-lg text-body-lg">
                            Nous fusionnons l'élégance intemporelle de la mode moderne avec la richesse vibrante de nos textiles traditionnels. Chaque pièce est conçue pour révéler la lumière qui réside en chaque femme, tout en respectant notre environnement à travers une production éco-responsable.
                        </p>
                    </div>
                    <!-- Key Indicators Bento Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-12">
                        <!-- Indicator 1 -->
                        <div class="glass-panel p-6 rounded-xl flex flex-col items-start gap-4 hover:bg-white/40 transition-colors duration-300">
                            <div class="w-12 h-12 rounded-full bg-forest-deep/10 flex items-center justify-center text-forest-deep">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'wght' 300;">diamond</span>
                            </div>
                            <div>
                                <span class="block font-headline-md text-headline-md text-charcoal-luxe">100%</span>
                                <span class="block font-label-caps text-label-caps text-terracotta-earth mt-1">Pièces Uniques</span>
                            </div>
                        </div>
                        <!-- Indicator 2 -->
                        <div class="glass-panel p-6 rounded-xl flex flex-col items-start gap-4 hover:bg-white/40 transition-colors duration-300">
                            <div class="w-12 h-12 rounded-full bg-mustard-gold/10 flex items-center justify-center text-mustard-gold">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'wght' 300;">palette</span>
                            </div>
                            <div>
                                <span class="block font-headline-md text-headline-md text-charcoal-luxe">4</span>
                                <span class="block font-label-caps text-label-caps text-terracotta-earth mt-1">Univers de Style</span>
                            </div>
                        </div>
                        <!-- Indicator 3 -->
                        <div class="glass-panel p-6 rounded-xl flex flex-col items-start gap-4 hover:bg-white/40 transition-colors duration-300">
                            <div class="w-12 h-12 rounded-full bg-terracotta-earth/10 flex items-center justify-center text-terracotta-earth">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'wght' 300;">public</span>
                            </div>
                            <div>
                                <span class="block font-headline-md text-headline-md text-charcoal-luxe">Bénin</span>
                                <span class="block font-label-caps text-label-caps text-terracotta-earth mt-1">&amp; International</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <footer class="bg-forest-deep dark:bg-charcoal-luxe border-t border-clay-muted/20 w-full py-10 md:py-2 px-4 md:px-8 mt-auto">
        <div class="max-w-[1280px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-4 text-ivory-light">
            <!-- Brand Column -->
            <div class="flex flex-col gap-2 md:col-span-1">
                <span class="font-display-lg text-headline-lg text-mustard-gold">KEKELI</span>
                <p class="font-body-md text-body-md text-ivory-light/80">ELEGANCE IN EVERY THREAD.</p>
            </div>
            <!-- Links Columns -->
            <div class="md:col-span-3 grid grid-cols-2 md:grid-cols-3 gap-5 md:gap-6">
                <div class="flex flex-col gap-2">
                    <span class="font-label-caps text-label-caps text-mustard-gold font-bold mb-1">Explore</span>
                    <a class="font-body-md text-body-md text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300" href="#">Newsletter</a>
                    <a class="font-body-md text-body-md text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300" href="#">Sustainability</a>
                </div>
                <div class="flex flex-col gap-2">
                    <span class="font-label-caps text-label-caps text-mustard-gold font-bold mb-1">Customer Care</span>
                    <a class="font-body-md text-body-md text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300" href="#">Shipping</a>
                    <a class="font-body-md text-body-md text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300" href="#">Returns</a>
                </div>
                <div class="flex flex-col gap-2 col-span-2 md:col-span-1">
                    <span class="font-label-caps text-label-caps text-mustard-gold font-bold mb-1">Legal</span>
                    <a class="font-body-md text-body-md text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300" href="#">Legal</a>
                    <a class="font-body-md text-body-md text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300" href="#">Privacy Policy</a>
                </div>
            </div>
            <!-- Copyright -->
            <div class="col-span-1 md:col-span-4 mt-4 pt-4 border-t border-ivory-light/10 text-center font-label-caps text-label-caps text-ivory-light/60">
                © 2026 KEKELI WEAR. L'ÉLÉGANCE DANS CHAQUE FIL.
            </div>
        </div>
    </footer>
    <!-- Simple Animation Styles injected for the hero -->
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navbar scroll effect logic (simulated with CSS for brevity, ideally JS) */
        /* In a real scenario, JS would toggle a class on scroll to intensify background */
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nav = document.getElementById('main-nav');
            const toggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const desktopLinks = [...document.querySelectorAll('.nav-link')];
            const mobileLinks = [...document.querySelectorAll('.mobile-nav-link')];
            const allLinks = [...desktopLinks, ...mobileLinks];
            const sections = [...document.querySelectorAll('section[id]')];

            function updateHeaderState() {
                if (!nav) return;

                if (window.scrollY > 24) {
                    nav.classList.add('bg-surface/90');
                    nav.classList.remove('bg-surface/70');
                    nav.classList.add('shadow-[0_10px_30px_-8px_rgba(160,82,45,0.12)]');
                    nav.classList.remove('shadow-[0_4px_20px_-5px_rgba(160,82,45,0.05)]');
                } else {
                    nav.classList.add('bg-surface/70');
                    nav.classList.remove('bg-surface/90');
                    nav.classList.add('shadow-[0_4px_20px_-5px_rgba(160,82,45,0.05)]');
                    nav.classList.remove('shadow-[0_10px_30px_-8px_rgba(160,82,45,0.12)]');
                }
            }

            function setActiveLink(targetId) {
                if (!targetId) return;

                allLinks.forEach((link) => {
                    const href = link.getAttribute('href') || '';
                    const active = href === '#' + targetId;
                    link.classList.toggle('active', active);
                    link.setAttribute('aria-current', active ? 'page' : 'false');
                });
            }

            function updateActiveSection() {
                const hashId = window.location.hash.replace('#', '').trim();

                if (hashId && sections.some((section) => section.id === hashId)) {
                    setActiveLink(hashId);
                    return;
                }

                let currentId = 'brand-story';

                sections.forEach((section) => {
                    const rect = section.getBoundingClientRect();
                    if (rect.top <= window.innerHeight * 0.35 && rect.bottom >= window.innerHeight * 0.35) {
                        currentId = section.id;
                    }
                });

                setActiveLink(currentId);
            }

            function toggleMenu(forceOpen) {
                if (!mobileMenu || !toggle || !menuIcon) return;

                const shouldOpen = typeof forceOpen === 'boolean' ? forceOpen : mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden', !shouldOpen);
                toggle.setAttribute('aria-expanded', String(shouldOpen));
                menuIcon.textContent = shouldOpen ? 'close' : 'menu';
            }

            toggle?.addEventListener('click', () => {
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                toggleMenu(!isExpanded);
            });

            allLinks.forEach((link) => {
                link.addEventListener('click', () => {
                    const targetId = (link.getAttribute('href') || '').replace('#', '');
                    setActiveLink(targetId);

                    if (window.innerWidth < 768) {
                        toggleMenu(false);
                    }
                });
            });

            updateHeaderState();
            updateActiveSection();

            window.addEventListener('scroll', () => {
                updateHeaderState();
                updateActiveSection();
            }, {
                passive: true
            });

            window.addEventListener('hashchange', () => {
                updateActiveSection();
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768 && !mobileMenu.classList.contains('hidden')) {
                    toggleMenu(false);
                }
            });
        });
    </script>
</body>

</html>
