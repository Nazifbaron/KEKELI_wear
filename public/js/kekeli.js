/* ============================================================
   KEKELI WEAR — JavaScript principal (kekeli.js)
   Toutes les interactions sont connectées aux routes Laravel :
   /api/products/{id}/like  → toggle like
   /api/products/{id}/view  → tracker vue
   /api/promo/verify        → vérifier code promo
   /api/measurements        → sauvegarder mensurations
   /api/cart (GET/POST/DELETE) → gestion panier
   /api/stats               → stats globales (refresh 60s)
   /checkout                → passer commande
============================================================ */

/* ============================================================
   CONFIG GLOBALE
   Variables injectées depuis Blade via home.blade.php
============================================================ */
var WA_NUMBER   = window.WA_NUMBER    || '22901401349';
var CSRF        = window.CSRF_TOKEN
                  || document.querySelector('meta[name="csrf-token"]')?.content
                  || '';

/* ============================================================
   UTILITAIRES HTTP
============================================================ */

/* POST JSON vers l'API Laravel */
function apiPost(url, data) {
    return fetch(url, {
        method:  'POST',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  CSRF,
            'Accept':        'application/json',
        },
        body: JSON.stringify(data),
    }).then(r => r.json());
}

/* GET vers l'API Laravel */
function apiGet(url) {
    return fetch(url, {
        headers: { 'Accept': 'application/json' },
    }).then(r => r.json());
}

/* DELETE vers l'API Laravel */
function apiDelete(url) {
    return fetch(url, {
        method:  'DELETE',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
        },
    }).then(r => r.json());
}

/* PATCH vers l'API Laravel */
function apiPatch(url, data) {
    return fetch(url, {
        method:  'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept':       'application/json',
        },
        body: JSON.stringify(data),
    }).then(r => r.json());
}

/* ============================================================
   TOAST NOTIFICATION
============================================================ */
var toastTimer;
function showToast(msg, type) {
    var t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.className   = 'toast show' + (type === 'err' ? ' toast-err' : '');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), 3200);
}

/* ============================================================
   SCROLL DOUX
============================================================ */
function smoothScrollTo(id) {
    var el = document.getElementById(id);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/* ============================================================
   INITIALISATION AU CHARGEMENT
============================================================ */
document.addEventListener('DOMContentLoaded', function () {

    /* --- Mobile menu --- */
    var toggle = document.getElementById('nav-toggle');
    var menu   = document.getElementById('mobile-menu');
    if (toggle && menu) {
        toggle.addEventListener('click', () => menu.classList.toggle('open'));
        menu.querySelectorAll('a').forEach(a =>
            a.addEventListener('click', () => menu.classList.remove('open'))
        );
    }

    /* --- Scroll spy navigation --- */
    var sections = ['hero','about','catalogue','featured','shop','morphology','reviews','contact'];
    window.addEventListener('scroll', function () {
        var y = window.scrollY + 80;
        sections.forEach(id => {
            var el = document.getElementById(id);
            if (!el) return;
            if (el.offsetTop <= y && el.offsetTop + el.offsetHeight > y) {
                document.querySelectorAll('.nav-link').forEach(a =>
                    a.classList.toggle('active', a.getAttribute('href') === '#' + id)
                );
            }
        });
    }, { passive: true });

    /* --- Charger le panier depuis la BD --- */
    loadCart();

    /* --- Initialiser le compteur filtre boutique --- */
    refreshFilterCount('all');

    /* --- Tracker les vues au survol des produits --- */
    document.querySelectorAll('.prod-card[data-id]').forEach(card => {
        var tracked = false;
        card.addEventListener('mouseenter', function () {
            if (tracked) return;
            tracked = true;
            apiPost('/api/products/' + card.dataset.id + '/view', {})
                .then(data => {
                    var el = document.getElementById('views-' + card.dataset.id);
                    if (el && data.views !== undefined) {
                        el.textContent = data.views.toLocaleString('fr-FR');
                    }
                })
                .catch(() => {}); // Silencieux si offline
        });
    });

});

/* ============================================================
   HERO CAROUSEL
   totalSlides est injecté depuis hero.blade.php via @push('scripts')
   window.TOTAL_SLIDES = {{ $slides->count() ?? 3 }}
============================================================ */
var currentSlide = 0;
var totalSlides  = window.TOTAL_SLIDES || 3;
var slideTimer;

function goSlide(n) {
    document.querySelectorAll('.slide').forEach((s, i) =>
        s.classList.toggle('hidden', i !== n)
    );
    document.querySelectorAll('.cdot').forEach((d, i) =>
        d.classList.toggle('active', i === n)
    );
    currentSlide = n;
}

function nextSlide() { goSlide((currentSlide + 1) % totalSlides); }
function prevSlide() { goSlide((currentSlide - 1 + totalSlides) % totalSlides); }

/* Auto-play 5 secondes */
slideTimer = setInterval(nextSlide, 5000);

/* ============================================================
   FILTRE CATALOGUE + BOUTIQUE
   Click sur univ-card → filtre la grille boutique + scroll
============================================================ */
var currentCat = 'all';

function filterByCategory(cat, btn) {
    currentCat = cat;

    /* Afficher / masquer les produits */
    document.querySelectorAll('.prod-card').forEach(c => {
        c.style.display = (cat === 'all' || c.dataset.cat === cat) ? 'block' : 'none';
    });

    /* Sync boutons filtre boutique */
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    if (btn && btn.classList.contains('filter-btn')) {
        btn.classList.add('active');
    } else {
        /* Appelé depuis une univ-card ou le hero — sync manuel */
        document.querySelectorAll('.filter-btn').forEach(b => {
            if (cat === 'all' && b.textContent.trim() === 'Tout') b.classList.add('active');
        });
    }

    /* Sync univ-cards */
    document.querySelectorAll('.univ-card').forEach(u =>
        u.classList.toggle('active', u.id === 'ucard-' + cat)
    );

    /* Afficher/masquer la note sur-mesure */
    var smNote = document.getElementById('sm-note');
    if (smNote) smNote.classList.toggle('show', cat === 'surmesure');

    /* Mettre à jour le compteur de résultats */
    refreshFilterCount(cat);

    /* Scroll vers la boutique si filtre depuis le catalogue */
    if (cat !== 'all') setTimeout(() => smoothScrollTo('shop'), 150);
}

function refreshFilterCount(cat) {
    var visible = [...document.querySelectorAll('.prod-card')]
        .filter(c => cat === 'all' || c.dataset.cat === cat).length;
    var el = document.getElementById('filter-count-num');
    if (el) el.textContent = visible;
}

/* ============================================================
   LIKE / UNLIKE
   Appel POST /api/products/{id}/like
   Réponse : { liked: bool, likes: int }
============================================================ */
function toggleLikeApi(btn, productId) {
    apiPost('/api/products/' + productId + '/like', {})
        .then(data => {
            /* Mettre à jour l'apparence du bouton */
            btn.classList.toggle('liked', data.liked);
            btn.textContent = data.liked ? '♥' : '♡';

            /* Mettre à jour tous les compteurs de ce produit sur la page */
            document.querySelectorAll('#likes-' + productId).forEach(el => {
                el.textContent = data.likes;
            });

            showToast(data.liked ? '❤ Ajouté à vos favoris' : 'Favori retiré');
        })
        .catch(() => showToast('Erreur réseau', 'err'));
}

/* ============================================================
   CODE PROMO
   Appel POST /api/promo/verify
   Réponse : { valid: bool, discount: float, type: string, message: string }
============================================================ */
var activePromo = null;

function verifyPromo() {
    var code  = document.getElementById('promo-input')?.value.trim();
    var okEl  = document.getElementById('promo-ok');
    var errEl = document.getElementById('promo-err');
    if (!code) return;

    /* Réinitialiser les messages */
    if (okEl)  okEl.style.display  = 'none';
    if (errEl) errEl.style.display = 'none';

    apiPost('/api/promo/verify', { code })
        .then(data => {
            if (data.valid) {
                activePromo = data;
                if (okEl) { okEl.textContent = '✓ ' + data.message; okEl.style.display = 'inline'; }
                applyDiscount(data);
                showToast('Code ' + code + ' appliqué — ' + data.label);
            } else {
                if (errEl) {
                    errEl.textContent = '✗ ' + (data.message || 'Code invalide.');
                    errEl.style.display = 'inline';
                }
                setTimeout(() => { if (errEl) errEl.style.display = 'none'; }, 2500);
            }
        })
        .catch(() => showToast('Erreur lors de la vérification', 'err'));
}

function applyDiscount(promo) {
    /* Afficher les prix barrés + prix réduits sur tous les produits */
    document.querySelectorAll('.prod-price[data-base]').forEach(el => {
        var base = parseFloat(el.dataset.base);
        if (!base) return;

        var reduced = promo.type === 'percentage'
            ? Math.round(base * (1 - promo.discount / 100))
            : Math.max(0, base - promo.discount);

        el.classList.add('striked');
        var promoEl = el.nextElementSibling;
        if (promoEl && promoEl.classList.contains('prod-price-promo')) {
            promoEl.textContent = reduced.toLocaleString('fr-FR') + ' XOF';
            promoEl.style.display = 'inline';
        }
    });
}

/* ============================================================
   PANIER
   Toutes les opérations passent par /api/cart
============================================================ */
var cartItems = [];

/* Charger le panier depuis la BD */
function loadCart() {
    apiGet('/api/cart')
        .then(data => {
            cartItems = data.items || [];
            renderCart(cartItems, data.total || 0, data.amount || 0);
        })
        .catch(() => renderCartLocal());
}

/* Ajouter un produit */
function addToCartApi(productId, name, price) {
    apiPost('/api/cart/add', { product_id: productId })
        .then(data => {
            loadCart(); // Recharger depuis la BD
            showToast('✦ ' + name + ' ajouté au panier');
            /* Ouvrir le mini-panier */
            document.getElementById('cart-modal')?.classList.add('open');
        })
        .catch(() => {
            /* Fallback local si API indisponible */
            cartItems.push({ id: productId, name, price, quantity: 1 });
            renderCartLocal();
            showToast('✦ ' + name + ' ajouté au panier');
        });
}

/* Retirer un produit */
function removeFromCartApi(productId) {
    apiDelete('/api/cart/' + productId)
        .then(() => loadCart())
        .catch(() => {
            cartItems = cartItems.filter(i => i.id !== productId);
            renderCartLocal();
        });
}

/* Afficher le panier */
function renderCart(items, total, amount) {
    var badge    = document.getElementById('cart-badge');
    var itemsEl  = document.getElementById('cart-items');
    var totalEl  = document.getElementById('cart-total');
    var footerEl = document.getElementById('cart-footer');

    if (badge) badge.textContent = total || 0;
    if (!itemsEl) return;

    if (!items || items.length === 0) {
        itemsEl.innerHTML = '<div class="cart-empty">Votre panier est vide.</div>';
        if (footerEl) footerEl.style.display = 'none';
        return;
    }

    itemsEl.innerHTML = items.map(function(item) {
        var imgStyle = item.image
            ? 'background-image:url(/storage/' + item.image + ');background-size:cover;background-position:center'
            : '';

        /*
        | Si un code promo est actif → afficher prix barré + prix réduit
        | price_reduced est calculé côté serveur dans getCart()
        */
        var priceHtml = item.price_reduced
            ? '<span style="text-decoration:line-through;color:#8A8A8A;font-size:11px">'
                + item.price + '</span> '
                + '<span style="color:#16a34a;font-weight:700">' + item.price_reduced + '</span>'
            : item.name_display || item.price;

        return '<div class="cart-item">'
            + '<div class="cart-item-thumb" style="' + imgStyle + '"></div>'
            + '<div style="flex:1">'
            + '<div class="cart-item-name">' + (item.name || '') + '</div>'
            + '<div class="cart-item-price">' + priceHtml + '</div>'
            + '</div>'
            + '<button class="cart-item-remove" onclick="removeFromCartApi(' + item.id + ')" aria-label="Retirer">×</button>'
            + '</div>';
    }).join('');

    /* Afficher total avec remise si applicable */
    if (totalEl) {
        totalEl.textContent = amount
            ? parseInt(amount).toLocaleString('fr-FR') + ' XOF'
            : total + ' article(s)';
    }
    if (footerEl) footerEl.style.display = 'block';
}

function renderCartLocal() {
    renderCart(
        cartItems.map(i => ({ id: i.id, name: i.name, price: i.price, image: null })),
        cartItems.length,
        0
    );
}

/* Ouvrir / fermer le modal panier */
function toggleCart() {
    document.getElementById('cart-modal')?.classList.toggle('open');
}

/* Aller au checkout (paiement en ligne) */
function goToCheckout() {
    window.location.href = '/checkout/summary';
}

/* Finaliser via WhatsApp */
function checkoutWhatsApp() {
    apiGet('/api/cart').then(data => {
        var lines = (data.items || [])
            .map(i => `• ${i.name} (${i.price})`)
            .join('\n');
        var msg = `Bonjour KEKELI Wear ✦\n\nJe souhaite commander :\n${lines}\n\nMerci de confirmer la disponibilité.`;
        window.open('https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(msg), '_blank');
    }).catch(() => showToast('Erreur panier', 'err'));
}

/* ============================================================
   OUVRIR WHATSAPP pour un produit
============================================================ */
function openWhatsApp(productName) {
    var msg = `Bonjour KEKELI Wear ✦\n\nJe suis intéressée par : ${productName}\n\nPouvez-vous me donner plus d'informations ?`;
    window.open('https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(msg), '_blank');
}

/* ============================================================
   CARROUSEL COUPS DE CŒUR
============================================================ */
var favOffset = 0;

function scrollFavs(dir) {
    var track = document.getElementById('fav-track');
    if (!track) return;
    var cards   = track.querySelectorAll('.fav-card');
    if (!cards.length) return;
    var cardW   = cards[0].offsetWidth + 16;
    var visible = Math.floor(track.parentElement.offsetWidth / cardW);
    var maxOff  = -(cards.length - visible) * cardW;
    favOffset   = Math.max(maxOff, Math.min(0, favOffset + dir * (-cardW)));
    track.style.transform = `translateX(${favOffset}px)`;
}

/* ============================================================
   MORPHOLOGIE
   selectMorpho : onglet manuel
   detectMorphology : détection auto depuis les mesures
   sendMeasurements : sauvegarde BD + WhatsApp
============================================================ */
var currentMorphology = 'hourglass';

function selectMorpho(tab, type, advice) {
    document.querySelectorAll('.morpho-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    currentMorphology = type;

    var result = document.getElementById('morpho-result');
    var text   = document.getElementById('morpho-result-text');
    if (result) result.classList.add('show');
    if (text)   text.textContent = advice;
}

function detectMorphology() {
    var chest = parseFloat(document.getElementById('f-chest')?.value) || 0;
    var waist = parseFloat(document.getElementById('f-waist')?.value) || 0;
    var hips  = parseFloat(document.getElementById('f-hips')?.value)  || 0;
    var det   = document.getElementById('auto-detect');

    if (!det) return;

    if (!chest || !waist || !hips) {
        det.classList.add('show');
        det.innerHTML = '<strong>ℹ</strong> Renseignez tour de poitrine, taille et hanches pour la détection.';
        return;
    }

    /* Même algorithme que Measurement::detectMorphology() côté Laravel */
    var diff  = Math.abs(chest - hips);
    var ratio = hips > 0 ? waist / hips : 0;
    var morpho, advice, tabIndex;

    if (diff <= 5 && ratio <= 0.75) {
        morpho = 'Sablier ⌛';        tabIndex = 0;
        advice = 'Coupes cintrées et ceintures signature — Tenues Réinventées & Accessoires.';
    } else if (chest > hips + 5) {
        morpho = 'Triangle inversé 🔺'; tabIndex = 1;
        advice = 'Jupes évasées et bas imprimés wax — Tenues Réinventées.';
    } else if (hips > chest + 5) {
        morpho = 'Poire 🍐';           tabIndex = 2;
        advice = "Hauts à volants et détails d'épaule — Confections Maison.";
    } else if (ratio > 0.85) {
        morpho = 'Ronde / Pomme 🍎';   tabIndex = 3;
        advice = 'Robes fluides et tuniques kaftan — Confections Maison.';
    } else {
        morpho = 'Rectangle ▬';        tabIndex = 4;
        advice = "Ceintures Pichi'Pichi et Sur-Mesure pour créer des courbes.";
    }

    det.classList.add('show');
    det.innerHTML = `<strong>Morphologie détectée : ${morpho}</strong><br>${advice}`;

    /* Activer le bon onglet */
    var tabs = document.querySelectorAll('.morpho-tab');
    if (tabs[tabIndex]) tabs[tabIndex].click();
}

function sendMeasurements() {
    var name     = document.getElementById('f-name')?.value.trim();
    var whatsapp = document.getElementById('f-whatsapp')?.value.trim();

    if (!name || !whatsapp) {
        showToast('⚠ Veuillez renseigner votre nom et votre numéro WhatsApp.', 'err');
        return;
    }

    var payload = {
        full_name:    name,
        whatsapp:     whatsapp,
        morphology:   currentMorphology,
        back_size:    document.getElementById('f-back')?.value    || null,
        chest:        document.getElementById('f-chest')?.value   || null,
        waist:        document.getElementById('f-waist')?.value   || null,
        hips:         document.getElementById('f-hips')?.value    || null,
        height:       document.getElementById('f-height')?.value  || null,
        dress_length: document.getElementById('f-dress')?.value   || null,
        top_length:   document.getElementById('f-top')?.value     || null,
        skirt_length: document.getElementById('f-skirt')?.value   || null,
    };

    /* Sauvegarder en BD via Laravel puis ouvrir WhatsApp */
    apiPost('/api/measurements', payload)
        .then(data => {
            if (data.success) {
                var msg = `Bonjour KEKELI Wear ✦\n\n`
                    + `Nom : ${name}\n`
                    + `Morphologie : ${data.morphology || currentMorphology}\n\n`
                    + `Mes mensurations (cm) :\n`
                    + `• Dos : ${payload.back_size    || 'N/R'}\n`
                    + `• Poitrine : ${payload.chest   || 'N/R'}\n`
                    + `• Taille : ${payload.waist     || 'N/R'}\n`
                    + `• Hanches : ${payload.hips     || 'N/R'}\n`
                    + `• Hauteur : ${payload.height   || 'N/R'}\n`
                    + `• Robe : ${payload.dress_length|| 'N/R'}\n`
                    + `• Haut : ${payload.top_length  || 'N/R'}\n`
                    + `• Jupe/pant : ${payload.skirt_length || 'N/R'}\n\n`
                    + `J'aimerais passer commande. Merci !`;

                window.open('https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(msg), '_blank');
                showToast('✦ Mensurations enregistrées !');
            }
        })
        .catch(() => {
            /* Fallback WhatsApp direct si API down */
            var msg = `Bonjour KEKELI Wear ✦\n\nNom : ${name}\nMorphologie : ${currentMorphology}\n\nJ'aimerais passer commande.`;
            window.open('https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(msg), '_blank');
        });
}

/* ============================================================
   FORMULAIRE CONTACT — Envoi via WhatsApp
============================================================ */
function sendContact() {
    var name    = document.getElementById('c-name')?.value.trim();
    var email   = document.getElementById('c-email')?.value.trim();
    var message = document.getElementById('c-message')?.value.trim();

    if (!name || !message) {
        showToast('⚠ Nom et message requis.', 'err');
        return;
    }

    var msg = `Bonjour KEKELI Wear ✦\n\nNom : ${name}\nEmail : ${email || 'Non renseigné'}\n\nMessage :\n${message}`;
    window.open('https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(msg), '_blank');
}

/* ============================================================
   SOUMISSION AVIS CLIENT
   POST /reviews — sans connexion
   is_approved = false par défaut → modération admin
============================================================ */
var currentRating = 5;

function pickStar(val) {
    currentRating = val;
    document.getElementById('rv-rating').value = val;
    document.querySelectorAll('.star-pick').forEach((s, i) => {
        s.classList.toggle('active', i < val);
    });
}

function submitReview() {
    var name    = document.getElementById('rv-name')?.value.trim();
    var city    = document.getElementById('rv-city')?.value.trim();
    var rating  = document.getElementById('rv-rating')?.value;
    var content = document.getElementById('rv-content')?.value.trim();
    var fb      = document.getElementById('review-feedback');

    if (!name || !content) {
        showToast('⚠ Prénom et avis requis.', 'err');
        return;
    }

    apiPost('/reviews', { first_name: name, city, rating, content })
        .then(data => {
            if (data.success) {
                if (fb) {
                    fb.style.display = 'block';
                    fb.style.color   = '#4caf50';
                    fb.textContent   = '✓ ' + data.message;
                }
                // Vider le formulaire
                ['rv-name','rv-city','rv-content'].forEach(id => {
                    var el = document.getElementById(id);
                    if (el) el.value = '';
                });
                pickStar(5);
                showToast('✦ Merci pour votre avis !');
            }
        })
        .catch(() => showToast('Erreur lors de la soumission.', 'err'));
}

/* ============================================================
   STATS — refresh automatique toutes les 60 secondes
   Appel GET /api/stats → met à jour la barre stats + compteurs
============================================================ */
function fetchStats() {
    apiGet('/api/stats')
        .then(data => {
            var map = {
                'sb-total':  data.total_products,
                'sb-likes':  data.total_likes,
                'sb-views':  data.total_views,
                'sb-rating': data.avg_rating + ' / 5',
            };
            Object.keys(map).forEach(id => {
                var el = document.getElementById(id);
                if (el) el.textContent = map[id];
            });
            /* Compteurs par catégorie */
            if (data.per_category) {
                Object.keys(data.per_category).forEach(slug => {
                    var el = document.getElementById('cnt-' + slug);
                    if (el) el.textContent = data.per_category[slug];
                });
            }
        })
        .catch(() => {}); /* Silencieux si offline */
}

/* Premier refresh après 2s (laisser la page charger) puis toutes les 60s */
setTimeout(fetchStats, 2000);
setInterval(fetchStats, 60000);

/* ============================================================
   NAVBAR — transparent → blanc au scroll
   TOP BAR — disparaît après 80px de scroll
============================================================ */
(function () {
    var nav      = document.getElementById('main-nav');
    var topBar   = document.getElementById('top-bar');
    var topBarH  = topBar ? topBar.offsetHeight : 0;

    /* Décaler la nav sous le top bar au chargement */
    if (nav) nav.style.top = topBarH + 'px';

    window.addEventListener('scroll', function () {
        var y = window.scrollY;

        /* Top bar disparaît après 80px */
        if (topBar) {
            if (y > 80) {
                topBar.classList.add('hidden');
                if (nav) nav.style.top = '0px';
            } else {
                topBar.classList.remove('hidden');
                if (nav) nav.style.top = topBarH + 'px';
            }
        }

        /* Nav devient blanche après 60px */
        if (nav) nav.classList.toggle('scrolled', y > 60);

        /* Scroll spy sections */
        var sections = ['hero','advantages','about','catalogue','featured','shop','morphology','reviews','contact'];
        sections.forEach(function (id) {
            var el = document.getElementById(id);
            if (!el) return;
            if (el.offsetTop <= y + 80 && el.offsetTop + el.offsetHeight > y + 80) {
                document.querySelectorAll('.nav-link').forEach(function (a) {
                    a.classList.toggle('active', a.getAttribute('href') === '#' + id);
                });
            }
        });
    }, { passive: true });
})();

/* ============================================================
   MENU MOBILE
============================================================ */
function closeMobileMenu() {
    document.getElementById('mobile-menu')?.classList.remove('open');
}

(function () {
    var toggle = document.getElementById('nav-toggle');
    var menu   = document.getElementById('mobile-menu');
    var close  = document.getElementById('mobile-menu-close');

    if (toggle && menu) {
        toggle.addEventListener('click', function () {
            menu.classList.toggle('open');
        });
    }
    if (close) {
        close.addEventListener('click', closeMobileMenu);
    }
})();

/* ============================================================
   SECTION AVANTAGES — révélation au scroll
============================================================ */
(function () {
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.reveal').forEach(function (el) {
        observer.observe(el);
    });
})();

/* ============================================================
   AVIS — CARROUSEL
============================================================ */
(function () {
    var carousel = document.querySelector('.temo-carousel');
    if (!carousel) return;

    var viewport = carousel.querySelector('.temo-viewport');
    var track    = carousel.querySelector('.temo-track');
    var cards    = carousel.querySelectorAll('.temo-card');
    var dotsWrap = carousel.querySelector('.temo-dots');
    var previous = carousel.querySelector('[data-review-prev]');
    var next     = carousel.querySelector('[data-review-next]');
    var current  = 0;
    var timer;

    function visibleCards() {
        return Math.max(1, Math.round(viewport.offsetWidth / cards[0].offsetWidth));
    }

    function maxPosition() {
        return Math.max(0, cards.length - visibleCards());
    }

    function moveTo(position) {
        current = Math.min(Math.max(position, 0), maxPosition());
        var distance = cards[0].getBoundingClientRect().width + 24;
        track.style.transform = 'translateX(-' + (current * distance) + 'px)';
        carousel.querySelectorAll('.temo-dot').forEach(function (dot, index) {
            dot.classList.toggle('active', index === current);
            dot.setAttribute('aria-current', index === current ? 'true' : 'false');
        });
    }

    function buildDots() {
        if (!dotsWrap) return;
        dotsWrap.innerHTML = '';
        for (var index = 0; index <= maxPosition(); index++) {
            var dot = document.createElement('button');
            dot.className = 'temo-dot';
            dot.type = 'button';
            dot.setAttribute('aria-label', 'Afficher les avis ' + (index + 1));
            dot.addEventListener('click', function () { moveTo(Number(this.dataset.position)); });
            dot.dataset.position = index;
            dotsWrap.appendChild(dot);
        }
    }

    function restartTimer() {
        clearInterval(timer);
        if (cards.length > visibleCards()) {
            timer = setInterval(function () {
                moveTo(current >= maxPosition() ? 0 : current + 1);
            }, 5000);
        }
    }

    buildDots();
    moveTo(0);
    restartTimer();
    previous?.addEventListener('click', function () { moveTo(current - 1); restartTimer(); });
    next?.addEventListener('click', function () { moveTo(current + 1); restartTimer(); });
    carousel.addEventListener('mouseenter', function () { clearInterval(timer); });
    carousel.addEventListener('mouseleave', restartTimer);
    window.addEventListener('resize', function () { buildDots(); moveTo(current); restartTimer(); });
})();

/* ============================================================
   AVIS — TOGGLE FORMULAIRE
============================================================ */
function toggleReviewForm() {
    var wrap = document.getElementById('review-form-wrap');
    var btn  = document.getElementById('btn-review-toggle');
    if (!wrap) return;

    var isOpen = wrap.classList.toggle('open');
    if (btn) {
        btn.textContent = isOpen ? '✕ Fermer' : '✦ Laisser mon avis';
    }
    if (isOpen) {
        /* Scroll doux vers le formulaire */
        setTimeout(function () {
            wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }
}
