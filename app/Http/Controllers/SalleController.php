<?php

namespace App\Http\Controllers;

use App\Salle;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class SalleController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/salle",
     *     summary="Display a listing of rooms.",
     *     tags={"salle"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Salle")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $salles = Salle::all();
        return $salles;
    }

    /**
     * @SWG\Post(
     *     path="/salle",
     *     summary="Create a room",
     *     description="Use this method to create a room",
     *     operationId="createRoom",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"salle"},
     *     @SWG\Parameter(
     *         description="Nom de la salle",
     *         in="formData",
     *         name="nom_salle",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Nombre d'étages de la salle",
     *         in="formData",
     *         name="etage_salle",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Nombre de places",
     *         in="formData",
     *         name="places",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Room created",
     *         @SWG\Schema(
     *              ref="#/definitions/Genre",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champ manquant obligatoire ou incorrect",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_salle' => 'required|unique:salles|max:255',
            'etage_salle' => 'required|numeric',
            'places' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $salle = new Salle;
        $salle->nom_salle = $request->nom_salle;
        $salle->etage_salle = $request->etage_salle;
        $salle->places = $request->places;
        $salle->save();
        
        return response()->json(
            $salle,
            201);
    }

    /**
     * @SWG\Get(
     *      path="/salle/{id_salle}",
     *      summary="Display a single room",
     *      description="Use this method to return a single room attributes based on its id.",
     *      operationId="showRoom",
     *      tags={"salle"},
     *      @SWG\Parameter(
     *          name="id_salle",
     *          in="path", 
     *          type="integer",
     *          description="id of room to fetch",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Salle",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404, 
     *           description="Room not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $salle = Salle::find($id);

        if(empty($salle)){
            return response()->json(
                ['error' => 'This room does not exist'],
                404);
        }
        return $salle;
    }

    /**
     * @SWG\Put(
     *     path="/salle/{id_salle}",
     *     summary="Update a room",
     *     description="Use this method to update the attributes of a room",
     *     operationId="updateRoom",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"salle"},
     *      @SWG\Parameter(
     *         name="id_salle",
     *         in="path",
     *         type="integer",
     *         description="Room ID"
     *     ),
     *     @SWG\Parameter(
     *         description="Room's name",
     *         in="formData",
     *         name="nom_salle",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Rooms' floor number",
     *         in="formData",
     *         name="etage_salle",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Rooms' seats number",
     *         in="formData",
     *         name="places",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Room created",
     *         @SWG\Schema(
     *              ref="#/definitions/Salle",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champ manquant obligatoire ou incorrect",
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom_salle' => 'required|unique:salles|max:255',
            'etage_salle' => 'required|numeric',
            'places' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $salle = Salle::find($id);
        $salle->nom_salle = $request->nom_salle;
        $salle->etage_salle = $request->etage_salle;
        $salle->places = $request->places;
        $salle->save();

        return response()->json(
            $salle,
            200);
    }

    /**
     * @SWG\Delete(
     *     path="/salle/{id_salle}",
     *     summary="Delete a room",
     *     description="Use this method to delete a room based on its id.",
     *     operationId="deleteRoom",
     *     tags={"salle"},
     *     @SWG\Parameter(
     *         description="Room ID to delete",
     *         in="path",
     *         name="id_salle",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Room deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid room ID"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $salle = Salle::find($id);

        if(empty($salle)){
            return response()->json(
                ['error' => 'This room does not exist'],
                404);
        }

        $salle->delete();
    }
}
