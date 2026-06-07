<?php

namespace app\models; // Appartient au domaine de l'application

use yii\db\ActiveRecord; // Permet de manipuler les tables SQL comme des objets PHP

/**
 * Classe Voyage
 * Représente la table "fredouil.voyage".
 * Gère les relations avec les internautes, trajets, véhicules et réservations,
 * ainsi que les règles métier (places, tarifs, validations).
 */
class Voyage extends ActiveRecord
{
    /**
     * Nom de la table associée au modèle
     */
    public static function tableName()
    {
        return 'fredouil.voyage';
    }

    /* ================= RELATIONS ================= */

    /**
     * Relation : un voyage a un conducteur
     * @return \yii\db\ActiveQuery
     */
    public function getConducteur0()
    {
        // clé étrangère : conducteur → id internaute
        return $this->hasOne(Internaute::class, ['id' => 'conducteur']);
    }

    /**
     * Relation : un voyage correspond à un trajet
     * @return \yii\db\ActiveQuery
     */
    public function getTrajet0()
    {
        // clé étrangère : trajet → id trajet
        return $this->hasOne(Trajet::class, ['id' => 'trajet']);
    }

    /**
     * Relation : type de véhicule utilisé
     * @return \yii\db\ActiveQuery
     */
    public function getTypeVehicule()
    {
        // clé étrangère : idtypev → id typevehicule
        return $this->hasOne(TypeVehicule::class, ['id' => 'idtypev']);
    }

    /**
     * Relation : marque du véhicule utilisé
     * @return \yii\db\ActiveQuery
     */
    public function getMarqueVehicule()
    {
        // clé étrangère : idmarquev → id marquevehicule
        return $this->hasOne(MarqueVehicule::class, ['id' => 'idmarquev']);
    }

    /**
     * Relation : un voyage peut avoir plusieurs réservations
     * @return \yii\db\ActiveQuery
     */
    public function getReservations()
    {
        return $this->hasMany(Reservation::class, ['voyage' => 'id']);
    }

    /**
     * Récupère tous les voyages associés à un trajet donné
     * @param int $idTrajet
     * @return Voyage[]
     */
    public static function getVoyagesByTrajetId($idTrajet)
    {
        return self::findAll(['trajet' => $idTrajet]);
    }

    /* ================= MÉTHODES MÉTIER : PLACES ================= */

    /**
     * Capacité maximale du véhicule
     * @return int
     */
    public function getPlacesMax()
    {
        return (int)$this->nbplacedispo;
    }

    /**
     * Nombre total de places déjà réservées
     * @return int
     */
    public function getPlacesReservees()
    {
        $total = 0;
        foreach ($this->reservations as $r) {
            $total += (int)$r->nbplaceresa;
        }
        return $total;
    }

    /**
     * Nombre de places restantes (jamais négatif)
     * @return int
     */
    public function getPlacesRestantes()
    {
        $reste = $this->getPlacesMax() - $this->getPlacesReservees();
        return ($reste < 0) ? 0 : $reste;
    }

    /**
     * Vérifie si le voyage est complet
     * @return bool
     */
    public function estComplet()
    {
        return $this->getPlacesRestantes() === 0;
    }

    /**
     * Vérifie si une réservation de nb places est possible
     * @param int $nb
     * @return bool
     */
    public function peutReserver($nb)
    {
        return $this->getPlacesRestantes() >= (int)$nb;
    }

    /* ================= MÉTHODES MÉTIER : TARIFS ================= */

    /**
     * Tarif total pour une personne sur tout le trajet
     * @return float
     */
    public function getTarifTotal()
    {
        // récupération de la distance du trajet (ou 0 si inexistante)
        $distance = $this->trajet0->distance ?? 0;
        return $this->tarif * $distance;
    }

    /**
     * Tarif total pour plusieurs personnes
     * @param int $nbPersonnes
     * @return float
     */
    public function getTarifTotalPour($nbPersonnes)
    {
        return $this->getTarifTotal() * (int)$nbPersonnes;
    }

    /* ================= VALIDATIONS ================= */

    /**
     * Règles de validation du modèle
     */
    public function rules()
    {
        return [
            // Champs obligatoires
            [['trajet', 'tarif', 'nbplacedispo', 'heuredepart'], 'required'],

            // Champs entiers
            [['conducteur', 'trajet', 'idtypev', 'idmarquev', 'nbplacedispo', 'nbbagage'], 'integer'],

            // Tarif : nombre positif
            [['tarif'], 'number', 'min' => 0],

            // Minimum 1 place disponible
            [['nbplacedispo'], 'integer', 'min' => 1],

            // Heure de départ
            [['heuredepart'], 'string'],

            // Contraintes : texte libre
            [['contraintes'], 'string'],
        ];
    }

    /**
     * Libellés des champs (affichage formulaire)
     */
    public function attributeLabels()
    {
        return [
            'trajet' => 'Trajet',
            'idtypev' => 'Type de véhicule',
            'idmarquev' => 'Marque du véhicule',
            'tarif' => 'Tarif (€/km/personne)',
            'nbplacedispo' => 'Nombre de places disponibles',
            'nbbagage' => 'Nombre de bagages autorisés',
            'heuredepart' => 'Heure de départ',
            'contraintes' => 'Contraintes (animaux, fumeur...)',
        ];
    }
}
