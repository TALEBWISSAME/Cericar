<?php

// Déclaration du namespace (espace de noms)
namespace app\controllers;

// Import des classes nécessaires
use Yii;                          // Framework Yii
use yii\web\Controller;           // Classe parente des contrôleurs
use app\models\Reservation;       // Modèle Reservation (table reservation)
use app\models\Voyage;            // Modèle Voyage (table voyage)

/**
 * Contrôleur qui gère les réservations de voyages
 * 
 * Fonctionnalités :
 * - Réserver un voyage en AJAX
 * - Vérifier les places disponibles
 * - Créer une réservation en base de données
 */
class ReservationController extends Controller
{
    /**
     * ACTION AJAX : Réserver un voyage
     * URL : index.php?r=reservation/reserver-ajax
     * 
     * Cette action permet à un utilisateur connecté de réserver
     * un certain nombre de places sur un voyage.
     * 
     * Étapes :
     * 1. Vérifier que c'est une requête POST
     * 2. Vérifier que l'utilisateur est connecté
     * 3. Récupérer les données (voyage_id, nb_places)
     * 4. Vérifier que le voyage existe
     * 5. Vérifier qu'il y a assez de places disponibles
     * 6. Créer la réservation en base de données
     * 7. Renvoyer un message de succès ou d'erreur en JSON
     */
    public function actionReserverAjax()
    {
        // VÉRIFICATION 1 : S'assurer que c'est une requête POST
        // Les données sensibles doivent être envoyées en POST (pas en GET)
        if (!Yii::$app->request->isPost) {
            return $this->asJson([
                'ok' => false,
                'message' => 'Méthode non autorisée.'
            ]);
        }

        // VÉRIFICATION 2 : L'utilisateur est-il connecté ?
        // On vérifie si la session contient 'user_id'
        if (!Yii::$app->session->has('user_id')) {
            return $this->asJson([
                'ok' => false,
                'message' => 'Vous devez être connecté pour réserver.',
                'redirect' => 'index.php?r=internaute/connexion' // URL de redirection
            ]);
        }

        // RÉCUPÉRATION : Obtenir l'ID de l'utilisateur connecté depuis la session
        $userId = Yii::$app->session->get('user_id');

        // RÉCUPÉRATION : Obtenir les données envoyées par le formulaire
        // voyage_id = ID du voyage à réserver
        // nb_places = Nombre de places à réserver
        $voyageId = Yii::$app->request->post('voyage_id');
        $nbPlaces = Yii::$app->request->post('nb_places');

        // VÉRIFICATION 3 : Les données obligatoires sont-elles présentes ?
        if (!$voyageId || !$nbPlaces) {
            return $this->asJson([
                'ok' => false,
                'message' => 'Données manquantes (voyage_id ou nb_places).'
            ]);
        }

        // RECHERCHE : Récupérer le voyage depuis la base de données
        // findOne($id) = chercher un enregistrement par son ID
        $voyage = Voyage::findOne($voyageId);
        
        // VÉRIFICATION 4 : Le voyage existe-t-il ?
        if (!$voyage) {
            return $this->asJson([
                'ok' => false,
                'message' => 'Voyage introuvable.'
            ]);
        }

        // VÉRIFICATION 5 : Y a-t-il assez de places disponibles ?
        // peutReserver($nb) est une méthode du modèle Voyage
        // Elle compare le nombre de places demandées avec les places restantes
        if (!$voyage->peutReserver($nbPlaces)) {
            return $this->asJson([
                'ok' => false,
                'message' => 'Pas assez de places disponibles. Il reste ' . $voyage->getPlacesRestantes() . ' place(s).'
            ]);
        }

        // CRÉATION : Créer un nouvel objet Reservation (vide au départ)
        $reservation = new Reservation();
        
        // REMPLISSAGE : Attribuer les valeurs aux attributs de l'objet
        $reservation->voyage = $voyageId;           // ID du voyage réservé
        $reservation->voyageur = $userId;           // ID de l'utilisateur qui réserve
        $reservation->nbplaceresa = $nbPlaces;      // Nombre de places réservées

        // ENREGISTREMENT : Sauvegarder la réservation dans la base de données
        // save() retourne true si succès, false si échec
        if ($reservation->save()) {
            // SUCCÈS : Renvoyer un message de confirmation en JSON
            return $this->asJson([
                'ok' => true,
                'message' => "Réservation effectuée avec succès pour {$nbPlaces} place(s) !"
            ]);
        } else {
            // ÉCHEC : Renvoyer les erreurs de validation
            // $reservation->errors contient les raisons de l'échec
            return $this->asJson([
                'ok' => false,
                'message' => 'Erreur lors de la réservation : ' . json_encode($reservation->errors)
            ]);
        }
    }
}
