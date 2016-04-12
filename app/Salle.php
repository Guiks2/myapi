<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"numero_salle", "nom_salle", "etage_salle", "places"},
 *   @SWG\Xml(name="Salle"),
 *   @SWG\Property(format="int64", property="id_salle", type="number", default=1),
 *   @SWG\Property(format="int64", property="numero_salle", type="number", default=1),
 *   @SWG\Property(format="string", property="nom_salle", type="string", default=""),
 *   @SWG\Property(format="int64", property="etage_salle", type="number", default=1),
 *   @SWG\Property(format="int64", property="places", type="number", default=1),
 * )
 */
class Salle extends Model
{
    public $primaryKey = "id_salle";
    public $timestamps = false;
}
