<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"id_personne", "id_abonnement", "date_inscription", "debut_abonnement"},
 *   @SWG\Xml(name="Membre"),
 *   @SWG\Property(format="int64", property="id_membre", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_personne", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_abonnement", type="number", default=1),
 *   @SWG\Property(format="date", property="date_inscription", type="date", default="2015-08-17"),
 *   @SWG\Property(format="date", property="debut_abonnement", type="date", default="2015-09-01"),
 * )
 */
class Membre extends Model
{
    public $primaryKey = "id_membre";
    public $timestamps = false;

    public function historiques(){
        return $this->hasMany('App\HistoriqueMembre', 'id_membre');
    }

    public function personne() {
        return $this->belongsTo('App\Personne', 'id_personne');
    }

    public function abonnement() {
        return $this->belongsTo('App\Abonnement', 'id_abonnement');
    }
}
