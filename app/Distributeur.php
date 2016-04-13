<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"nom", "telephone"},
 *   @SWG\Xml(name="Distributeur"),
 *   @SWG\Property(format="int64", property="id_distributeur", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default="gimages"),
 *   @SWG\Property(format="string", property="telephone", type="string", default="146952611"),
 *   @SWG\Property(format="string", property="adresse", type="string", default="55 rue du faubourg Saint-Honoré"),
 *   @SWG\Property(format="string", property="cpostal", type="string", default="75008"),
 *   @SWG\Property(format="string", property="ville", type="string", default="Paris"),
 *   @SWG\Property(format="string", property="pays", type="string", default="France"),
 * )
 */
class Distributeur extends Model
{
    public $primaryKey = "id_distributeur";
    public $timestamps = false;

    public function films(){
        return $this->hasMany('App\Film', 'id_distributeur');
    }
}