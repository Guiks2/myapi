<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  required={"titre"},
 *  @SWG\Xml(name="Film"),
 *  @SWG\Property(format="int64", property="id_film", type="number", default=3451),
 *  @SWG\Property(format="int64", property="id_genre", type="number", default=17),
 *  @SWG\Property(format="int64", property="id_distributeur", type="number", default=70),
 *  @SWG\Property(format="string", property="titre", type="string", default="Hostel: Part II"),
 *  @SWG\Property(format="string", property="resum", type="string", default="Three young women are lured into a Slovakian hostel.  Once there, they are subjected to all kinds of torture and hell...  Can they escape?"),
 *  @SWG\Property(format="date", property="date_debut_affiche", type="date", default="2007-06-29"),
 *  @SWG\Property(format="date", property="date_fin_affiche", type="date", default="2007-07-20"),
 *  @SWG\Property(format="int64", property="duree_minutes", type="number", default=94),
 *  @SWG\Property(format="int64", property="annee_production", type="number", default=2007),
 * )
 */
class Film extends Model
{
    public $primaryKey = "id_film";
    public $timestamps = false;

    public function genre() {
        return $this->belongsTo('App\Genre', 'id_genre');
    }

    public function distributeur() {
        return $this->belongsTo('App\Distributeur', 'id_distributeur');
    }

    public function seances(){
        return $this->hasMany('App\Seance', 'id_film');
    }
}
