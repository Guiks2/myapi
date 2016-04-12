<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  required={"titre"},
 *  @SWG\Xml(name="Film"),
 *  @SWG\Property(format="int64", property="id_film", type="number", default=42),
 *  @SWG\Property(format="int64", property="id_genre", type="number", default=0),
 *  @SWG\Property(format="int64", property="id_distributeur", type="number", default=1),
 *  @SWG\Property(format="string", property="titre", type="string", default="Pulp Fiction"),
 *  @SWG\Property(format="string", property="resum", type="string", default="Dans un café restaurant de Los Angeles, dans la matinée, un couple de jeunes braqueurs, Pumpkin et Yolanda, discutent des risques que comporte leur activité. Ils se décident finalement à attaquer le lieu, afin de pouvoir dévaliser à la fois l'établissement et les clients."),
 *  @SWG\Property(format="date", property="date_debut_affiche", type="date", default="1994-01-01"),
 *  @SWG\Property(format="date", property="date_fin_affiche", type="date", default="1994-04-01"),
 *  @SWG\Property(format="int64", property="duree_minutes", type="number", default=154),
 *  @SWG\Property(format="int64", property="annee_production", type="number", default=1994),
 * )
 */
class Film extends Model
{
    public $primaryKey = "id_film";
    public $timestamps = false;
}
