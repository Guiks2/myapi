<?php

namespace App\Http\Controllers;

use App\Distributeur;
use App\Genre;
use App\Film;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
    
class FilmController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/film",
     *     summary="Get a movie list",
     *     description="Use this method to return a listing of all movies.",
     *     operationId="indexFilm",
     *     tags={"film"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="The request didn't return any content.",
     *     ),
     * )
     */
    public function index()
    {
        $films = Film::all();

        if ($films->isEmpty()) {
            return response()->json("The request didn't return any content.", 204);
        }

        return $films;
    }

    /**
     * @SWG\Post(
     *     path="/film",
     *     summary="Create a film",
     *     description="Use this method to create a new movie.",
     *     operationId="storeFilm",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="Genre ID",
     *         in="formData",
     *         name="id_genre",
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Distributor ID",
     *         in="formData",
     *         name="id_distributeur",
     *         type="integer",
     *         format="int64"
     *     ),    
     *     @SWG\Parameter(
     *         description="Name of the movie",
     *         in="formData",
     *         name="titre",
     *         required=true,
     *         type="string",
     *         maximum="255",
     *         format="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Resume of the movie",
     *         in="formData",
     *         name="resum",
     *         type="string",
     *         maximum="255",
     *         format="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Starting date",
     *         in="formData",
     *         name="date_debut_affiche",
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Ending date",
     *         in="formData",
     *         name="date_fin_affiche",
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Duration",
     *         in="formData",
     *         name="duree_minutes",
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Production year",
     *         in="formData",
     *         name="annee_production",
     *         type="integer",
     *         maximum="4",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Film created",
     *          @SWG\Schema(
     *               ref="#/definitions/Film",
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=403,
     *          description="Permission required"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing or incorrect field"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->type != 1){
            return response()->json(
                ['error' => 'You\'re not allowed to access this service.'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'id_genre' => 'exists:genres|numeric',
                'id_distributeur' => 'exists:distributeurs|numeric',
                'titre' => 'required|unique:films|max:255',
                'resum' => 'max:255',
                'date_debut_affiche' => 'required|date|before:date_fin_affiche',
                'date_fin_affiche' => 'required|date|after:date_debut_affiche',
                'duree_minutes' => 'numeric',
                'annee_production' => 'digits:4'
            ]);

            if($validator->fails()){
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $film = new Film;
            $film->id_genre = $request->id_genre;
            $film->id_distributeur = $request->id_distributeur;
            $film->titre = $request->titre;
            $film->resum = $request->resum;
            $film->date_debut_affiche = $request->date_debut_affiche;
            $film->date_fin_affiche = $request->date_fin_affiche;
            $film->duree_minutes = $request->duree_minutes;
            $film->annee_production = $request->annee_production;
            $film->save();

            return response()->json(
                $film,
                201);
        }
    }

    /**
     * @SWG\Get(
     *      path="/film/{id_film}",
     *      summary="Display a single movie",
     *      description="Use this method to return a single movie attributes based on its id.",
     *      operationId="showFilm",
     *      tags={"film"},
     *      @SWG\Parameter(
     *          name="id_film",
     *          in="path", 
     *          type="integer",
     *          required=true,
     *          description="Movie ID",
     *          format="int64"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Film",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404, 
     *           description="Movie not found"
     *      ),
     * )
     */
    public function show($id)
    {
        $film = Film::find($id);

        if(empty($film)){
            return response()->json(
                ['error' => 'Movie not found'],
                404);
        }
        return $film;
    }

    /**
     * @SWG\Put(
     *     path="/film/{id_film}",
     *     summary="Update a movie",
     *     description="Use this method to update the attributes of a movie based on its id.",
     *     operationId="updateFilm",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"film"},
     *     @SWG\Parameter(
     *         name="id_film",
     *         in="path", 
     *         type="integer",
     *         required=true,
     *         description="Movie ID",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Genre ID",
     *         in="formData",
     *         name="id_genre",
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Distributor ID",
     *         in="formData",
     *         name="id_distributeur",
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the movie",
     *         in="formData",
     *         name="titre",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Resume of the movie",
     *         in="formData",
     *         name="resum",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Starting date",
     *         in="formData",
     *         name="date_debut_affiche",
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Ending date",
     *         in="formData",
     *         name="date_fin_affiche",
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Duration",
     *         in="formData",
     *         name="duree_minutes",
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Production year",
     *         in="formData",
     *         name="annee_production",
     *         type="integer",
     *         maximum="4"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Film updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Film",
     *         ),
     *     ),
     *     @SWG\Response(
     *           response=404, 
     *           description="Movie not found"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing or incorrect fields"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You\'re not allowed to access this service.'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'id_genre' => 'exists:genres|numeric',
                'id_distributeur' => 'exists:distributeurs|numeric',
                'titre' => 'unique:films|max:255',
                'resum' => 'max:255',
                'date_debut_affiche' => 'date|before:' . $request->date_fin_affiche,
                'date_fin_affiche' => 'date|after:' . $request->date_debut_affiche,
                'duree_minutes' => 'numeric',
                'annee_production' => 'digits:4'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $film = Film::find($id);

            if(empty($film)){
                return response()->json(
                    ['error' => 'Movie not found'],
                    404);
            }

            $film->id_genre = $request->id_genre != null ? $request->id_genre : $film->id_genre;
            $film->id_distributeur = $request->id_distributeur != null ? $request->id_distributeur : $film->id_distributeur;
            $film->titre = $request->titre != null ? $request->titre : $film->titre;
            $film->resum = $request->resum != null ? $request->resum : $film->resum;
            $film->date_debut_affiche = $request->date_debut_affiche != null ? $request->date_debut_affiche : $film->date_debut_affiche;
            $film->date_fin_affiche = $request->date_fin_affiche != null ? $request->date_fin_affiche : $film->date_fin_affiche;
            $film->duree_minutes = $request->duree_minutes != null ? $request->duree_minutes : $film->duree_minutes;
            $film->annee_production = $request->annee_production != null ? $request->annee_production : $film->annee_production;
            $film->save();

            return response()->json(
                $film,
                200);
        }    
    }

    /**
     * @SWG\Delete(
     *     path="/film/{id_film}",
     *     summary="Delete a film",
     *     description="Use this method to delete a movie based on its id.",
     *     operationId="destroyFilm",
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="Movie ID",
     *         in="path",
     *         name="id_film",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Movie deleted"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Permission required"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Movie not found"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1) {
            return response()->json(
                ['error' => 'You\'re not allowed to access this service.'],
                403);
        } else {
            
            $film = Film::find($id);

            if (empty($film)) {
                return response()->json(
                    ['error' => 'Movie not found'],
                    404);
            }

            $film->delete();

            return response()->json(
                'Successfully deleted',
                200);
        }
    }

    /**
     * @SWG\Get(
     *      path="/film/distributeur/{id_distributeur}",
     *      summary="Display movies by ditributor",
     *      description="Use this method to return a listing of movies based on distributors id.",
     *      operationId="listFilmsByDistributor",
     *      tags={"film"},
     *      @SWG\Parameter(
     *          name="id_distributeur",
     *          in="path",
     *          type="integer",
     *          required=true,
     *          description="Distributor ID",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
     *          ),
     *      ),
     *      @SWG\Response(
     *         response=204,
     *         description="The request didn't return any content.",
     *      ),
     *      @SWG\Response(
     *           response=404,
     *           description="Distributor not found"
     *      ),
     * )
     */
    public function listFilmsByDistributor($id)
    {
        $distributeur = Distributeur::find($id);
        if(empty($distributeur)){
            return response()->json(
                ['error' => 'Distributor not found'],
                404);
        }

        $films = Film::where('id_distributeur', $id)->get();
        if ($films->isEmpty()) {
            return response()->json("The request didn't return any content.", 204);
        }

        return $films;
    }

    /**
     * @SWG\Get(
     *      path="/film/genre/{id_genre}",
     *      summary="Display movies by genre",
     *      description="Use this method to return a listing of movies based on genre id.",
     *      operationId="listFilmsByGenre",
     *      tags={"film"},
     *      @SWG\Parameter(
     *          name="id_genre",
     *          in="path",
     *          type="integer",
     *          required=true,
     *          description="Genre ID",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=204,
     *          description="The request didn't return any content.",
     *      ),
     *      @SWG\Response(
     *           response=404,
     *           description="Genre not found"
     *      ),
     * )
     */
    public function listFilmsByGenre($id)
    {
        $genre = Genre::find($id);
        if(empty($genre)){
            return response()->json(
                ['error' => 'Genre not found'],
                404);
        }

        $films = Film::where('id_genre', $id)->get();
        if ($films->isEmpty()) {
            return response()->json("The request didn't return any content.", 204);
        }

        return $films;
    }
}