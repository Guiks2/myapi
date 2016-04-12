<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"nom", "prenom", "date_naissance", "email", "cpostal", "ville", "pays"},
 *   @SWG\Xml(name="Personne"),
 *   @SWG\Property(format="int64", property="id_personne", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default=""),
 *   @SWG\Property(format="string", property="prenom", type="string", default=""),
 *   @SWG\Property(format="date", property="date_naissance", type="date", default=""),
 *   @SWG\Property(format="string", property="email", type="string", default=""),
 *   @SWG\Property(format="string", property="adresse", type="string", default=""),
 *   @SWG\Property(format="string", property="cpostal", type="string", default=""),
 *   @SWG\Property(format="string", property="ville", type="string", default=""),
 *   @SWG\Property(format="string", property="pays", type="string", default=""),
 * )
 */
class Personne extends Model
{
    public $primaryKey = "id_personne";
    public $timestamps = false;
}
