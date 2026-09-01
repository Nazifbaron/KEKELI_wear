<!DOCTYPE html>

<html class="light" lang="fr"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>KEKELI Wear - Nos Créations &amp; Boutique</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Libre+Caslon+Text:ital,wght@0,400;0,700;1,400&amp;family=Hanken+Grotesk:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-container": "#fed65b",
                        "tertiary-fixed": "#c6e9e9",
                        "inverse-primary": "#ffb68c",
                        "tertiary-fixed-dim": "#abcdcd",
                        "on-error": "#ffffff",
                        "on-secondary-fixed": "#241a00",
                        "forest-deep": "#1B3022",
                        "inverse-on-surface": "#f3f0ea",
                        "surface-container-high": "#ebe8e2",
                        "surface-variant": "#e5e2dc",
                        "terracotta-earth": "#A0522D",
                        "primary-container": "#8b4513",
                        "surface-dim": "#dcdad4",
                        "surface-container-low": "#f6f3ed",
                        "on-secondary-container": "#745c00",
                        "on-tertiary": "#ffffff",
                        "error": "#ba1a1a",
                        "surface-container-highest": "#e5e2dc",
                        "on-surface": "#1c1c18",
                        "primary-fixed-dim": "#ffb68c",
                        "clay-muted": "#D2B48C",
                        "charcoal-luxe": "#1A1A1A",
                        "surface-tint": "#934b19",
                        "on-tertiary-container": "#b3d6d5",
                        "on-secondary": "#ffffff",
                        "on-background": "#1c1c18",
                        "secondary": "#735c00",
                        "on-secondary-fixed-variant": "#574500",
                        "tertiary": "#264646",
                        "primary-fixed": "#ffdbc9",
                        "error-container": "#ffdad6",
                        "on-primary-container": "#ffc29f",
                        "on-primary-fixed": "#321200",
                        "background": "#fcf9f3",
                        "secondary-fixed-dim": "#e9c349",
                        "primary": "#6c2f00",
                        "secondary-fixed": "#ffe088",
                        "on-tertiary-fixed": "#002020",
                        "outline-variant": "#dac2b6",
                        "on-surface-variant": "#54433a",
                        "on-tertiary-fixed-variant": "#2c4c4c",
                        "surface": "#fcf9f3",
                        "surface-bright": "#fcf9f3",
                        "surface-container-lowest": "#ffffff",
                        "inverse-surface": "#31312d",
                        "surface-container": "#f0eee8",
                        "on-error-container": "#93000a",
                        "on-primary": "#ffffff",
                        "on-primary-fixed-variant": "#753401",
                        "mustard-gold": "#E3A018",
                        "ivory-light": "#FDFAF5",
                        "tertiary-container": "#3e5e5e",
                        "outline": "#877369"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "gutter": "24px",
                        "unit": "8px",
                        "container-max": "1280px",
                        "margin-desktop": "64px",
                        "margin-mobile": "16px",
                        "section-gap": "120px"
                    },
                    "fontFamily": {
                        "quote": ["Libre Caslon Text"],
                        "display-lg": ["Libre Caslon Text"],
                        "display-lg-mobile": ["Libre Caslon Text"],
                        "label-caps": ["Hanken Grotesk"],
                        "headline-lg": ["Libre Caslon Text"],
                        "body-lg": ["Hanken Grotesk"],
                        "body-md": ["Hanken Grotesk"],
                        "headline-md": ["Libre Caslon Text"]
                    },
                    "fontSize": {
                        "quote": ["20px", { "lineHeight": "30px", "fontWeight": "400" }],
                        "display-lg": ["64px", { "lineHeight": "72px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "display-lg-mobile": ["40px", { "lineHeight": "48px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                        "label-caps": ["12px", { "lineHeight": "16px", "letterSpacing": "0.1em", "fontWeight": "700" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "600" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md overflow-x-hidden">
<!-- TopAppBar -->
<header class="fixed top-0 w-full z-50 bg-surface/70 dark:bg-charcoal-luxe/70 backdrop-blur-xl shadow-sm shadow-primary/5 hidden md:flex justify-between items-center px-margin-mobile h-20 w-full max-w-container-max mx-auto bg-transparent">
<button class="text-on-surface dark:text-surface-variant hover:opacity-80 transition-opacity active:scale-95 transition-transform flex items-center justify-center p-2">
<span class="material-symbols-outlined text-2xl">menu</span>
</button>
<div class="font-display-lg text-display-lg tracking-tighter text-primary dark:text-inverse-primary">
            KEKELI
        </div>
<button class="text-on-surface dark:text-surface-variant hover:opacity-80 transition-opacity active:scale-95 transition-transform flex items-center justify-center p-2">
<span class="material-symbols-outlined text-2xl">shopping_bag</span>
</button>
</header>
<!-- Main Content -->
<main class="w-full max-w-container-max mx-auto pt-24 md:pt-32 pb-section-gap">
<!-- Nos Créations Header -->
<section class="px-margin-mobile md:px-margin-desktop mb-16 text-center">
<h1 class="font-display-lg-mobile text-display-lg-mobile md:font-display-lg md:text-display-lg text-primary mb-6">Nos Créations</h1>
<p class="font-quote text-quote text-on-surface-variant max-w-2xl mx-auto">
                L'art de la lumière tissée. Découvrez nos quatre univers où l'héritage ouest-africain rencontre l'élégance contemporaine.
            </p>
</section>
<!-- Catalog Universes -->
<div class="flex flex-col gap-section-gap">
<!-- Universe 1: Tenues Réinventées -->
<section class="px-margin-mobile md:px-margin-desktop">
<div class="flex flex-col md:flex-row justify-between items-end mb-8 border-b-2 border-clay-muted/20 pb-4">
<div>
<span class="font-label-caps text-label-caps text-terracotta-earth block mb-2">UPCYCLING &amp; VINTAGE</span>
<h2 class="font-headline-lg text-headline-lg text-primary">Tenues Réinventées</h2>
</div>
<p class="font-body-md text-body-md text-on-surface-variant max-w-md text-right hidden md:block">
                        Redonner vie à des pièces oubliées. Une démarche éco-responsable pour une mode qui a du sens.
                    </p>
</div>
<div class="flex overflow-x-auto hide-scrollbar gap-gutter snap-x snap-mandatory pb-8">
<!-- Item 1 -->
<div class="min-w-[280px] md:min-w-[320px] snap-center group">
<div class="relative bg-ivory-light aspect-[3/4] mb-4 overflow-hidden rounded-lg shadow-sm shadow-primary/5 hover:shadow-lg transition-shadow duration-300">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="A high-fashion editorial photograph of a model wearing an upcycled West African inspired outfit. The garment blends traditional woven textiles with modern minimalist cuts. Warm, natural lighting mimicking sunlight diffusion. Shot in a minimalist gallery space with light ivory walls and subtle terracotta accents. Elegant, eco-conscious aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDJIKPuEGCsG6ny3ZPIT0Jkec4SLmf6weEbzA1X3EJlPfAYvnTvlMgQeY3ZkUf9sHF_H4E9TOj9gTqXGXtWYZFKwaq3i18ZjyPr7NVQ157pw5XRYYKPRyWDUNzV3CL7bCoiWG9AvV9WiXXGdnn4Ux5jZ3f0ZhcPVLQ8hmd4_bqeoaqvSq-nJUvRoRowmiD5wfAnL_92c_IMxqImtqvdqwAQ-cf33APHl5crE5ESaY0a_xP1lOOcNyLfRg"/>
<div class="absolute top-4 left-4 flex gap-2">
<span class="bg-forest-deep text-ivory-light font-label-caps text-label-caps px-3 py-1 rounded-xl">ÉCO-RESPONSABLE</span>
</div>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-1">Veste Aube</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Pièce Unique</p>
</div>
<!-- Item 2 -->
<div class="min-w-[280px] md:min-w-[320px] snap-center group">
<div class="relative bg-ivory-light aspect-[3/4] mb-4 overflow-hidden rounded-lg shadow-sm shadow-primary/5 hover:shadow-lg transition-shadow duration-300">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="A close-up shot of an upcycled blazer featuring intricate traditional African embroidery on the lapels. The fabric is a rich earthy tone, photographed in a bright, minimalist studio setting with soft glowing shadows. The image conveys premium craftsmanship and eco-friendly luxury fashion." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDr8nOZZ4eDivxQuyYCo1fK_wzSQ4-J_aE-gweaYtBpOHkVtSMIaKtm_SyvmMZ1RxTuPxOjpkS3b-9-yXmfCrLW5Jr1rdGWHefVTHXEkBTNLIyiMCHgl4rVBrCafnl-QadQ9pUEcJBcn2YktE0xb6EAXIfteZjN-NyH7twEZbWPxF-DTZtuTQNseD_l2V8EUmqUOG-LN-s64Tn1MfsF8m7nMk6bXHj-RpHiXq3NE3jO3tUZRjZqXKsMAQ"/>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-1">Manteau Zénith</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Pièce Unique</p>
</div>
</div>
</section>
</div>
</main>
<!-- Footer -->
<footer class="bg-forest-deep dark:bg-black text-ivory-light font-body-md text-body-md w-full mt-section-gap border-t border-clay-muted/20 flex flex-col items-center text-center p-margin-mobile gap-unit w-full">
<div class="font-display-lg text-headline-md text-mustard-gold mb-4">KEKELI</div>
<p class="mb-4">© 2024 KEKELI Wear. Crafted with Light.</p>
<div class="flex gap-4">
<a class="text-clay-muted hover:text-mustard-gold transition-colors" href="#">Terms of Service</a>
<a class="text-clay-muted hover:text-mustard-gold transition-colors" href="#">Privacy Policy</a>
<a class="text-clay-muted hover:text-mustard-gold transition-colors" href="#">Eco-Responsibility</a>
</div>
</footer>
</body></html>