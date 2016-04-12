<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"nom", "salaire", "cadre"},
 *   @SWG\Xml(name="Fonction"),
 *   @SWG\Property(format="int64", property="id_fonction", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default=""),
 *   @SWG\Property(format="string", property="salaire", type="string", default=""),
 *   @SWG\Property(format="int64", property="cadre", type="tinyint", default=1),
 * )
 */
class Fonction extends Model
{
    public $primaryKey = "id_fonction";
    public $timestamps = false;
}
