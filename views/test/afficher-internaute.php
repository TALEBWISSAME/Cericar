<?php
?>

<div style="font-size: 18px; line-height: 1.6;">

    <!-- ===================== -->
    <!--   INFO INTERNAUTE     -->
    <!-- ===================== -->

    <div style="font-size: 22px; font-weight: bold; margin-bottom: 10px;"> Informations de l'internaute</div>
    <div style="margin-left: 10px;">

        <strong>Pseudo :</strong> <?= $user->pseudo ?><br> 
        <strong>Nom :</strong> <?= $user->nom ?><br>         <!-- objet de classe internaute -->
        <strong>Prénom :</strong> <?= $user->prenom ?><br>
        <strong>Mail :</strong> <?= $user->mail ?><br>
        <strong>Photo :</strong><br>
        <strong>Permis :</strong> <?= $user->permis ?><br>
        <img src="<?= $user->photo ?>"
          alt="Photo de profil"
            style="width:150px; height:150px; object-fit:cover; border-radius:10px; margin-top:5px;">
        <br>
        <strong>Permis :</strong> <?= $user->permis ?><br>
    </div>
    <hr>

    <!-- ===================== -->
    <!--   VOYAGES PROPOSÉS    -->
    <!-- ===================== -->
   <div style="font-size: 22px; font-weight: bold; margin: 15px 0;"> Voyages proposés par l'internaute</div>

    <?php if (empty($voyages)) : ?>
        <div style="margin-left: 10px;">Aucun voyage proposé.</div>

    <?php else : ?>
        <?php foreach ($voyages as $v): ?>     <!-- boucle -->

            <div style="margin-bottom: 25px;">
                <div style="font-size: 19px; font-weight: bold;"> Voyage #<?= $v->id ?></div>

                <?php if ($v->trajet0): ?>   <!-- objet trajet si le voyage  existe  -->
                    <div style="margin-left: 10px;">
                 <strong>Trajet :</strong><br>
                 <strong>id du trajet  :</strong> <?= $v->trajet0-> id  ?><br>
                 <strong>Départ :</strong> <?= $v->trajet0->depart ?><br>
                 <strong>Arrivée :</strong> <?= $v->trajet0->arrivee ?><br>
                 <strong>Distance :</strong> <?= $v->trajet0->distance ?> km<br>
                 <strong> contraintes </strong> <?= $v-> contraintes ?> <br>
                  </div>
                <?php else: ?>
                    <div style="margin-left: 10px; color: red;">
                        Trajet introuvable
                    </div>
                <?php endif; ?>

                <div style="margin-left: 10px; margin-top: 5px;">
                    <strong>Tarif :</strong> <?= $v->tarif ?> €/km<br>
                    <strong>Places disponibles :</strong> <?= $v->nbplacedispo ?><br>
                    <strong>Bagages maximum :</strong> <?= $v->nbbagage ?><br>
                    <strong>Heure de départ :</strong> <?= $v->heuredepart ?>h<br>
                    <strong>Marque véhicule :</strong> <?= $v->marqueVehicule->marquev ?><br>
                    <strong>Type véhicule :</strong> <?= $v->typeVehicule->typev ?><br>
                     </div>
                     <hr>
                     </div>
                 <?php endforeach; ?>
                 <?php endif; ?>

      <!-- ===================== -->
      <!--     RÉSERVATIONS      -->
      <!-- ===================== -->

   <div style="font-size: 22px; font-weight: bold; margin: 15px 0;"> Réservations effectuées</div>

   <?php if (empty($reservations)) : ?>
   <div style="margin-left: 10px;">Aucune réservation trouvée.</div>
 
   <?php else : ?>
    <?php foreach ($reservations as $r): ?>
        <div style="margin-bottom: 20px;">
            <div style="font-size: 19px; font-weight: bold;"> Réservation #<?= $r->id ?></div>
             <?php if ($r->leVoyage): ?>
                    <?php if ($r->leVoyage->trajet0): ?>
                      <strong>Voyage ID :</strong><?= $r->leVoyage->id ?><br>
                      <strong>Trajet :</strong>
                      <?= $r->leVoyage->trajet0->depart ?> 
                      <?= $r->leVoyage->trajet0->arrivee ?><br>
                      <strong>Distance :</strong><?= $r->leVoyage->trajet0->distance ?> km<br>
                      <strong>Places réservées :</strong> <?= $r->nbplaceresa ?><br>
                      <strong> marque de vehicule :</strong><?= $r->leVoyage->marqueVehicule-> marquev ?><br>

                    <?php else: ?>
                        <strong>Trajet :</strong> introuvable<br>
                    <?php endif; ?>
                </div>

                     <?php else: ?>
                       <div style="margin-left: 10px; color:red;"> Voyage introuvable (id = <?= $r->voyage ?>)</div>
                <?php endif; ?>
                <hr>
                </div>
          <?php endforeach; ?>
          <?php endif; ?>

