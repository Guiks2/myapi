<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"nom", "salaire", "cadre"},
 *   @SWG\Xml(name="Fonction"),
 *   @SWG\Property(format="int64", property="id_fonction", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default="gérant"),
 *   @SWG\Property(format="string", property="salaire", type="string", default="60000"),
 *   @SWG\Property(format="int64", property="cadre", type="tinyint", default=1),
 * )
 */
class Fonction extends Model
{
    public $primaryKey = "id_fonction";
    public $timestamps = false;

    public function employes(){
        return $this->hasMany('App\Employe', 'id_fonction');
    }

    public function personnes(){
        return $this->belongsToMany('App\Personne', 'employes', 'id_fonction', 'id_personne');
    }
}
