<?php

// Déclaration du namespace (espace de noms) pour organiser les classes
namespace app\controllers;

// Import des classes nécessaires
use Yii;                          // Framework Yii
use yii\web\Controller;           // Classe parente des contrôleurs
use app\models\Internaute;        // Modèle Internaute (table internaute)
use app\models\Voyage;            // Modèle Voyage (table voyage)
use app\models\Reservation;       // Modèle Reservation (table reservation)

/**
 * Contrôleur qui gère tout ce qui concerne les internautes (utilisateurs)
 * - Inscription
 * - Connexion
 * - Déconnexion
 * - Affichage du profil
 */
class InternauteController extends Controller
{
    /**
     * ACTION : Afficher la page d'inscription
     * URL : index.php?r=internaute/inscription
     * 
     * Cette action affiche simplement le formulaire HTML d'inscription.
     * Le traitement se fait en AJAX via actionInscriptionAjax()
     */
    public function actionInscription()
    {
        // render() = afficher la vue 'inscription.php' dans views/internaute/
        return $this->render('inscription');
    }

    /**
     * ACTION : Afficher la page de connexion
     * URL : index.php?r=internaute/connexion
     * 
     * Cette action affiche simplement le formulaire HTML de connexion.
     * Le traitement se fait en AJAX via actionConnexionAjax()
     */
    public function actionConnexion()
    {
        // render() = afficher la vue 'connexion.php' dans views/internaute/
        return $this->render('connexion');
    }

    /**
     * ACTION AJAX : Traiter l'inscription d'un nouvel utilisateur
     * URL : index.php?r=internaute/inscription-ajax
     * 
     * Cette action reçoit les données du formulaire en AJAX,
     * valide les données, crée l'utilisateur en base, et renvoie du JSON.
     */
    public function actionInscriptionAjax()
    {
        // Dire à Yii de renvoyer du JSON (pas du HTML)
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Récupérer les données POST envoyées par le formulaire
        // $data sera un tableau avec pseudo, mail, pass, nom, prenom, etc.
        $data = Yii::$app->request->post('Internaute', []);
        
        // Extraire et nettoyer les données importantes
        $pseudo = trim($data['pseudo'] ?? '');  // trim() enlève les espaces
        $mail = trim($data['mail'] ?? '');
        $pass = $data['pass'] ?? '';

        // VALIDATION : Vérifier que les champs obligatoires sont remplis
        if (!$pseudo || !$mail || !$pass) {
            // Renvoyer une erreur en JSON
            return ['ok' => false, 'message' => 'Champs obligatoires manquants'];
        }

        // VALIDATION : Vérifier que le pseudo n'existe pas déjà
        if (Internaute::find()->where(['pseudo' => $pseudo])->exists()) {
            return ['ok' => false, 'message' => 'Pseudo déjà utilisé'];
        }

        // CRÉATION : Créer un nouvel objet Internaute (vide au départ)
        $user = new Internaute();
        
        // Remplir les attributs de l'objet avec les données du formulaire
        $user->pseudo = $pseudo;
        $user->mail = $mail;
        $user->nom = trim($data['nom'] ?? '');
        $user->prenom = trim($data['prenom'] ?? '');
        $user->permis = trim($data['permis'] ?? '');
        $user->photo = trim($data['photo'] ?? '');
        $user->pass = sha1($pass);  // Hasher le mot de passe avec SHA1

        // ENREGISTREMENT : Sauvegarder dans la base de données
        // save(false) = enregistrer sans validation (on l'a déjà faite)
        if ($user->save(false)) {
            // Succès : renvoyer un message de confirmation
            return ['ok' => true, 'message' => 'Inscription réussie. Vous pouvez vous connecter.'];
        }

        // Échec : renvoyer une erreur
        return ['ok' => false, 'message' => 'Erreur lors de l\'inscription'];
    }

    /**
     * ACTION AJAX : Traiter la connexion d'un utilisateur
     * URL : index.php?r=internaute/connexion-ajax
     * 
     * Cette action vérifie les identifiants (pseudo + mot de passe),
     * et si corrects, crée une session pour garder l'utilisateur connecté.
     */
    public function actionConnexionAjax()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $pseudo = trim(Yii::$app->request->post('pseudo', ''));
    $pass = Yii::$app->request->post('pass', '');

    if (!$pseudo || !$pass) {
        return ['ok' => false, 'message' => 'Champs manquants'];
    }

    $user = Internaute::getUserByIdentifiant($pseudo);
    
    $passHash = sha1($pass);

    // Compatibilité: accepter l'ancien format en clair si présent
    if (!$user || ($user->pass !== $passHash && $user->pass !== $pass)) {
        return ['ok' => false, 'message' => 'Identifiants incorrects'];
    }

    // Si l'ancien format est encore en clair, migrer vers sha1
    if ($user && $user->pass === $pass) {
        $user->pass = $passHash;
        $user->save(false);
    }

        // CONNEXION RÉUSSIE : Créer une session pour garder l'utilisateur connecté
        // La session stocke l'ID et le pseudo de l'utilisateur
    Yii::$app->session->set('user_id', $user->id);
    Yii::$app->session->set('user_pseudo', $user->pseudo);

    return ['ok' => true, 'message' => 'Connexion réussie. Bienvenue ' . $user->pseudo];
}

    /**
     * ACTION : Déconnecter l'utilisateur
     * URL : index.php?r=internaute/deconnexion
     * 
     * Cette action supprime la session de l'utilisateur
     * et le redirige vers la page de recherche.
     */
    public function actionDeconnexion()
    {
        // SUPPRIMER la session (déconnexion)
        Yii::$app->session->remove('user_id');
        Yii::$app->session->remove('user_pseudo');
        
        // REDIRECTION : Envoyer l'utilisateur vers la page de recherche
        return $this->redirect(['recherche/index']);
    }

    /**
     * ACTION : Afficher le profil de l'utilisateur connecté
     * URL : index.php?r=internaute/profile
     * 
     * Cette action affiche :
     * - Les informations personnelles de l'utilisateur
     * - Ses voyages proposés (s'il est conducteur)
     * - Ses réservations
     */
    public function actionProfile()
    {
        // VÉRIFICATION : Est-ce que l'utilisateur est connecté ?
        if (!Yii::$app->session->has('user_id')) {
            // Si NON connecté → rediriger vers la page de connexion
            return $this->redirect(['internaute/connexion']);
        }
        
        // RÉCUPÉRATION : Obtenir l'ID de l'utilisateur depuis la session
        $userId = Yii::$app->session->get('user_id');
        
        // RECHERCHE : Chercher l'utilisateur dans la base de données
        $user = Internaute::findOne($userId);
        
        // VÉRIFICATION : L'utilisateur existe-t-il toujours en base ?
        if (!$user) {
            // Si l'utilisateur n'existe pas → message d'erreur + redirection
            Yii::$app->session->setFlash('error', 'Utilisateur introuvable.');
            return $this->redirect(['site/index']);
        }
        
        // RÉCUPÉRATION DES VOYAGES PROPOSÉS
        // Chercher tous les voyages où 'conducteur' = ID de l'utilisateur
        $voyagesProposes = Voyage::find()
            ->where(['conducteur' => $userId])
            ->all();
        
        // RÉCUPÉRATION DES RÉSERVATIONS
        // Chercher toutes les réservations où 'voyageur' = ID de l'utilisateur
        // with('leVoyage') = charger aussi les infos du voyage associé (jointure)
        $reservations = Reservation::find()
            ->where(['voyageur' => $userId])
            ->with('leVoyage')
            ->all();
        
        // AFFICHAGE : Envoyer les données à la vue 'profile.php'
        return $this->render('profile', [
            'user' => $user,                        // Objet Internaute
            'voyagesProposes' => $voyagesProposes,  // Tableau d'objets Voyage
            'reservations' => $reservations,        // Tableau d'objets Reservation
        ]);
    }
}
