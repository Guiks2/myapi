<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(@SWG\Xml(name="Film"))
 */
class Film extends Model
{
    public $timestamps = false;

    /**
     * @var int
     * @SWG\Property(format="int64")
     */
    public $primaryKey = "id_film";
    /**
     * @var int
     * @SWG\Property(format="int64")
     */
    public $id_genre;
    /**
     * @var int
     * @SWG\Property(format="int64")
     */
    public $id_distributeur;
    /**
     * @var string
     * @SWG\Property(@SWG\Xml(name="titre",wrapped=true))
     */
    public $titre;

    /**
     * @var string
     * @SWG\Property(@SWG\Xml(name="resum",wrapped=true))
     */
    public $resum;
    /**
     * @var string
     * @SWG\Property(format="date")
     */
    public $date_debut_affiche;
    /**
     * @var string
     * @SWG\Property(format="date")
     */
    public $date_fin_affiche;
    /**
     * @var int
     * @SWG\Property()
     */
    public $duree_minutes;
    /**
     * @var int
     * @SWG\Property()
     */
    public $annee_production;
}
