<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Classe MarqueVehicule
 * Représente la table "fredouil.marquevehicule" dans la base de données.
 * Chaque enregistrement de la table correspond à un objet MarqueVehicule.
 */
class MarqueVehicule extends ActiveRecord
{
    /**
     * Nom de la table associée au modèle
     */
    public static function tableName()
    {
        return 'fredouil.marquevehicule';
    }

    /**
     * Relation : une marque de véhicule peut être associée à plusieurs voyages
     * @return \yii\db\ActiveQuery
     */
    public function getVoyages()
    {
        // clé étrangère : idmarquev (table voyage) → id (table marquevehicule)
        return $this->hasMany(Voyage::class, ['idmarquev' => 'id']);
    }
}
