<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Classe TypeVehicule
 * Représente la table "fredouil.typevehicule" de la base de données.
 * Chaque type de véhicule peut être associé à plusieurs voyages.
 */
class TypeVehicule extends ActiveRecord
{
    /**
     * Nom de la table associée au modèle
     */
    public static function tableName()
    {
        return 'fredouil.typevehicule';
    }

    /**
     * Relation : un type de véhicule peut être associé à plusieurs voyages
     * @return \yii\db\ActiveQuery
     */
    public function getVoyages()
    {
        // clé étrangère : idtypev (table voyage) → id (table typevehicule)
        return $this->hasMany(Voyage::class, ['idtypev' => 'id']);
    }
}
