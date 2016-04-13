<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *   required={"nom", "prenom", "date_naissance", "email", "cpostal", "ville", "pays"},
 *   @SWG\Xml(name="Personne"),
 *   @SWG\Property(format="int64", property="id_personne", type="number", default=1),
 *   @SWG\Property(format="string", property="nom", type="string", default="SOLEVIC"),
 *   @SWG\Property(format="string", property="prenom", type="string", default="alexis"),
 *   @SWG\Property(format="date", property="date_naissance", type="date", default="1997-01-05"),
 *   @SWG\Property(format="string", property="email", type="string", default="solevic.alexis@lycos.fr"),
 *   @SWG\Property(format="string", property="adresse", type="string", default="1 rue Ketanou"),
 *   @SWG\Property(format="string", property="cpostal", type="string", default="94170"),
 *   @SWG\Property(format="string", property="ville", type="string", default="LE PERREUX SUR MARNE"),
 *   @SWG\Property(format="string", property="pays", type="string", default="France"),
 * )
 */
class Personne extends Model
{
    public $primaryKey = "id_personne";
    public $timestamps = false;

    public function employes(){
        return $this->hasMany('App\Employe', 'id_personne');
    }

    public function membres(){
        return $this->hasMany('App\Membre', 'id_personne');
    }

    public function seancesOuvreur(){
        return $this->hasMany('App\Seance', 'id_personne_ouvreur');
    }

    public function seancesTechnicien(){
        return $this->hasMany('App\Seance', 'id_personne_technicien');
    }

    public function seancesMenage(){
        return $this->hasMany('App\Seance', 'id_personne_menage');
    }
}
