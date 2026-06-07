<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Classe Reservation
 * Représente la table "fredouil.reservation" de la base de données.
 * Chaque réservation est automatiquement convertie en objet PHP
 * grâce à ActiveRecord.
 */
class Reservation extends ActiveRecord
{
    /**
     * Nom de la table associée au modèle
     */
    public static function tableName()
    {
        return 'fredouil.reservation';
    }

    /**
     * Relation : une réservation concerne un seul voyage
     * @return \yii\db\ActiveQuery
     */
    public function getLeVoyage()
    {
        // clé étrangère : voyage (table reservation) → id (table voyage)
        return $this->hasOne(Voyage::class, ['id' => 'voyage']);
    }

    /**
     * Relation : une réservation est faite par un seul internaute
     * @return \yii\db\ActiveQuery
     */
    public function getLeVoyageur()
    {
        // clé étrangère : voyageur (table reservation) → id (table internaute)
        return $this->hasOne(Internaute::class, ['id' => 'voyageur']);
    }

    /**
     * Récupère toutes les réservations d’un voyage donné
     * Méthode statique : pas besoin de créer un objet Reservation
     *
     * @param int $idVoyage
     * @return Reservation[]
     */
    public static function getReservationsByVoyageId($idVoyage)
    {
        return self::findAll(['voyage' => $idVoyage]);
    }
}
