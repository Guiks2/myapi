<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"nom"},
 *   @SWG\Xml(name="Genre"),
 *   @SWG\Property(format="int64", property="id_genre", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default=""),
 * )
 */
class Genre extends Model
{
    public $primaryKey = "id_genre";
    public $timestamps = false;

    public function films(){
        return $this->hasMany('App\Film', 'id_genre');
    }
}
