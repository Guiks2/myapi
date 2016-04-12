<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"nom", "date_debut", "date_fin", "pourcentage_reduction"},
 *   @SWG\Xml(name="Reduction"),
 *   @SWG\Property(format="int64", property="id_reduction", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default=""),
 *   @SWG\Property(format="date", property="date_debut", type="date", default=""),
 *   @SWG\Property(format="date", property="date_fin", type="date", default=""),
 *   @SWG\Property(format="int64", property="pourcentage_reduction", type="number", default=1),
 * )
 */
class Reduction extends Model
{
    public $primaryKey = "id_reduction";
    public $timestamps = false;
}
