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
 *   @SWG\Property(format="date", property="date_inscription", type="date", default=""),
 *   @SWG\Property(format="date", property="debut_abonnement", type="date", default=""),
 * )
 */
class Membre extends Model
{
    public $primaryKey = "id_membre";
    public $timestamps = false;
}
