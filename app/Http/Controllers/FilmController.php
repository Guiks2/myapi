<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Film;
use Illuminate\Support\Facades\Validator;

class FilmController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/film",
     *     summary="Display a listing of films.",
     *     tags={"film"},
     *     produces={"application/xml", "application/json"},
     *     @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film"),
     *          ),
     *     ),
     * )
     */
    public function index()
    {
        $films = Film::all();
        return $films;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'string|required|unique:films',
            'date_debut_affiche' => 'date_format:"Y-m-d"',
            'date_fin_affiche' => 'date_format:"Y-m-d"',
            'annee_production' => 'integer|min:1850',
            'duree_minutes' => 'integer',
            'id_distributeur' => 'integer|exists:distributeurs',
            'id_genre' => 'integer',
            'resum' => 'string',
        ]);

        if ($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422); // HTTP Status code
        }

        // Enregistre un nouvel élément
        $film = new Film();
        $film->titre = $request->titre;
        $film->date_debut_affiche = $request->date_debut_affiche;
        $film->date_fin_affiche = $request->date_fin_affiche;
        $film->annee_production = $request->annee_production;
        $film->duree_minutes = $request->duree_minutes;
        $film->id_distributeur = $request->id_distributeur;
        $film->id_genre = $request->id_genre;
        $film->resum = $request->resum;
        $film->save();
        return response()->json(
            ['id_film' => $film->id_film],
            201); // HTTP Status code
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $film = Film::find($id);
        
        if(empty($film)){
            return response()->json(
                ['error' => 'this film does not exist'], 404
            );
        }
        
        return $film;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $film = Film::find($id);
        if (empty($film)){
            return response()->json(
                ['error' => 'This film does not exist'],
                404); // HTTP Status code
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'string|unique:films',
            'date_debut_affiche' => 'date_format:"Y-m-d"',
            'date_fin_affiche' => 'date_format:"Y-m-d"',
            'annee_production' => 'integer|min:1850',
            'duree_minutes' => 'integer',
            'id_distributeur' => 'integer|exists:distributeurs',
            'id_genre' => 'integer',
            'resum' => 'string',
        ]);

        if ($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422); // HTTP Status code
        }

        $film->titre = $request->titre;
        $film->date_debut_affiche = $request->date_debut_affiche;
        $film->date_fin_affiche = $request->date_fin_affiche;
        $film->annee_production = $request->annee_production;
        $film->duree_minutes = $request->duree_minutes;
        $film->id_distributeur = $request->id_distributeur;
        $film->id_genre = $request->id_genre;
        $film->resum = $request->resum;
        $film->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $film = Film::find($id);
        if (empty($film)){
            return response()->json(
                ['error' => 'This film does not exist'],
                404); // HTTP Status code
        }
        $film->delete();
    }
}
