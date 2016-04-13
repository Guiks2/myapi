<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"id_forfait", "debut"},
 *   @SWG\Xml(name="Abonnement"),
 *   @SWG\Property(format="int64", property="id_forfait", type="number", default=1),
 *   @SWG\Property(format="date", property="debut", type="date", default="2015-01-01"),
 * )
 */
class Abonnement extends Model
{
    public $primaryKey = "id_abonnement";
    public $timestamps = false;

    public function forfait() {
        return $this->belongsTo('App\Forfait', 'id_forfait');
    }

    public function membres(){
        return $this->hasMany('App\Membre', 'id_abonnement');
    }
}
