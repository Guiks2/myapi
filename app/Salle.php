<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"numero_salle", "nom_salle", "etage_salle", "places"},
 *   @SWG\Xml(name="Salle"),
 *   @SWG\Property(format="int64", property="id_salle", type="number", default=1),
 *   @SWG\Property(format="int64", property="numero_salle", type="number", default=1),
 *   @SWG\Property(format="string", property="nom_salle", type="string", default="Martin Scorsese"),
 *   @SWG\Property(format="int64", property="etage_salle", type="number", default=0),
 *   @SWG\Property(format="int64", property="places", type="number", default=135),
 * )
 */
class Salle extends Model
{
    public $primaryKey = "id_salle";
    public $timestamps = false;

    public function seances(){
        return $this->hasMany('App\Seance', 'id_salle');
    }
}
