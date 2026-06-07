<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Classe Trajet
 * Représente la table "fredouil.trajet" de la base de données.
 * Un trajet correspond à un départ et une arrivée.
 */
class Trajet extends ActiveRecord
{
    /**
     * Nom de la table associée au modèle
     */
    public static function tableName()
    {
        return 'fredouil.trajet';
    }

    /**
     * Relation : un trajet peut être associé à plusieurs voyages
     * @return \yii\db\ActiveQuery
     */
    public function getVoyages()
    {
        // clé étrangère : trajet (table voyage) → id (table trajet)
        return $this->hasMany(Voyage::class, ['trajet' => 'id']);
    }

    /**
     * Récupère un trajet à partir du départ et de l’arrivée
     *
     * @param string $depart
     * @param string $arrivee
     * @return Trajet|null
     */
    public static function getTrajet($depart, $arrivee)
    {
        return self::findOne([
            'depart' => $depart,
            'arrivee' => $arrivee
        ]);
    }
}
