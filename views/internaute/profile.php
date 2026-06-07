<?php
/**
 * VUE : Profil de l'utilisateur connecté
 * 
 * Cette vue affiche :
 * - Les informations personnelles de l'utilisateur (photo, nom, email, permis)
 * - Ses voyages proposés (si conducteur)
 * - Ses réservations
 * 
 * Variables reçues du contrôleur :
 * - $user : Objet Internaute (l'utilisateur connecté)
 * - $voyagesProposes : Tableau d'objets Voyage (voyages que l'utilisateur propose)
 * - $reservations : Tableau d'objets Reservation (réservations de l'utilisateur)
 */

// Définir le titre de la page (affiché dans l'onglet du navigateur)
$this->title = 'Mon profil';
?>

<!-- Container principal avec marge en haut pour éviter le header fixe -->
<div class="container mt-5" style="padding-top: 100px;">
    
    <!-- ========== CARTE PROFIL ========== -->
    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <!-- En-tête de la carte avec dégradé violet -->
        <div class="card-header text-white text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3 class="mb-0">👤 Mon profil</h3>
        </div>
        
        <!-- Corps de la carte : informations de l'utilisateur -->
        <div class="card-body p-4">
            <div class="row">
                
                <!-- COLONNE 1 : Photo de profil (3 colonnes sur 12) -->
                <div class="col-md-3 text-center">
                    
                    <!-- CONDITION : L'utilisateur a-t-il une photo ? -->
                    <?php if (!empty($user->photo)): ?>
                        <!-- SI OUI : Afficher la photo -->
                        <img src="<?= $user->photo ?>" 
                             class="rounded-circle" 
                             style="width: 150px; height: 150px; object-fit: cover;" 
                             alt="Photo">
                    <?php else: ?>
                        <!-- SI NON : Afficher un cercle avec la première lettre du pseudo -->
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px; font-size: 60px; color: white;">
                            <?php
                            // strtoupper() = mettre en MAJUSCULE
                            // substr($user->pseudo, 0, 1) = prendre le 1er caractère du pseudo
                            // Exemple : "Fourmi" → "F"
                            ?>
                            <?= strtoupper(substr($user->pseudo, 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- COLONNE 2 : Informations personnelles (9 colonnes sur 12) -->
                <div class="col-md-9">
                    <!-- Pseudo en grand et gras -->
                    <h4 class="fw-bold"><?= $user->pseudo ?></h4>
                    
                    <!-- Nom et prénom -->
                    <p><strong>Nom :</strong> <?= $user->nom ?> <?= $user->prenom ?></p>
                    
                    <!-- Email -->
                    <p><strong>Email :</strong> <?= $user->mail ?></p>
                    
                    <!-- CONDITION : L'utilisateur a-t-il un permis ? -->
                    <?php if (!empty($user->permis)): ?>
                        <!-- SI OUI : Badge vert avec le numéro -->
                        <p><strong>Permis :</strong> <span class="badge bg-success">✅ <?= $user->permis ?></span></p>
                    <?php else: ?>
                        <!-- SI NON : Badge gris "Aucun" -->
                        <p><strong>Permis :</strong> <span class="badge bg-secondary">❌ Aucun</span></p>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>

    <!-- ========== MES VOYAGES PROPOSÉS ========== -->
    <!-- CONDITION : L'utilisateur a-t-il proposé des voyages ? -->
    <?php if (!empty($voyagesProposes)): ?>
    <div class="card shadow border-0 rounded-4 mb-4">
        <!-- En-tête verte avec le nombre de voyages -->
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">🚗 Mes voyages proposés (<?= count($voyagesProposes) ?>)</h5>
        </div>
        
        <div class="card-body">
            <!-- Tableau listant tous les voyages proposés -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Trajet</th>
                        <th>Heure</th>
                        <th>Places</th>
                        <th>Tarif</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BOUCLE : Pour chaque voyage proposé -->
                    <?php foreach ($voyagesProposes as $v): ?>
                    <tr>
                        <!-- Trajet : récupérer depart et arrivee via la relation trajet0 -->
                        <!-- ?? '?' = si null, afficher '?' -->
                        <td><?= $v->trajet0->depart ?? '?' ?> → <?= $v->trajet0->arrivee ?? '?' ?></td>
                        
                        <!-- Heure de départ -->
                        <td><?= $v->heuredepart ?></td>
                        
                        <!-- Nombre de places disponibles -->
                        <td><?= $v->nbplacedispo ?></td>
                        
                        <!-- Tarif par km par personne -->
                        <td><?= $v->tarif ?> €/km</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- ========== MES RÉSERVATIONS ========== -->
    <!-- CONDITION : L'utilisateur a-t-il fait des réservations ? -->
    <?php if (!empty($reservations)): ?>
    <div class="card shadow border-0 rounded-4 mb-4">
        <!-- En-tête bleue avec le nombre de réservations -->
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">🎟️ Mes réservations (<?= count($reservations) ?>)</h5>
        </div>
        
        <div class="card-body">
            <!-- Tableau listant toutes les réservations -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Trajet</th>
                        <th>Places réservées</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BOUCLE : Pour chaque réservation -->
                    <?php foreach ($reservations as $r): ?>
                    <tr>
                        <!-- Trajet du voyage réservé -->
                        <!-- $r->leVoyage = relation vers le voyage (définie dans Reservation.php) -->
                        <!-- ->trajet0 = relation vers le trajet (définie dans Voyage.php) -->
                        <!-- Chaîne de relations : Reservation → Voyage → Trajet -->
                        <td><?= $r->leVoyage->trajet0->depart ?? '?' ?> → <?= $r->leVoyage->trajet0->arrivee ?? '?' ?></td>
                        
                        <!-- Nombre de places réservées -->
                        <td><?= $r->nbplaceresa ?></td>
                        
                        <!-- Statut de la réservation (toujours confirmée ici) -->
                        <td><span class="badge bg-success">Confirmée</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- ========== ALERTE SI PAS DE PERMIS ========== -->
    <!-- CONDITION : L'utilisateur n'a-t-il PAS de permis ? -->
    <?php if (empty($user->permis)): ?>
        <!-- Afficher un message d'avertissement -->
        <div class="alert alert-warning mt-3" role="alert">
            <strong>⚠️ Attention !</strong> Vous devez enregistrer un numéro de permis pour pouvoir proposer des voyages.
            <br>
            <small>Contactez un administrateur ou mettez à jour votre profil.</small>
        </div>
    <?php endif; ?>

</div>
