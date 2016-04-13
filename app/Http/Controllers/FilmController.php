<?php

namespace App\Http\Controllers;

use App\Film;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilmController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/film",
     *     summary="Get a film list",
     *     description="Use this method to return a listing of films.",
     *     operationId="listFilm",
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
        return $films;
    }

    /**
     * @SWG\Post(
     *     path="/film",
     *     summary="Create a film",
     *     description="Use this method to create a new film.",
     *     operationId="createFilm",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="Name of the film",
     *         in="formData",
     *         name="titre",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Resume of the film",
     *         in="formData",
     *         name="resum",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date début affiche",
     *         in="formData",
     *         name="date_debut_affiche",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Date fin affiche",
     *         in="formData",
     *         name="date_fin_affiche",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Durée en minutes",
     *         in="formData",
     *         name="duree_minutes",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Année de production",
     *         in="formData",
     *         name="annee_production",
     *         required=true,
     *         type="integer",
     *         maximum="4"
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Film created",
     *          @SWG\Schema(
     *               ref="#/definitions/Film",
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You don\'t have authorization for this content'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'titre' => 'required|unique:films|max:255',
                'resum' => 'required|max:255',
                'date_debut_affiche' => 'required|date|before:'.$request->date_fin_affiche,
                'date_fin_affiche' => 'required|date|after:'.$request->date_debut_affiche,
                'duree_minutes' => 'required|numeric',
                'annee_production' => 'required|digits:4'
            ]);

            if($validator->fails()){
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $film = new Film;
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
     *      summary="Display a single film",
     *      description="Use this method to return a single film attributes based on its id.",
     *      operationId="showFilm",
     *      tags={"film"},
     *      @SWG\Parameter(
     *          name="id_film",
     *          in="path", 
     *          type="integer",
     *          description="id of film to fetch",
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
     *           description="Film not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $film = Film::find($id);

        if(empty($film)){
            return response()->json(
                ['error' => 'This film does not exist'],
                404);
        }
        return $film;
    }


    /**
     * @SWG\Put(
     *     path="/film/{id_film}",
     *     summary="Update a film",
     *     description="Use this method to update the attributes of a film based on its id.",
     *     operationId="updateFilm",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="Name of the film",
     *         in="formData",
     *         name="titre",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Resume of the film",
     *         in="formData",
     *         name="resum",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date début affiche",
     *         in="formData",
     *         name="date_debut_affiche",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Date fin affiche",
     *         in="formData",
     *         name="date_fin_affiche",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Durée en minutes",
     *         in="formData",
     *         name="duree_minutes",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Année de production",
     *         in="formData",
     *         name="annee_production",
     *         required=true,
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
     *         response=422,
     *         description="Champs manquants obligatoires ou incorrects"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You don\'t have authorization for this content'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'titre' => 'required|unique:films|max:255',
                'resum' => 'required|max:255',
                'date_debut_affiche' => 'required|date|before:' . $request->date_fin_affiche,
                'date_fin_affiche' => 'required|date|after:' . $request->date_debut_affiche,
                'duree_minutes' => 'required|numeric',
                'annee_production' => 'required|digits:4'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $film = Film::find($id);
            $film->titre = $request->titre;
            $film->resum = $request->resum;
            $film->date_debut_affiche = $request->date_debut_affiche;
            $film->date_fin_affiche = $request->date_fin_affiche;
            $film->duree_minutes = $request->duree_minutes;
            $film->annee_production = $request->annee_production;
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
     *     description="Use this method to delete a film based on its id.",
     *     operationId="deleteFilm",
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="Film ID to delete",
     *         in="path",
     *         name="id_film",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="FIlm deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid film value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You don\'t have authorization for this content'],
                403);
        } else {
            $film = Film::find($id);

            if (empty($film)) {
                return response()->json(
                    ['error' => 'this film does not exist'],
                    404);
            }

            $film->delete();
        }
    }
}