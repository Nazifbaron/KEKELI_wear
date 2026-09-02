<!DOCTYPE html>

<html class="light" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Espace Client - Guide de Morphologie | KEKELI Wear</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;700&amp;family=Libre+Caslon+Text:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
    <style>
        .glass-panel {
            background: rgba(253, 250, 245, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .kekeli-shadow {
            box-shadow: 0 4px 20px -5px rgba(160, 82, 45, 0.05);
        }

        .input-kekeli {
            border: none;
            border-bottom: 2px solid #D2B48C;
            background: transparent;
            transition: all 0.3s ease;
        }

        .input-kekeli:focus {
            outline: none;
            border-bottom-color: #E3A018;
            box-shadow: none;
        }
    </style>
</head>

<body class="bg-background text-on-surface min-h-screen flex flex-col font-body-md antialiased selection:bg-mustard-gold selection:text-charcoal-luxe">
    <!-- TopNavBar -->
    <header class="fixed top-0 w-full z-50 bg-surface/70 backdrop-blur-md shadow-[0_4px_20px_-5px_rgba(160,82,45,0.05)]">
        <div class="max-w-[1280px] mx-auto px-margin-desktop flex justify-between items-center h-20">
            <a class="font-display-lg text-headline-lg tracking-tighter text-terracotta-earth" href="#">KEKELI</a>
            <nav class="hidden md:flex space-x-8">
                <a class="font-label-caps text-label-caps text-on-surface hover:text-mustard-gold transition-colors duration-300" href="/">Accueil</a>
                <a class="font-label-caps text-label-caps text-on-surface hover:text-mustard-gold transition-colors duration-300" href="/catalogue">Nos créations</a>
                <a class="font-label-caps text-label-caps text-on-surface hover:text-mustard-gold transition-colors duration-300" href="#">Boutique</a>
                <a class="font-label-caps text-label-caps text-mustard-gold border-b-2 border-mustard-gold pb-1 cursor-pointer active:scale-95 transition-transform" href="#">Morphology Guide</a>
                <a class="font-label-caps text-label-caps text-on-surface hover:text-mustard-gold transition-colors duration-300" href="#">Reviews</a>
            </nav>
            <div class="flex items-center space-x-6 text-primary">
                <button aria-label="shopping_bag" class="hover:opacity-80 transition-opacity duration-200 cursor-pointer active:scale-95 transition-transform">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">shopping_bag</span>
                </button>
                <button aria-label="person" class="hover:opacity-80 transition-opacity duration-200 cursor-pointer active:scale-95 transition-transform">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">person</span>
                </button>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="flex-grow pt-32 pb-section-gap px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto w-full">
        <!-- Header Section -->
        <div class="text-center mb-16 max-w-3xl mx-auto">
            <h1 class="font-headline-lg text-headline-lg text-charcoal-luxe mb-6">Espace Client &amp; Sur-Mesure</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant">
                Découvrez votre morphologie et personnalisez vos créations. Notre approche sur-mesure sublime votre silhouette unique tout en célébrant l'élégance de l'héritage textile.
            </p>
        </div>
        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
            <!-- Morphologie Guide (Left/Top) -->
            <section class="lg:col-span-7 bg-ivory-light rounded-xl p-8 kekeli-shadow relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-mustard-gold/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
                <h2 class="font-headline-md text-headline-md text-charcoal-luxe mb-8 relative z-10 flex items-center">
                    <span class="material-symbols-outlined mr-3 text-terracotta-earth">accessibility_new</span>
                    Guide de Morphologie
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                    <!-- Morph Types (Interactive List) -->
                    <div class="space-y-4">
                        <button class="w-full text-left p-4 rounded-lg border border-mustard-gold bg-mustard-gold/10 transition-all flex items-center justify-between group">
                            <span class="font-body-lg text-body-lg text-charcoal-luxe font-medium">Sablier (X)</span>
                            <span class="material-symbols-outlined text-mustard-gold group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                        <button class="w-full text-left p-4 rounded-lg border border-transparent hover:bg-surface-variant transition-all flex items-center justify-between group">
                            <span class="font-body-lg text-body-lg text-on-surface-variant">Triangle inversé (V)</span>
                            <span class="material-symbols-outlined text-outline-variant group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                        <button class="w-full text-left p-4 rounded-lg border border-transparent hover:bg-surface-variant transition-all flex items-center justify-between group">
                            <span class="font-body-lg text-body-lg text-on-surface-variant">Poire (A)</span>
                            <span class="material-symbols-outlined text-outline-variant group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                        <button class="w-full text-left p-4 rounded-lg border border-transparent hover:bg-surface-variant transition-all flex items-center justify-between group">
                            <span class="font-body-lg text-body-lg text-on-surface-variant">Ronde/Pomme (O)</span>
                            <span class="material-symbols-outlined text-outline-variant group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                        <button class="w-full text-left p-4 rounded-lg border border-transparent hover:bg-surface-variant transition-all flex items-center justify-between group">
                            <span class="font-body-lg text-body-lg text-on-surface-variant">Rectangle (H)</span>
                            <span class="material-symbols-outlined text-outline-variant group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                    <!-- Morph Details Display -->
                    <div class="bg-surface rounded-lg p-6 border border-surface-variant flex flex-col items-center text-center">
                        <div class="w-32 h-48 mb-6 relative">
                            <img class="object-cover w-full h-full rounded opacity-90" data-alt="Minimalist elegant line art illustration of a woman with an hourglass body shape. Soft, warm amber-tinted lighting. Cultural West African subtle motifs in the background. High-end fashion editorial style, light mode aesthetic, clean lines on an ivory background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDVoY6gC1_oNsq7St8P40ThtiX_PCxx1T1NIaJapwEre4ikTs-YIu10Lcjrer87mtwkwzX7cy1INVQY2bCoPF46drik89n9R4ghgDeCyn7gQIzZwyEzRSLPM184u3BUEO0Y2g6MUfCTEIbcFHyXC43kZHOCFsU3nsPF42kfHMvT5QSB1sGrrA2k7tfqoBf5CikEu7_qf8ET1u9mgWOeb8ArXsKeUUEFV8PGQeAgj95pDY_wkH2YCQR5SQ" />
                        </div>
                        <h3 class="font-headline-md text-headline-md text-terracotta-earth mb-2">Morphologie en Sablier</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant mb-4">
                            Épaules et hanches alignées avec une taille très marquée.
                        </p>
                        <div class="mt-auto">
                            <h4 class="font-label-caps text-label-caps text-charcoal-luxe mb-2">Conseil Style</h4>
                            <p class="font-body-md text-body-md text-on-surface text-sm italic">
                                "Privilégiez les coupes cintrées et les ceintures pour souligner votre taille naturelle. Nos vestes ajustées en bogolan sont parfaites pour vous."
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Measurements Form (Right/Bottom) -->
            <section class="lg:col-span-5 bg-surface rounded-xl p-8 border border-surface-variant relative overflow-hidden">
                <h2 class="font-headline-md text-headline-md text-charcoal-luxe mb-8 flex items-center">
                    <span class="material-symbols-outlined mr-3 text-mustard-gold">straighten</span>
                    Mensurations
                </h2>
                <form class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">Nom Complet</label>
                            <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe placeholder-outline-variant" placeholder="Votre nom" type="text" />
                        </div>
                        <div>
                            <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">WhatsApp</label>
                            <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe placeholder-outline-variant" placeholder="+229 01 90 34 56 78" type="tel" />
                        </div>
                    </div>
                    <div class="border-t border-clay-muted/30 pt-6 mt-6">
                        <h3 class="font-label-caps text-label-caps text-terracotta-earth mb-4">Mesures (en cm)</h3>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">Dos</label>
                                <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe" placeholder="20" type="number" />
                            </div>
                            <div>
                                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">Poitrine </label>
                                <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe" placeholder="0" type="number" />
                            </div>
                            <div>
                                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">Taille hauteur en (cm)</label>
                                <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe" placeholder="85" type="number" />
                            </div>
                            <div>
                                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">Tour de taille (cm)</label>
                                <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe" placeholder="22" type="number" />
                            </div>
                            <div>
                                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">Hanches</label>
                                <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe" placeholder="55" type="number" />
                            </div>
                            <div>
                                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">Longueur robe (cm)</label>
                                <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe" placeholder="20" type="number" />
                            </div>
                            <div>
                                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">Longueur haut (cm)</label>
                                <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe" placeholder="60" type="number" />
                            </div>
                            <div>
                                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-2">Longueur jupe / pantalon</label>
                                <input class="input-kekeli w-full pb-2 font-body-md text-charcoal-luxe" placeholder="100" type="number" />
                            </div>
                        </div>
                    </div>
                    <button class="w-full bg-mustard-gold text-charcoal-luxe font-label-caps text-label-caps py-4 rounded-lg mt-8 hover:bg-mustard-gold/90 transition-colors duration-300 shadow-sm flex justify-center items-center" type="button">
                        <span class="material-symbols-outlined mr-2 text-sm">save</span>
                        Envoyer mes mesures par Whatsapp
                    </button>
                </form>
            </section>
            <!-- Virtual Rendez-vous (Bottom Left) -->
            <section class="lg:col-span-6 bg-forest-deep text-ivory-light rounded-xl p-8 relative overflow-hidden group cursor-pointer">
                <div class="absolute inset-0 bg-[url('placeholder')] bg-cover bg-center opacity-20 mix-blend-overlay group-hover:scale-105 transition-transform duration-700" data-alt="A serene, sophisticated high-end boutique interior with soft natural light streaming through large windows. Earthy tones, minimalist decor, subtle West African art pieces. Warm, inviting, luxury aesthetic suitable for a background texture." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAxXyZoU8L8S8eyQEHMONAPmTS-DgtSO7IcUDPMZyg9Jjfh4NqA4B8rgcwVPVe6hv7mPXMqxm66aYY7xdMuDSrRVhZ0S2geoVzjfSqr7KL7mAfPYNhMYVpOXPKd1_YSzpsZzi5hobvBlgqDe4F5705jp4eMTjNclZ0ibo_zz5V5GkkJIaRt-SDIWriqO6F8MAm1LL62QAxVXFMQ94vBcil3B_V1ZAzWQJmx7rAyddSThpbDDIH6v2U67Q')"></div>
                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <span class="material-symbols-outlined text-mustard-gold mb-4 text-3xl">video_camera_front</span>
                        <h2 class="font-headline-md text-headline-md mb-2">Rendez-vous Virtuel</h2>
                        <p class="font-body-md text-body-md text-ivory-light/80 mb-6">
                            Planifiez une consultation vidéo avec nos stylistes pour une prise de mesures assistée et des conseils personnalisés.
                        </p>
                    </div>
                    <button class="self-start px-6 py-3 border border-mustard-gold text-mustard-gold font-label-caps text-label-caps rounded hover:bg-mustard-gold hover:text-forest-deep transition-all duration-300">
                        Réserver un créneau
                    </button>
                </div>
            </section>
            <!-- Demande de Style (Bottom Right) -->
            <section class="lg:col-span-6 bg-terracotta-earth text-ivory-light rounded-xl p-8 relative overflow-hidden group cursor-pointer">
                <div class="absolute inset-0 opacity-10 mix-blend-overlay" style="background-image: radial-gradient(circle at 50% 50%, #ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <span class="material-symbols-outlined text-mustard-gold mb-4 text-3xl">palette</span>
                        <h2 class="font-headline-md text-headline-md mb-2">Demande de Style</h2>
                        <p class="font-body-md text-body-md text-ivory-light/80 mb-6">
                            Partagez vos inspirations et laissez nos artisans imaginer une création unique qui reflète votre éclat intérieur.
                        </p>
                    </div>
                    <button class="self-start px-6 py-3 bg-ivory-light text-terracotta-earth font-label-caps text-label-caps rounded hover:bg-mustard-gold hover:text-charcoal-luxe transition-all duration-300">
                        Créer une demande
                    </button>
                </div>
            </section>
        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-forest-deep w-full py-section-gap px-margin-desktop border-t border-clay-muted/20">
        <div class="max-w-[1280px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-gutter">
            <div class="md:col-span-1">
                <div class="font-display-lg text-headline-lg text-mustard-gold mb-4">KEKELI</div>
                <p class="font-body-md text-body-md text-ivory-light/60">
                    © 2026 KEKELI WEAR. L'ÉLÉGANCE DANS CHAQUE FIL.
                </p>
            </div>
            <div class="md:col-span-3 grid grid-cols-2 md:grid-cols-3 gap-8">
                <div class="flex flex-col space-y-3">
                    <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Newsletter</a>
                    <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Sustainability</a>
                </div>
                <div class="flex flex-col space-y-3">
                    <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Shipping</a>
                    <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Returns</a>
                </div>
                <div class="flex flex-col space-y-3">
                    <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Legal</a>
                    <a class="font-label-caps text-label-caps text-ivory-light/80 hover:text-mustard-gold transition-colors duration-300 cursor-pointer" href="#">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
