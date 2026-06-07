<?php
/** @var app\models\Voyage[] $voyages */
/** @var int $placesDemandes */
/** @var string|null $message */
?>



    <?php foreach ($voyages as $v): ?>
        <?php
        // Nombre de passagers demandés (sécurisé)
        $nbPassagers     = max(1, (int)$placesDemandes);

        // Capacité et places restantes (méthodes du modèle)
        $capacite        = $v->nbplacedispo;
        $placesRestantes = $v->getPlacesRestantes();

        // Distance et coût total
        $distance        = $v->trajet0->distance ?? 0;
        $coutTotalGroupe = $distance * $v->tarif * $nbPassagers;

        // Détermination du type de statut (pour couleur)
        if ($nbPassagers > $capacite) {
            $statutClasse = 'bg-danger text-white';  // rouge
            $statutTexte  = "Trajet existant mais le véhicule ne supporte pas $nbPassagers personnes.";
        } elseif ($placesRestantes < $nbPassagers) {
            $statutClasse = 'bg-warning text-dark';  // jaune
            $statutTexte  = "Trajet existant mais pas assez de places disponibles.";
        } else {
            $statutClasse = 'bg-success text-white'; // vert
            $statutTexte  = "Voyage DISPONIBLE — réservation possible.";
        }
        ?>


<!-- Bandeau de statut + Bouton (côte à côte) -->
<div class="d-flex justify-content-between align-items-center gap-2">
    <!-- Statut (vert/jaune/rouge) -->
    <div class="p-2 rounded flex-grow-1 <?= $statutClasse ?>">
        <?= $statutTexte ?>
    </div>
    
    <!-- BOUTON RÉSERVER (seulement si disponible) -->
    <?php if ($placesRestantes >= $nbPassagers && $statutClasse === 'bg-success text-white'): ?>
        <button class="btn btn-primary btn-reserver" 
                data-voyage-id="<?= $v->id ?>" 
                data-nb-places="<?= $nbPassagers ?>"
                style="white-space: nowrap;">
            Réserver
        </button>
    <?php endif; ?>
</div>



        <!-- Carte Bootstrap pour 1 voyage -->
        <div class="card mb-3 shadow-sm" style="border-left: 5px solid #84c5f4;">
            <!-- En‑tête bleu bébé -->
            <div class="card-header" style="background-color:#e0f3ff;">
                <strong>
                    <?= $v->trajet0->depart ?? '' ?>
                    →
                    <?= $v->trajet0->arrivee ?? '' ?>
                </strong>
                <span class="text-muted ms-2">
                    (<?= $distance ?> km)
                </span>
            </div>

            <div class="card-body">

                <!-- Ligne 1 : conducteur + véhicule -->
                <p class="mb-1">
                    <strong>Nom du conducteur :</strong>
                    <?= $v->conducteur0->nom ?? '' ?>
                </p>
                <p class="mb-1">
                    <strong>Type de véhicule :</strong>
                    <?= $v->typeVehicule->typev ?? '' ?>
                    —
                    <strong>Marque :</strong>
                    <?= $v->marqueVehicule->marquev ?? '' ?>
                </p>

                <!-- Ligne 2 : infos principales -->
                <p class="mb-1">
                    <strong>Tarif :</strong>
                    <?= $v->tarif ?> €/km/personne
                </p>
                <p class="mb-1">
                    <strong>Capacité du véhicule :</strong>
                    <?= $v->nbplacedispo ?> places
                    —
                    <strong>Places restantes :</strong>
                    <?= $placesRestantes ?>
                </p>
                <p class="mb-1">
                    <strong>Nombre de bagages :</strong>
                    <?= $v->nbbagage ?>
                </p>
                <p class="mb-1">
                    <strong>Heure de départ :</strong>
                    <?= $v->heuredepart ?> h
                </p>
                <p class="mb-2">
                    <strong>Contraintes :</strong>
                    <?= $v->contraintes ?>
                </p>

                <!-- Coût total -->
                <p class="mb-2">
                    <strong>Coût total pour <?= $nbPassagers ?> personne(s) :</strong>
                    <?= number_format($coutTotalGroupe, 2) ?> €
                </p>

               

            </div>
        </div>

    <?php endforeach; ?>

