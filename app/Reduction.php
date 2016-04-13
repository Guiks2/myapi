<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"nom", "date_debut", "date_fin", "pourcentage_reduction"},
 *   @SWG\Xml(name="Reduction"),
 *   @SWG\Property(format="int64", property="id_reduction", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default="Reduction 1"),
 *   @SWG\Property(format="date", property="date_debut", type="date", default="2016-01-01"),
 *   @SWG\Property(format="date", property="date_fin", type="date", default="2016-02-01"),
 *   @SWG\Property(format="int64", property="pourcentage_reduction", type="number", default=20),
 * )
 */
class Reduction extends Model
{
    public $primaryKey = "id_reduction";
    public $timestamps = false;
}
