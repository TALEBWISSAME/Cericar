<?php
/**
 * VUE : Formulaire pour proposer un voyage
 * 
 * Cette vue affiche un formulaire permettant à un conducteur
 * de proposer un nouveau voyage.
 * 
 * Variables reçues du contrôleur :
 * - $trajets : Tableau d'objets Trajet (pour la liste déroulante)
 * - $typesVehicules : Tableau d'objets TypeVehicule (pour la liste déroulante)
 * - $marquesVehicules : Tableau d'objets MarqueVehicule (pour la liste déroulante)
 * 
 * Le formulaire est soumis en AJAX (pas de rechargement de page).
 */

// Définir le titre de la page (affiché dans l'onglet du navigateur)
$this->title = 'Proposer un voyage';
?>

<!-- Container principal avec marge en haut pour éviter le header fixe -->
<div class="container mt-5" style="padding-top: 100px;">
    <div class="row justify-content-center">
        <!-- Colonne centrée (8 colonnes sur 12) -->
        <div class="col-md-8">
            
            <!-- Carte contenant le formulaire -->
            <div class="card shadow-lg border-0 rounded-4">
                
                <!-- En-tête de la carte avec dégradé vert -->
                <div class="card-header text-center text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <h3 class="mb-0">🚗 Proposer un voyage</h3>
                </div>
                
                <!-- Corps de la carte : formulaire -->
                <div class="card-body p-4">
                    
                    <!-- Formulaire HTML avec ID pour JavaScript -->
                    <form id="proposer-form">
                        
                        <!-- ========== CHAMP 1 : Trajet ========== -->
                        <div class="mb-3">
                            <label class="form-label">Trajet *</label>
                            <!-- Liste déroulante (select) -->
                            <!-- name="trajet" = nom du champ envoyé au serveur -->
                            <!-- required = champ obligatoire (validation HTML5) -->
                            <select name="trajet" class="form-select" required>
                                <option value="">-- Choisir un trajet --</option>
                                
                                <!-- BOUCLE : Pour chaque trajet disponible -->
                                <?php foreach ($trajets as $t): ?>
                                    <!-- value = ID du trajet (envoyé au serveur) -->
                                    <!-- Texte affiché = Départ → Arrivée (Distance km) -->
                                    <option value="<?= $t->id ?>">
                                        <?= $t->depart ?> → <?= $t->arrivee ?> (<?= $t->distance ?> km)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- ========== CHAMP 2 : Type de véhicule ========== -->
                        <div class="mb-3">
                            <label class="form-label">Type de véhicule *</label>
                            <select name="idtypev" class="form-select" required>
                                <option value="">-- Choisir --</option>
                                
                                <!-- BOUCLE : Pour chaque type de véhicule (Citadine, SUV, etc.) -->
                                <?php foreach ($typesVehicules as $tv): ?>
                                    <option value="<?= $tv->id ?>">
                                        <?= $tv->typev ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- ========== CHAMP 3 : Marque du véhicule ========== -->
                        <div class="mb-3">
                            <label class="form-label">Marque du véhicule *</label>
                            <select name="idmarquev" class="form-select" required>
                                <option value="">-- Choisir --</option>
                                
                                <!-- BOUCLE : Pour chaque marque (Renault, Peugeot, etc.) -->
                                <?php foreach ($marquesVehicules as $mv): ?>
                                    <option value="<?= $mv->id ?>">
                                        <?= $mv->marquev ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- ========== CHAMP 4 : Heure de départ ========== -->
                        <div class="mb-3">
                            <label class="form-label">Heure de départ *</label>
                            <!-- type="time" = sélecteur d'heure HTML5 -->
                            <input type="time" name="heuredepart" class="form-control" required>
                        </div>

                        <!-- ========== CHAMP 5 : Nombre de places ========== -->
                        <div class="mb-3">
                            <label class="form-label">Nombre de places disponibles *</label>
                            <!-- type="number" = saisie de nombre -->
                            <!-- min="1" = minimum 1 place -->
                            <input type="number" name="nbplacedispo" class="form-control" min="1" required>
                        </div>

                        <!-- ========== CHAMP 6 : Tarif ========== -->
                        <div class="mb-3">
                            <label class="form-label">Tarif (€/km/personne) *</label>
                            <!-- step="0.01" = permet les décimales (ex: 0.50) -->
                            <!-- min="0.01" = minimum 1 centime -->
                            <input type="number" name="tarif" class="form-control" step="0.01" min="0.01" placeholder="Ex: 0.50" required>
                            <small class="text-muted">Exemple : 0.50 pour 50 centimes par km</small>
                        </div>

                        <!-- ========== CHAMP 7 : Nombre de bagages ========== -->
                        <div class="mb-3">
                            <label class="form-label">Nombre de bagages autorisés</label>
                            <!-- value="1" = valeur par défaut -->
                            <!-- Ce champ n'est PAS obligatoire (pas de 'required') -->
                            <input type="number" name="nbbagage" class="form-control" min="0" value="1">
                        </div>

                        <!-- ========== CHAMP 8 : Contraintes ========== -->
                        <div class="mb-3">
                            <label class="form-label">Contraintes</label>
                            <!-- textarea = champ de texte multi-lignes -->
                            <!-- rows="3" = hauteur de 3 lignes -->
                            <!-- placeholder = texte d'exemple grisé -->
                            <textarea name="contraintes" class="form-control" rows="3" placeholder="Ex: Non fumeur, animaux acceptés..."></textarea>
                        </div>

                        <!-- ========== BOUTON DE SOUMISSION ========== -->
                        <div class="text-center">
                            <!-- type="submit" = soumet le formulaire -->
                            <button type="submit" class="btn btn-lg btn-success px-5">
                                Publier le voyage
                            </button>
                        </div>
                        
                    </form>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Script AJAX déplacé dans web/js/voyage.js pour compatibilité AJAX -->
