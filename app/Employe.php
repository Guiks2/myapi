<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"id_personne", "id_fonction"},
 *   @SWG\Xml(name="Employe"),
 *   @SWG\Property(format="int64", property="id_employe", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_personne", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_fonction", type="number", default=6),
 * )
 */
class Employe extends Model
{
    public $primaryKey = "id_employe";
    public $timestamps = false;

    public function personne() {
        return $this->belongsTo('App\Personne', 'id_personne');
    }

    public function fonction() {
        return $this->belongsTo('App\Fonction', 'id_fonction');
    }
}
