<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"nom", "resum", "prix", "duree_jours"},
 *   @SWG\Xml(name="Forfait"),
 *   @SWG\Property(format="int64", property="id_forfait", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default="Forfait 1"),
 *   @SWG\Property(format="string", property="resum", type="string", default="Résumé du forfait 1"),
 *   @SWG\Property(format="int64", property="prix", type="number", default=15),
 *   @SWG\Property(format="int64", property="duree_jours", type="number", default=3),
 * )
 */
class Forfait extends Model
{
    public $primaryKey = "id_forfait";
    public $timestamps = false;

    public function abonnements(){
        return $this->hasMany('App\Abonnement', 'id_forfait');
    }
}
