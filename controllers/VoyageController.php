<?php

// Déclaration du namespace (espace de noms)
namespace app\controllers;

// Import des classes nécessaires
use Yii;                          // Framework Yii
use app\models\Voyage;            // Modèle Voyage (table voyage)
use app\models\Trajet;            // Modèle Trajet (table trajet)
use app\models\TypeVehicule;      // Modèle TypeVehicule (table typevehicule)
use app\models\MarqueVehicule;    // Modèle MarqueVehicule (table marquevehicule)
use app\models\Internaute;        // Modèle Internaute (table internaute)
use yii\web\Controller;           // Classe parente des contrôleurs

/**
 * Contrôleur qui gère les voyages
 * 
 * Fonctionnalités :
 * - Afficher le formulaire pour proposer un voyage
 * - Créer un voyage en AJAX
 * - Vérifier que l'utilisateur a un permis de conduire
 */
class VoyageController extends Controller
{
    /**
     * MÉTHODE SPÉCIALE : beforeAction()
     * 
     * Cette méthode s'exécute AVANT chaque action du contrôleur.
     * 
     * Ici, on l'utilise pour désactiver la protection CSRF (Cross-Site Request Forgery)
     * uniquement pour l'action AJAX 'proposer-ajax'.
     * 
     * Pourquoi désactiver CSRF ?
     * - Yii protège automatiquement les formulaires avec un token CSRF
     * - En AJAX, ce token peut causer des erreurs "400 Bad Request"
     * - On le désactive donc pour cette action spécifique
     * 
     * @param $action L'action qui va être exécutée
     * @return bool true pour continuer, false pour bloquer
     */
    public function beforeAction($action)
    {
        // Si l'action appelée est 'proposer-ajax'
        if ($action->id === 'proposer-ajax') {
            // Désactiver la validation CSRF pour cette action
            $this->enableCsrfValidation = false;
        }
        
        // Appeler la méthode parente et continuer l'exécution
        return parent::beforeAction($action);
    }

    /**
     * ACTION : Afficher le formulaire "Proposer un voyage"
     * URL : index.php?r=voyage/create
     * 
     * Cette action :
     * 1. Vérifie que l'utilisateur est connecté
     * 2. Vérifie que l'utilisateur a un numéro de permis
     * 3. Récupère les données nécessaires pour les listes déroulantes
     * 4. Affiche le formulaire
     */
    public function actionCreate()
    {
        // VÉRIFICATION 1 : L'utilisateur est-il connecté ?
        // has('user_id') vérifie si la session contient une clé 'user_id'
        if (!Yii::$app->session->has('user_id')) {
            // Si NON connecté :
            // - Créer un message flash d'erreur (affiché une seule fois)
            Yii::$app->session->setFlash('error', 'Connectez-vous pour proposer un voyage.');
            // - Rediriger vers la page de connexion
            return $this->redirect(['internaute/connexion']);
        }

        // RÉCUPÉRATION : Obtenir l'ID de l'utilisateur connecté depuis la session
        $userId = Yii::$app->session->get('user_id');
        
        // RECHERCHE : Chercher l'utilisateur dans la base de données
        $user = Internaute::findOne($userId);

        // VÉRIFICATION 2 : L'utilisateur a-t-il un numéro de permis ?
        // empty() retourne true si la variable est vide, null, ou n'existe pas
        if (empty($user->permis)) {
            // Si PAS de permis :
            // - Créer un message flash explicatif
            Yii::$app->session->setFlash('error', 'Vous devez enregistrer un numéro de permis dans votre profil pour proposer un voyage.');
            // - Rediriger vers la page de profil
            return $this->redirect(['internaute/profile']);
        }
        
        // RÉCUPÉRATION DES DONNÉES pour les listes déroulantes du formulaire
        
        // Récupérer TOUS les trajets disponibles
        // find()->all() = SELECT * FROM trajet
        $trajets = Trajet::find()->all();
        
        // Récupérer TOUS les types de véhicules (Citadine, Berline, SUV, etc.)
        $typesVehicules = TypeVehicule::find()->all();
        
        // Récupérer TOUTES les marques de véhicules (Renault, Peugeot, etc.)
        $marquesVehicules = MarqueVehicule::find()->all();

        // AFFICHAGE : Envoyer les données à la vue 'create.php'
        // Ces données seront utilisées pour remplir les listes déroulantes
        return $this->render('create', [
            'trajets' => $trajets,                    // Tableau d'objets Trajet
            'typesVehicules' => $typesVehicules,      // Tableau d'objets TypeVehicule
            'marquesVehicules' => $marquesVehicules,  // Tableau d'objets MarqueVehicule
        ]);
    }

    /**
     * ACTION AJAX : Créer un voyage
     * URL : index.php?r=voyage/proposer-ajax
     * 
     * Cette action est appelée en AJAX depuis le formulaire.
     * Elle reçoit les données du formulaire, crée le voyage en base,
     * et renvoie un message de succès ou d'erreur en JSON.
     * 
     * Étapes :
     * 1. Vérifier que l'utilisateur est connecté
     * 2. Vérifier que l'utilisateur a un permis
     * 3. Récupérer les données du formulaire
     * 4. Créer un objet Voyage
     * 5. Enregistrer en base de données
     * 6. Renvoyer une réponse JSON
     */
    public function actionProposerAjax()
    {
        // FORMAT DE RÉPONSE : Dire à Yii de renvoyer du JSON
        // Toutes les valeurs retournées seront automatiquement converties en JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        // VÉRIFICATION 1 : L'utilisateur est-il connecté ?
        if (!Yii::$app->session->has('user_id')) {
            // Renvoyer une erreur en JSON
            return ['ok' => false, 'message' => 'Connectez-vous d\'abord.'];
        }

        // RÉCUPÉRATION : Obtenir l'ID de l'utilisateur connecté
        $userId = Yii::$app->session->get('user_id');
        
        // RECHERCHE : Récupérer l'utilisateur depuis la base
        $user = Internaute::findOne($userId);

        // VÉRIFICATION 2 : L'utilisateur a-t-il un permis ?
        // empty() vérifie si le champ 'permis' est vide ou null
        if (empty($user->permis)) {
            // Renvoyer une erreur en JSON
            return ['ok' => false, 'message' => 'Vous devez avoir un permis.'];
        }

        // CRÉATION : Créer un nouvel objet Voyage (vide au départ)
        $voyage = new Voyage();
        
        // REMPLISSAGE : Attribuer les valeurs récupérées du formulaire
        
        // Le conducteur = ID de l'utilisateur connecté
        $voyage->conducteur = $userId;
        
        // Récupérer les données POST envoyées par le formulaire AJAX
        // post('trajet') récupère la valeur du champ 'trajet' du formulaire
        $voyage->trajet = Yii::$app->request->post('trajet');
        $voyage->idtypev = Yii::$app->request->post('idtypev');
        $voyage->idmarquev = Yii::$app->request->post('idmarquev');
        $voyage->heuredepart = Yii::$app->request->post('heuredepart');
        $voyage->nbplacedispo = Yii::$app->request->post('nbplacedispo');
        $voyage->tarif = Yii::$app->request->post('tarif');
        
        // post('nbbagage', 0) = si le champ n'existe pas, utiliser 0 comme valeur par défaut
        $voyage->nbbagage = Yii::$app->request->post('nbbagage', 0);
        
        // post('contraintes', '') = si le champ est vide, utiliser '' (chaîne vide)
        $voyage->contraintes = Yii::$app->request->post('contraintes', '');

        // ENREGISTREMENT : Sauvegarder le voyage dans la base de données
        // save() exécute un INSERT SQL et retourne true si succès
        if ($voyage->save()) {
            // SUCCÈS : Renvoyer un message de confirmation en JSON
            return ['ok' => true, 'message' => 'Voyage publié !'];
        } else {
            // ÉCHEC : Renvoyer les erreurs de validation
            // $voyage->errors contient les raisons de l'échec (champs manquants, formats invalides, etc.)
            // json_encode() convertit le tableau PHP en chaîne JSON
            return ['ok' => false, 'message' => 'Erreur : ' . json_encode($voyage->errors)];
        }
    }
}
