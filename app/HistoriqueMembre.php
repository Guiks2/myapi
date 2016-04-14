<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"id_membre", "id_seance", "date"},
 *   @SWG\Xml(name="HistoriqueMembre"),
 *   @SWG\Property(format="int64", property="id_historique", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_membre", type="number", default=1),
 *   @SWG\Property(format="int64", property="id_seance", type="number", default=1),
 *   @SWG\Property(format="date", property="date", type="date", default="2015-08-17"),
 * )
 */
class HistoriqueMembre extends Model
{
    public $primaryKey = "id_historique";
    public $table = "historique_membre";
    public $timestamps = false;

    public function membre() {
        return $this->belongsTo('App\Membre', 'id_membre');
    }

    public function seance() {
        return $this->belongsTo('App\Seance', 'id');
    }
}
