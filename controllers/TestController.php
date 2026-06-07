<?php

namespace app\controllers; #import des fonctionnalite de controller yii 

use yii\web\Controller;
use app\models\Internaute;
use app\models\Voyage;
use app\models\Trajet;  #utiliser les donnes du model 
use app\models\Reservation;

class TestController extends Controller 
{
    /* ==========================================================
       1) Afficher un internaute + voyages + réservations (MVC)
    =========================================================== */

    public function actionAfficherInternaute($pseudo)  # fonction qui permet de changer le pseudo dans url  la valeur qu on mit 
    {
        $user = Internaute::getUserByIdentifiant($pseudo);
        if (!$user) {
            return $this->render('afficher-internaute', [
                'error' => "Aucun internaute trouvé avec le pseudo : $pseudo" # user est un objet de classe  internaute don on stock ls info pour chaque internaute 
            ]);
        }

        $voyages = $user->voyages;          // relation
        $reservations = $user->reservations;

        return $this->render('afficher-internaute', [
            'user' => $user,
            'voyages' => $voyages,
            'reservations' => $reservations
        ]);
    }
}