<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"id_film", "id_salle", "id_personne_ouvreur", "id_personne_technicien", "id_personne_menage", "debut_seance", "fin_seance"},
 *   @SWG\Xml(name="Seance"),
 *   @SWG\Property(format="int64", property="id", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_film", type="number", default=112),
 *   @SWG\Property(format="int64", property="id_salle", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_personne_ouvreur", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_personne_technicien", type="number", default=2),
 *   @SWG\Property(format="int64", property="id_personne_menage", type="number", default=3),
 *   @SWG\Property(format="date", property="debut_seance", type="date", default="2016-04-04 10:25:00"),
 *   @SWG\Property(format="date", property="fin_seance", type="date", default="2016-04-04 12:05:00"),
 * )
 */
class Seance extends Model
{
    public $primaryKey = "id";
    public $timestamps = false;

    public function historiques(){
        return $this->hasMany('App\HistoriqueMembre', 'id_seance');
    }

    public function film() {
        return $this->belongsTo('App\Film', 'id_film');
    }

    public function salle() {
        return $this->belongsTo('App\Salle', 'id_salle');
    }

    public function personneOuvreur() {
        return $this->belongsTo('App\Personne', 'id_personne');
    }

    public function personneTechnicien() {
        return $this->belongsTo('App\Personne', 'id_personne');
    }
    
    public function personneMenage() {
        return $this->belongsTo('App\Personne', 'id_personne');
    }
}
