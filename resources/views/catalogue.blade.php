<!DOCTYPE html>

<html class="light" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>KEKELI WEAR - Nos Créations &amp; Boutique</title>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Libre+Caslon+Text:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Configuration -->
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
                }
            }
        }
    </script>
    <style>
        body {
            background-color: theme('colors.background');
            color: theme('colors.on-background');
        }

        .glass-panel {
            background: rgba(253, 250, 245, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        .shadow-luminous {
            box-shadow: 0 4px 20px -5px rgba(160, 82, 45, 0.05);
        }

        .hover-shadow-luminous:hover {
            box-shadow: 0 12px 30px -10px rgba(160, 82, 45, 0.15);
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Pattern Overlay */
        .bg-pattern-subtle {
            background-image: radial-gradient(theme('colors.clay-muted') 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.1;
        }
    </style>
</head>

<body class="antialiased selection:bg-mustard-gold selection:text-charcoal-luxe">
    <?php $navItems = [
        ['label' => 'Accueil', 'href' => '/'],
        ['label' => 'Nos créations', 'href' => '/catalogue'],
        ['label' => 'Boutique', 'href' => '#boutique'],
        ['label' => 'Morphology Guide', 'href' => '#morphology-guide'],
        ['label' => 'Reviews', 'href' => '#reviews'],
    ]; ?>
    <!-- TopNavBar -->
    <x-header :nav-items="$navItems" />
    <!-- Main Content -->
    <main class="pt-32 pb-section-gap relative">
        <!-- Background decorative element -->
        <div class="fixed inset-0 bg-pattern-subtle pointer-events-none z-[-1]"></div>
        <!-- Section: Nos Créations -->
        <section class="max-w-[1280px] mx-auto px-margin-desktop mb-section-gap">
            <header class="text-center mb-16">
                <h1 class="font-display-lg text-display-lg text-primary mb-4">Nos Créations</h1>
                <p class="font-quote text-quote text-on-surface-variant max-w-2xl mx-auto">Découvrez l'essence même de Kekeli à travers nos quatre univers stylistiques distincts. Chaque pièce témoigne à la fois de notre héritage culturel et de notre élégance moderne.</p>
            </header>
            <!-- Bento Grid Layout for Universes -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter auto-rows-[300px]">
                <!-- Universe 1: Tenues Réinventées (Large) -->
                <a class="md:col-span-8 row-span-2 group relative overflow-hidden bg-ivory-light shadow-luminous hover-shadow-luminous transition-all duration-500 rounded-lg" href="#">
                    <div class="absolute inset-0 bg-cover bg-[center_20%] w-full h-full transition-transform duration-700 group-hover:scale-105" data-alt="High fashion editorial shot of a reinvented traditional West African outfit, elegant, modern silhouette, minimalist background, warm dramatic lighting, showcasing rich textures and patterns." style="background-image: url('{{ asset('images/universe1.jpg') }}')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-charcoal-luxe/80 via-charcoal-luxe/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8 w-full">
                        <h2 class="font-headline-lg text-headline-lg text-on-primary mb-2">Tenues Réinventées</h2>
                        <div class="flex justify-between items-center">
                            <span class="font-label-caps text-label-caps text-mustard-gold">42 Créations</span>
                            <span class="material-symbols-outlined text-on-primary group-hover:translate-x-2 transition-transform">arrow_forward</span>
                        </div>
                    </div>
                </a>
                <!-- Universe 2: Confections Maison -->
                <a class="md:col-span-4 row-span-1 group relative overflow-hidden bg-ivory-light shadow-luminous hover-shadow-luminous transition-all duration-500 rounded-lg" href="#">
                    <div class="absolute inset-0 bg-cover bg-center w-full h-full transition-transform duration-700 group-hover:scale-105" data-alt="Close up of artisan hands sewing premium eco-friendly fabric, warm ambient lighting, elegant studio setting, highlighting craftsmanship and natural textures." style="background-image: url('{{ asset('images/universe2.jpg') }}')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-charcoal-luxe/80 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 w-full">
                        <h2 class="font-headline-md text-headline-md text-on-primary mb-1">Confections Maison</h2>
                        <span class="font-label-caps text-label-caps text-mustard-gold">28 Créations</span>
                    </div>
                </a>
                <!-- Universe 3: Sur-Mesure -->
                <a class="md:col-span-4 row-span-1 group relative overflow-hidden bg-ivory-light shadow-luminous hover-shadow-luminous transition-all duration-500 rounded-lg" href="#">
                    <div class="absolute inset-0 bg-cover bg-center w-full h-full transition-transform duration-700 group-hover:scale-105" data-alt="Tailoring tools, measuring tape, and fabric swatches elegantly arranged on a wooden table, soft warm light, conveying a bespoke, high-end tailoring experience." style="background-image: url('{{ asset('images/universe3.jpg') }}')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-charcoal-luxe/80 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 w-full">
                        <h2 class="font-headline-md text-headline-md text-on-primary mb-1">Sur-Mesure</h2>
                        <span class="font-label-caps text-label-caps text-mustard-gold">Expérience Unique</span>
                    </div>
                </a>
                <!-- Universe 4: Accessoires (Wide) -->
                <a class="md:col-span-12 row-span-1 group relative overflow-hidden bg-ivory-light shadow-luminous hover-shadow-luminous transition-all duration-500 rounded-lg" href="#">
                    <div class="absolute inset-0 bg-cover bg-center w-full h-full transition-transform duration-700 group-hover:scale-105" data-alt="Artistic flat lay of premium handmade accessories, jewelry with subtle West African geometric patterns, warm earthy tones, elegant composition on a minimalist surface." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCN1umm7uJqwZBdCEkfeSyIQbfj7Zjk8oEdrYeAfwF2w5_-ovKYOW9cxsiiSfb9gRd7OMVkZX88JlvsITlJkcdKabgYWKlGJkenUY8TbJjNHf2T9yDGR8Ouw_0aR0mxw5h5Jp8O64nKQh1lW5xyOb5DHE4P7uV7B6749PQq-ZK3U4b_ym4mNGRhFUNW2J9IzzP3xUiKLZhcPpJzg7ACjHI8kbEMdASS_c1yE5ppv3Ve13k1W3BG9OOuBQ')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-charcoal-luxe/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8 w-full flex justify-between items-end">
                        <div>
                            <h2 class="font-headline-lg text-headline-lg text-on-primary mb-2">Accessoires</h2>
                            <span class="font-label-caps text-label-caps text-mustard-gold">15 Créations</span>
                        </div>
                        <span class="material-symbols-outlined text-on-primary group-hover:translate-x-2 transition-transform text-3xl">arrow_forward</span>
                    </div>
                </a>
            </div>
        </section>
        <!-- Section: Boutique - Coups de Cœur -->
        <section class="max-w-[1280px] mx-auto px-margin-desktop mb-section-gap pt-16 border-t border-clay-muted/20">
            <header class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="font-display-lg text-display-lg text-primary mb-2">Boutique</h2>
                    <p class="font-headline-md text-headline-md text-terracotta-earth">Coups de Cœur</p>
                </div>
                <a class="font-label-caps text-label-caps text-primary border-b border-primary hover:text-mustard-gold hover:border-mustard-gold transition-colors pb-1" href="#">VOIR TOUT</a>
            </header>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <!-- Product Card 1 -->
                <article class="group cursor-pointer">
                    <div class="relative overflow-hidden bg-ivory-light rounded-lg aspect-[3/4] mb-4">
                        <img alt="Product Image" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105" data-alt="A model wearing a stunning mustard gold and deep forest green Afrofusion dress, minimalist high-end studio lighting, sophisticated and elegant posture." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCjTFkXs6yz6bfxheLdmbwNuoGe9WKSfx6kkAU3ydWKU1SynhqdT2jlE6EnKPU3uFUbbrV1ovAbaLT1VpyTa0w1fK2Uwrh7vzLA4b-OPgsOBjRDeyrqNNKoB0QsxvJojpQQi9RYa_3n3LXBpXR9IHAeCc8p6Yy-b0D5DDtiECpLKVi9UX0re9oP55I325Vfk8n1XwIPUGvMM8olslKM7Wj7vtPCYf2Ky0QBAE2rcxwh4dUhpSbxX_Wx9g" />
                        <!-- Chips -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-forest-deep text-on-primary font-label-caps text-label-caps px-3 py-1 rounded-xl shadow-sm">ÉCO-RESPONSABLE</span>
                            <span class="bg-terracotta-earth text-on-primary font-label-caps text-label-caps px-3 py-1 rounded-xl shadow-sm w-fit">PIÈCE UNIQUE</span>
                        </div>
                        <!-- Hover Action -->
                        <div class="absolute bottom-0 left-0 w-full p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out glass-panel">
                            <button class="w-full bg-mustard-gold text-charcoal-luxe font-label-caps text-label-caps py-3 rounded hover:opacity-90 transition-opacity">AJOUTER AU PANIER</button>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-body-lg text-body-lg text-primary font-bold">Robe Soleil de Minuit</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant mb-2">Tissu tissé main, Coupe asymétrique</p>
                        <p class="font-label-caps text-label-caps text-terracotta-earth">450 €</p>
                    </div>
                </article>
                <!-- Product Card 2 -->
                <article class="group cursor-pointer">
                    <div class="relative overflow-hidden bg-ivory-light rounded-lg aspect-[3/4] mb-4">
                        <img alt="Product Image" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105" data-alt="Close up of a tailored charcoal luxe blazer with subtle terracotta earth trim and West African motif embroidery on the lapel, premium studio lighting, showcasing texture." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzyYf2DlJkYyGb8Lc_WezlUACNJyGCx4vgftITbyRo_xvDOINUMmuw_o2G1HNlJa4kTxDy7n4K86eRwI2atbNZUE_RR0bqlHNVgJIVyu06xttEqUWwF4cOXpQCHwrWq2zClo5QP1Am_RCSLR9Vs7kTtdRouAbf_HdxiPNbESyVidBWko5cbBVPMRtSNluzPhNOVFbRUv7AI2ZgwtZBeebJu6OByeWIRfB2b50uRcI_313_2pGXCRx9Rg" />
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-forest-deep text-on-primary font-label-caps text-label-caps px-3 py-1 rounded-xl shadow-sm">ÉCO-RESPONSABLE</span>
                        </div>
                        <div class="absolute bottom-0 left-0 w-full p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out glass-panel">
                            <button class="w-full bg-mustard-gold text-charcoal-luxe font-label-caps text-label-caps py-3 rounded hover:opacity-90 transition-opacity">AJOUTER AU PANIER</button>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-body-lg text-body-lg text-primary font-bold">Veste Héritage</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant mb-2">Coton bio, Broderies discrètes</p>
                        <p class="font-label-caps text-label-caps text-terracotta-earth">320 €</p>
                    </div>
                </article>
                <!-- Product Card 3 -->
                <article class="group cursor-pointer">
                    <div class="relative overflow-hidden bg-ivory-light rounded-lg aspect-[3/4] mb-4">
                        <img alt="Product Image" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105" data-alt="Elegant flowing wide-leg trousers in an ivory light fabric, styled with a minimalist top, high fashion editorial photography, soft luminous lighting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD4qOYGPtvtjV4H9JIQaky2nUBb8kNrGSudGvQctIgZHuUycPuUrP9e0RWuAu7vXeAGReaZGdew5d0hzfXP0U5LhbD3hX9-9BpDZ65XvFARdkyOmBMgUADtX1EKvgtU-SBKopqg1DXWq0jV1eP2bPV-LZTZfYlJeGABQI3H4Lf-Wy7Zvr2FYrvsAwBnM5maRKsSWScRVnvcjXdbJzjhRtFxwvMIMuGFcOABKurRrz4b5AdBueKA33kB0w" />
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="bg-terracotta-earth text-on-primary font-label-caps text-label-caps px-3 py-1 rounded-xl shadow-sm w-fit">FAIT MAIN</span>
                        </div>
                        <div class="absolute bottom-0 left-0 w-full p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out glass-panel">
                            <button class="w-full bg-mustard-gold text-charcoal-luxe font-label-caps text-label-caps py-3 rounded hover:opacity-90 transition-opacity">AJOUTER AU PANIER</button>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-body-lg text-body-lg text-primary font-bold">Pantalon Souffle</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant mb-2">Lin mélangé, Coupe fluide</p>
                        <p class="font-label-caps text-label-caps text-terracotta-earth">210 €</p>
                    </div>
                </article>
            </div>
        </section>
        <!-- Section: Ordering Process -->
        <section class="max-w-[1280px] mx-auto px-margin-desktop bg-ivory-light py-16 rounded-lg shadow-luminous">
            <header class="text-center mb-12">
                <h2 class="font-headline-lg text-headline-lg text-primary mb-4">Votre Parcours Sur-Mesure</h2>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-xl mx-auto">Trois étapes simples pour que votre création Kekeli s'harmonise parfaitement avec votre éclat.</p>
            </header>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                <!-- Connecting Line (Desktop) -->
                <div class="hidden md:block absolute top-1/2 left-[15%] right-[15%] h-px bg-clay-muted/50 -translate-y-1/2 z-0"></div>
                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center text-center bg-ivory-light px-4">
                    <div class="w-16 h-16 rounded-full bg-mustard-gold/20 flex items-center justify-center mb-6 text-mustard-gold border-2 border-mustard-gold/30">
                        <span class="material-symbols-outlined text-3xl">checkroom</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md text-primary mb-2">Choix de la pièce</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">Découvrez nos collections et choisissez le modèle qui correspond le mieux à votre style personnel.</p>
                </div>
                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center text-center bg-ivory-light px-4">
                    <div class="w-16 h-16 rounded-full bg-mustard-gold/20 flex items-center justify-center mb-6 text-mustard-gold border-2 border-mustard-gold/30">
                        <span class="material-symbols-outlined text-3xl">straighten</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md text-primary mb-2">Mensurations</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">Suivez notre guide de morphologie pour fournir vos mesures exactes afin d'obtenir un ajustement parfait.</p>
                </div>
                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center text-center bg-ivory-light px-4">
                    <div class="w-16 h-16 rounded-full bg-mustard-gold/20 flex items-center justify-center mb-6 text-mustard-gold border-2 border-mustard-gold/30">
                        <span class="material-symbols-outlined text-3xl">auto_awesome</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md text-primary mb-2">Coupe Idéale</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">Nos artisans confectionnent votre pièce, assurant l'équilibre parfait entre confort et élégance.</p>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <footer class="w-full py-section-gap px-margin-desktop bg-forest-deep dark:bg-charcoal-luxe border-t border-clay-muted/20">
        <div class="max-w-[1280px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-gutter">
            <!-- Brand & Copyright -->
            <div class="md:col-span-1 flex flex-col gap-4">
                <span class="font-display-lg text-headline-lg text-mustard-gold">KEKELI</span>
                <p class="font-body-md text-body-md text-ivory-light">© 2024 KEKELI WEAR. ELEGANCE IN EVERY THREAD.</p>
            </div>
            <!-- Links Column 1 -->
            <div class="flex flex-col gap-3">
                <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Newsletter</a>
                <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Sustainability</a>
            </div>
            <!-- Links Column 2 -->
            <div class="flex flex-col gap-3">
                <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Shipping</a>
                <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Returns</a>
            </div>
            <!-- Links Column 3 -->
            <div class="flex flex-col gap-3">
                <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Legal</a>
                <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Privacy Policy</a>
            </div>
        </div>
    </footer>
</body>

</html>
