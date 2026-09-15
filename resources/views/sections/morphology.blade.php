{{-- ============================================================
     sections/morphology.blade.php
     Espace personnel :
     1. Sélection manuelle de la morphologie (5 profils)
     2. Formulaire de mensurations → API /api/measurements
     3. Détection automatique depuis les mesures saisies
     4. Envoi sur WhatsApp avec pré-remplissage du message
============================================================ --}}
<section id="morphology">
    <div class="container">

        <div class="sec-label">Espace personnel</div>
        <div class="sec-title" style="font-size:28px;color:#fff">Mon profil morphologique</div>
        <div class="divider"></div>
        <p class="sec-sub" style="margin-bottom:40px;color:#fff">
            Identifiez votre morphologie pour recevoir des conseils de style personnalisés,
            puis renseignez vos mesures pour commander en toute confiance.
        </p>

        <div class="morpho-grid">
            {{-- ===== COLONNE GAUCHE : Sélection morphologie ===== --}}
            <div>
                <div class="morpho-section-label" style="color:#fff">Sélectionnez votre morphologie</div>
                <div class="morpho-tabs">
                    {{-- Sablier --}}
                    <div class="morpho-tab active"
                         onclick="selectMorpho(this,'hourglass',
                         'Coupes cintrées et ceintures signature ✦ - Privilégiez les robes ajustées qui marquent la taille. Univers recommandé : Tenues Réinventées & Accessoires.')">
                        <div class="morpho-icon">⌛</div>
                        <div>
                            <div class="morpho-tab-name">Sablier</div>
                            <div class="morpho-tab-tip">Épaules et hanches équilibrées, taille marquée. Nos coupes cintrées et ceintures signature soulignent naturellement votre silhouette.</div>
                        </div>
                    </div>

                    {{-- Triangle inversé --}}
                    <div class="morpho-tab"
                         onclick="selectMorpho(this,'inverted-triangle',
                         'Jupes évasées et bas imprimés - Équilibrez vos épaules larges avec des jupes wax volumineuses. Univers recommandé : Tenues Réinventées.')">
                        <div class="morpho-icon">🔺</div>
                        <div>
                            <div class="morpho-tab-name">Triangle inversé</div>
                            <div class="morpho-tab-tip">Épaules plus larges que les hanches. Les jupes évasées et bas imprimés de nos Tenues Réinventées rééquilibrent la silhouette avec élégance.</div>
                        </div>
                    </div>

                    {{-- Poire --}}
                    <div class="morpho-tab"
                         onclick="selectMorpho(this,'pear',
                         'Hauts à volants et détails d\'épaule - Magnifiez le buste avec des bretelles et cols structurés. Univers recommandé : Confections Maison.')">
                        <div class="morpho-icon">🍐</div>
                        <div>
                            <div class="morpho-tab-name">Poire</div>
                            <div class="morpho-tab-tip">Hanches plus marquées que le buste. Les hauts à volants et détails d'épaule attirent le regard vers le haut avec grâce.</div>
                        </div>
                    </div>

                    {{-- Ronde --}}
                    <div class="morpho-tab"
                         onclick="selectMorpho(this,'round',
                         'Robes fluides et tuniques kaftan - Les tombés souples et lignes verticales subliment votre silhouette. Univers recommandé : Confections Maison.')">
                        <div class="morpho-icon">🍎</div>
                        <div>
                            <div class="morpho-tab-name">Ronde / Pomme</div>
                            <div class="morpho-tab-tip">Buste et taille arrondis. Les robes fluides et tuniques kaftan de nos Confections Maison offrent un confort chic et enveloppant.</div>
                        </div>
                    </div>

                    {{-- Rectangle --}}
                    <div class="morpho-tab"
                         onclick="selectMorpho(this,'rectangle',
                         'Ceintures Pichi\'Pichi et pièces Sur-Mesure - Créez des courbes avec des pièces structurées. Univers recommandé : Sur-Mesure & Accessoires.')">
                        <div class="morpho-icon">▬</div>
                        <div>
                            <div class="morpho-tab-name">Rectangle</div>
                            <div class="morpho-tab-tip">Silhouette longiligne et harmonieuse. Les ceintures Pichi'Pichi et pièces Sur-Mesure viennent structurer et sculpter la taille.</div>
                        </div>
                    </div>

                </div>{{-- /morpho-tabs --}}

                {{-- Conseil affiché après sélection --}}
                <div class="morpho-result" id="morpho-result">
                    <div class="morpho-result-title">✦ Conseil pour votre morphologie</div>
                    <div class="morpho-result-text" id="morpho-result-text"></div>
                </div>

            </div>{{-- /colonne gauche --}}

            {{-- ===== COLONNE DROITE : Formulaire mensurations ===== --}}
            <div class="form-mensuration">

                <div class="form-title">
                    📐 Mes mensurations
                    <span>(en cm)</span>
                </div>

                {{-- Infos client --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom complet</label>
                        <input class="form-input" id="f-name" type="text"
                               placeholder="Votre nom complet" autocomplete="name" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Numéro WhatsApp</label>
                        <input class="form-input" id="f-whatsapp" type="tel"
                               placeholder="+229 01..." autocomplete="tel" />
                    </div>
                </div>

                <div class="form-section-label">Mesures corporelles</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tour de dos</label>
                        <input class="form-input" id="f-back"    type="number" placeholder="ex: 38" min="20" max="200" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tour de poitrine</label>
                        <input class="form-input" id="f-chest"   type="number" placeholder="ex: 90" min="40" max="200" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tour de taille</label>
                        <input class="form-input" id="f-waist"   type="number" placeholder="ex: 72" min="30" max="200" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tour de hanches</label>
                        <input class="form-input" id="f-hips"    type="number" placeholder="ex: 98" min="50" max="250" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Hauteur (cm)</label>
                        <input class="form-input" id="f-height"  type="number" placeholder="ex: 165" min="100" max="230" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Longueur robe</label>
                        <input class="form-input" id="f-dress"   type="number" placeholder="ex: 110" min="30" max="200" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Longueur haut</label>
                        <input class="form-input" id="f-top"     type="number" placeholder="ex: 55" min="20" max="120" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Longueur jupe / pantalon</label>
                        <input class="form-input" id="f-skirt"   type="number" placeholder="ex: 90" min="20" max="150" />
                    </div>
                </div>

                {{-- Bouton détection automatique --}}
                <button class="btn-detect" onclick="detectMorphology()">
                    🔍 Détecter ma morphologie automatiquement
                </button>

                {{-- Résultat de la détection auto --}}
                <div class="morpho-detect" id="auto-detect"></div>

                {{-- Mention confidentialité --}}
                <p class="form-note">
                    🔒 Vos données sont transmises uniquement à KEKELI Wear
                    pour la préparation de votre commande.
                </p>

                {{-- Envoi sur WhatsApp — sauvegarde en BD + ouverture WA --}}
                <button class="btn-wa-send" onclick="sendMeasurements()">
                    📱 Envoyer mes mesures sur WhatsApp
                </button>

            </div>{{-- /form-mensuration --}}

        </div>{{-- /morpho-grid --}}
    </div>
</section>
