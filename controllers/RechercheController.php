<?php

namespace app\controllers;
use yii\web\Response;   
use yii\web\Controller;
use app\models\Trajet;  //model qu on a besoin 
use app\models\Voyage;

class RechercheController extends Controller
{
    public function actionIndex() // appel 
    {
        // 1) Récupérer les valeurs envoyées par le formulaire (GET) pour pouvoir commence recherche 
        $request = \Yii::$app->request; // un variable local  qui stocke les info renvoyee par nav evite ecrire  Yii::$app->request
        $depart  = trim((string)$request->get('depart', ''));  // ville de départ
        $arrivee = trim((string)$request->get('arrivee', '')); // ville d'arrivée
        $nb      = max(1, (int)$request->get('nb', 1));        // nb de personnes


        // Tableau d'objet voyage 
        $voyages = [];

        // Message d'information à afficher dans la vue
        $message = null;

        // Indicateurs pour les messages
        $trajetExiste        = false;
        $existeMaisTropGrand = false;

        // 2) On lance la recherche seulement si départ et arrivée sont remplis
        if ($depart !== '' && $arrivee !== '') {

   
    // Vérifier si les villes existent dans au moins un trajet
    $departExiste  = Trajet::find()->where(['depart' => $depart])->exists();
    $arriveeExiste = Trajet::find()->where(['arrivee' => $arrivee])->exists();

    if (!$departExiste) {
        $message = "Ville de départ inconnue : $depart.";
    } elseif (!$arriveeExiste) {
        $message = "Ville d'arrivée inconnue : $arrivee.";
            } else {

        // 3) Chercher le trajet correspondant (ligne de la table trajet)
            $trajet = Trajet::findOne([
                'depart'  => $depart,      // valeur formulaire 
                'arrivee' => $arrivee,
            ]);

            if ($trajet) {
                $trajetExiste = true;

                // 4) Récupérer tous les voyages pour ce trajet
                $tousLesVoyages = Voyage::getVoyagesByTrajetId($trajet->id);
                
                // filtre la capacite max de vehicule : 
                foreach ($tousLesVoyages as $v) {
                    // 4.a) Si la voiture est trop petite pour le groupe → on ne l’affiche pas
                    if ($nb > $v->getPlacesMax()) {
                        $existeMaisTropGrand = true;
                        continue;
                    }

                    // 4.b) Sinon, on garde ce voyage pour l’affichage
                    $voyages[] = $v;
                }
 
                // 5) Construire un message simple pour l’étape 3 (quelle voyage garder et quelle msg affciher )
                if (empty($voyages) && $existeMaisTropGrand) {
                    $message = "Trajet trouvé, mais aucune voiture ne supporte $nb passager(s).";
                } elseif (empty($voyages)) {
                    $message = "Trajet trouvé, mais aucun voyage proposé pour le moment.";
                } else {
                    $message = count($voyages) . " voyage(s) trouvé(s).";
                }

            } else {
                // Aucun trajet entre ces deux villes
                $message = "Aucun trajet trouvé entre $depart et $arrivee.";
            }
        }
    }
          // ⬇⬇⬇ ICI on distingue requête normale et requête AJAX JSON
           // qaund appel vient de ajax:
        if ($request->isAjax) {
            // 1) On génère le HTML de la zone résultats uniquement
            //    -> par ex. une vue partielle 
            $html = $this->renderPartial('test', [
                'voyages'        => $voyages,
                'placesDemandes' => $nb,      // IMPORTANT : correspond à $placesDemandes
                'message'        => $message,
            ]);

            // 2) On prépare la réponse au format JSON
            \Yii::$app->response->format = Response::FORMAT_JSON; // [web:487][web:489]

            return [
                'html'    => $html,
                'message' => $message ?? 'Aucun critère de recherche renseigné.',
            ];
        }

        // 6) Requête normale (non AJAX) : on rend la page complète comme avant
        return $this->render('index', [
            'voyages' => $voyages,
            'nb'      => $nb,
            'depart'  => $depart,
            'arrivee' => $arrivee,
            'message' => $message,
        ]);
    }
}
