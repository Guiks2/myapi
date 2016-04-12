<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required=("id_forfait", "debut"),
 *   @SWG\Xml(name="Abonnement"),
 *   @SWG\Property(format="int64", property="id_forfait", type="number", default=1),
 *   @SWG\Property(format="date", property="debut", type="date", default="1992-01-01"),
 * )
 */
class Abonnement extends Model
{
    public $primaryKey = "id_film";
    public $timestamps = false;
}
