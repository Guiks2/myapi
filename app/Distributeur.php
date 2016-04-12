<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required=("nom", "telephone"),
 *   @SWG\Xml(name="Distributeur"),
 *   @SWG\Property(format="int64", property="id_forfait", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default=""),
 *   @SWG\Property(format="string", property="telephone", type="string", default=""),
 *   @SWG\Property(format="string", property="adresse", type="string", default=""),
 *   @SWG\Property(format="string", property="cpostal", type="string", default=""),
 *   @SWG\Property(format="string", property="ville", type="string", default=""),
 *   @SWG\Property(format="string", property="pays", type="string", default=""),
 * )
 */
class Distributeur extends Model
{
    public $primaryKey = "id_distributeur";
    public $timestamps = false;
}