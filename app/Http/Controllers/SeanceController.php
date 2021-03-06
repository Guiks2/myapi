<?php

namespace App\Http\Controllers;

use App\Seance;
use App\Film;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SeanceController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/seance",
     *     summary="Display a listing of seances.",
     *     tags={"seance"},
     *     operationId="indexSeance",
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="The request didn't return any content.",
     *     ),
     *  )
     */
    public function index()
    {
        $seances = Seance::all();

        if ($seances->isEmpty()) {
            return response()->json("The request didn't return any content.", 204);
        }

        return $seances;
    }

    /**
     * @SWG\Post(
     *     path="/seance",
     *     summary="Create a seance",
     *     description="Use this method to create a seance. Including a check of the avaibility of the timeslot.",
     *     operationId="storeSeance",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"seance"},
     *     @SWG\Parameter(
     *         description="Movie ID",
     *         in="formData",
     *         name="id_film",
     *         required=true,
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         description="Room ID",
     *         in="formData",
     *         name="id_salle",
     *         required=true,
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         description="Room ID",
     *         in="formData",
     *         name="id_personne_ouvreur",
     *         required=true,
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         description="Technician guy ID",
     *         in="formData",
     *         name="id_personne_technicien",
     *         required=true,
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         description="Cleaning guy ID",
     *         in="formData",
     *         name="id_personne_menage",
     *         required=true,
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         description="Starting time",
     *         in="formData",
     *         name="debut_seance",
     *         required=true,
     *         type="string",
     *         maximum="255" 
     *     ),
     *     @SWG\Parameter(
     *         description="Ending time",
     *         in="formData",
     *         name="fin_seance",
     *         required=true,
     *         type="string",
     *         maximum="255" 
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Seance created",
     *         @SWG\Schema(
     *              ref="#/definitions/Seance",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Timeslot chosen is not available for this room",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing or incorrect fields",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_film' => 'required|numeric|exists:films',
            'id_salle' => 'required|numeric|exists:salles',
            'id_personne_ouvreur' => 'required|numeric|exists:personnes,id_personne',
            'id_personne_technicien' => 'required|numeric|exists:personnes,id_personne',
            'id_personne_menage' => 'required|numeric|exists:personnes,id_personne',
            'debut_seance' => 'required|date|before:fin_seance',
            'fin_seance' => 'required|date|after:debut_seance'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $seance = new Seance;
        $seance->id_film = $request->id_film;
        $seance->id_salle = $request->id_salle;
        $seance->id_personne_ouvreur = $request->id_personne_ouvreur;
        $seance->id_personne_technicien = $request->id_personne_technicien;
        $seance->id_personne_menage = $request->id_personne_menage;
        $seance->debut_seance = $request->debut_seance;
        $seance->fin_seance = $request->fin_seance;

        $seances = Seance::where('id_salle', $seance->id_salle)
        ->where(function($query) use ($seance){
            $query->where('debut_seance', '>=', $seance->debut_seance)
                ->where('debut_seance', '<=', $seance->fin_seance);
        })
        ->orWhere(function($query) use ($seance){
            $query->where('fin_seance', '>=', $seance->debut_seance)
            ->where('fin_seance', '<=', $seance->fin_seance);
        })
        ->orWhere(function($query) use ($seance){
            $query->where('debut_seance', '<=', $seance->debut_seance)
                ->where('fin_seance', '>=', $seance->fin_seance);
        })
        ->get();

        if($seances->isEmpty()){
            $seance->save();

            return response()->json(
                $seance,
                201);

        } else {
            return response()->json(
                'Timeslot chosen is not available for this room',
            400);
        }
    }

    /**
     * @SWG\Get(
     *      path="/seance/{id_seance}",
     *      summary="Display a single seance",
     *      description="Use this method to return a single seance attributes based on its id.",
     *      operationId="showSeance",
     *      tags={"seance"},
     *      @SWG\Parameter(
     *          name="id_seance",
     *          in="path", 
     *          type="integer",
     *          required=true,
     *          description="Seance ID",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Seance",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404, 
     *           description="Seance not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $seance = Seance::find($id);

        if(empty($seance)){
            return response()->json(
                ['error' => 'This seance does not exist'],
                404);
        }

        return $seance;
    }

    /**
     * @SWG\Put(
     *     path="/seance/{id_seance}",
     *     summary="Update a seance",
     *     description="Use this method to update the attributes of a seance based on its id. Including a check of the avaibility of the timeslot.",
     *     operationId="updateSeance",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"seance"},
     *     @SWG\Parameter(
     *         description="Seance ID",
     *         in="path",
     *         name="id_seance",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Movie ID",
     *         in="formData",
     *         name="id_film",
     *         type="integer"
     *     ), 
     *     @SWG\Parameter(
     *         description="Room ID",
     *         in="formData",
     *         name="id_salle",
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         description="Opener ID",
     *         in="formData",
     *         name="id_personne_ouvreur",
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         description="Technician ID",
     *         in="formData",
     *         name="id_personne_technicien",
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         description="Cleaning ID",
     *         in="formData",
     *         name="id_personne_menage",
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         description="Starting time",
     *         in="formData",
     *         name="debut_seance",
     *         type="string",
     *         required=true,
     *         format="date",
     *         maximum="255" 
     *     ),
     *     @SWG\Parameter(
     *         description="Ending time",
     *         in="formData",
     *         name="fin_seance",
     *         type="string",
     *         required=true,
     *         format="date",
     *         maximum="255" 
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Seance updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Seance",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=404, 
     *         description="Seance not found"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing or incorrect fields"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_film' => 'numeric|exists:films',
            'id_salle' => 'numeric|exists:salles',
            'id_personne_ouvreur' => 'numeric|exists:personnes',
            'id_personne_technicien' => 'numeric|exists:personnes',
            'id_personne_menage' => 'numeric|exists:personnes',
            'debut_seance' => 'required|date|before:fin_seance',
            'fin_seance' => 'required|date|after:debut_seance'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $seance = Seance::find($id);
        if(empty($seance)){
            return response()->json(
                ['error' => 'Seance not found'],
                404);
        }
        $seance->id_film = $request->id_film != null ? $request->id_film : $seance->id_film;
        $seance->id_salle = $request->id_salle != null ? $request->id_salle : $seance->id_salle;
        $seance->id_personne_ouvreur = $request->id_personne_ouvreur != null ? $request->id_personne_ouvreur : $seance->id_personne_ouvreur;
        $seance->id_personne_technicien = $request->id_personne_technicien != null ? $request->id_personne_technicien : $seance->id_personne_technicien;
        $seance->id_personne_menage = $request->id_personne_menage != null ? $request->id_personne_menage : $seance->id_personne_menage;
        $seance->debut_seance = $request->debut_seance != null ? $request->debut_seance : $seance->debut_seance;
        $seance->fin_seance = $request->fin_seance != null ? $request->fin_seance : $seance->fin_seance;

        $seances = Seance::where('id_salle', $seance->id_salle)
        ->whereNotIn('id', [$seance->id])
        ->where(function($query) use ($seance){
            $query->where('debut_seance', '>=', $seance->debut_seance)
                ->where('debut_seance', '<=', $seance->fin_seance)
                ->orWhere(function($query) use ($seance){
                    $query->where('fin_seance', '>=', $seance->debut_seance)
                        ->where('fin_seance', '<=', $seance->fin_seance);
                })
                ->orWhere(function($query) use ($seance){
                    $query->where('debut_seance', '<=', $seance->debut_seance)
                        ->where('fin_seance', '>=', $seance->fin_seance);
                });
        })
        ->get();


        if($seances->isEmpty()){
            $seance->save();

            return response()->json(
                $seance,
                200);

        } else {
            return response()->json(
                'Timeslot chosen is not available for this room',
            400);  
        }
    }

     /**
     * @SWG\Delete(
     *     path="/seance/{id_seance}",
     *     summary="Delete a seance",
     *     description="Use this method to delete a seance based on its id.",
     *     operationId="destroySeance",
     *     tags={"seance"},
     *     @SWG\Parameter(
     *         description="Seance ID",
     *         in="path",
     *         name="id_seance",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Seance deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Seance not found"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You\'re not allowed to access this service.'],
                403);
        } else {

            $seance = Seance::find($id);

            if (empty($seance)) {
                return response()->json(
                    ['error' => 'Seance not found'],
                    404);
            }

            $seance->delete();
            return response()->json(
                'Successfully deleted',
                200);
        }
    }

    /**
     * @SWG\Get(
     *     path="/seance/film/{id_film}",
     *     summary="Display next seances by film id",
     *     description="Use this method to return a listing of next seances based on film id and dates. If no date, from today to the end of the day.",
     *     operationId="getSeancesByIdFilm",
     *     tags={"seance"},
     *     @SWG\Parameter(
     *         description="Film ID",
     *         in="path",
     *         name="id_film",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Starting time",
     *         in="query",
     *         name="date_debut",
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Ending time",
     *         in="query",
     *         name="date_fin",
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="Successful operation but there isn't seance with this film or dates",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Film not found"
     *     )
     *  )
     */
    public function getSeancesByIdFilm(Request $request, $id)
    {
        $film = Film::find($id);

        if (empty($film)) {
            return response()->json(
                ['error' => 'this film does not exist'],
                404);
        }

        if(empty($request->date_debut)){
            $date_debut = date('Y-m-d').' 00:00:00';
        } else {
            $date_debut = $request->date_debut;
        }
        if(empty($request->date_fin) || $date_debut > $request->date_fin){
            $date_fin = date('+1 day').' 00:00:00';
        } else {
            $date_fin = $request->date_fin;
        }

        $seances = Seance::where('id_film', $id)
            ->where('debut_seance', '>=', $date_debut)
            ->where('debut_seance', '<=', $date_fin)
            ->orderBy('id_film')
            ->get();

        if ($seances->isEmpty()) {
            return response()->json("No content", 204);
        }

        foreach($seances as $key => $seance){
            $seance->film;
            $seance->salle;
        }

        return $seances;
    }
}
