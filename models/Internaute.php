<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Classe Internaute
 * Représente la table "fredouil.internaute" de la base de données.
 * Chaque ligne de la table est automatiquement convertie en objet PHP
 * grâce à ActiveRecord (évite d'écrire des requêtes SQL manuellement).
 */
class Internaute extends ActiveRecord
{
    /**
     * Indique le nom exact de la table associée au modèle
     */
    public static function tableName()
    {
        return 'fredouil.internaute';
    }

    /**
     * Relation : un internaute peut être conducteur de plusieurs voyages
     * @return \yii\db\ActiveQuery
     */
    public function getVoyages()
    {
        // clé étrangère : conducteur (table voyage) → id (table internaute)
        return $this->hasMany(Voyage::class, ['conducteur' => 'id']);
    }

    /**
     * Relation : un internaute peut avoir plusieurs réservations
     * @return \yii\db\ActiveQuery
     */
    public function getReservations()
    {
        // clé étrangère : voyageur (table reservation) → id (table internaute)
        return $this->hasMany(Reservation::class, ['voyageur' => 'id']);
    }

    /**
     * Récupère un internaute à partir de son pseudo
     * @param string $pseudo
     * @return Internaute|null
     */
    public static function getUserByIdentifiant($pseudo)
    {
        // Recherche un internaute dont le pseudo correspond
        return self::findOne(['pseudo' => $pseudo]);
    }

    /**
     * Vérifie si l'internaute est conducteur
     * Un conducteur possède un permis non vide
     * @return bool
     */
    public function isConducteur()
    {
        return !empty($this->permis);
    }
}
