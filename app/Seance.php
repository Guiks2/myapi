<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"id_film", "id_salle", "id_personne_ouvreur", "id_personne_technicien", "id_personne_menage", "debut_seance", "fin_seance"},
 *   @SWG\Xml(name="Seance"),
 *   @SWG\Property(format="int64", property="id", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_film", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_salle", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_personne_ouvreur", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_personne_technicien", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_personne_menage", type="number", default=1),
 *   @SWG\Property(format="date", property="debut_seance", type="date", default=""),
 *   @SWG\Property(format="date", property="fin_seance", type="date", default=""),
 * )
 */
class Seance extends Model
{
    public $primaryKey = "id";
    public $timestamps = false;
}
