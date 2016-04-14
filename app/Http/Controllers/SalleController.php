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
     *     operationId="indexSalle"
     *     tags={"salle"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Salle")
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
        $salles = Salle::all();

        if ($salles->isEmpty()) {
            return response()->json("The request didn't return any content.", 204);
        }

        return $salles;
    }

    /**
     * @SWG\Post(
     *     path="/salle",
     *     summary="Create a room",
     *     description="Use this method to create a room",
     *     operationId="storeSalle",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"salle"},
     *     @SWG\Parameter(
     *         description="Room number",
     *         in="formData",
     *         name="numero_salle",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the room",
     *         in="formData",
     *         name="nom_salle",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Floor number",
     *         in="formData",
     *         name="etage_salle",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Seats number",
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
     *         description="Missing or incorrect fields",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_salle' => 'required|unique:salles|numeric',
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
        $salle->numero_salle = $request->numero_salle;
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
     *      operationId="showSalle",
     *      tags={"salle"},
     *      @SWG\Parameter(
     *          name="id_salle",
     *          in="path", 
     *          type="integer",
     *          required=true,
     *          description="Room ID",
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
     *      ),
     * )
     */
    public function show($id)
    {
        $salle = Salle::find($id);

        if(empty($salle)){
            return response()->json(
                ['error' => 'Room not found'],
                404);
        }
        return $salle;
    }

    /**
     * @SWG\Put(
     *     path="/salle/{id_salle}",
     *     summary="Update a room",
     *     description="Use this method to update the attributes of a room",
     *     operationId="updateSalle",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"salle"},
     *      @SWG\Parameter(
     *         name="id_salle",
     *         in="path",
     *         type="string",
     *         required=true,
     *         description="Room ID"
     *     ),
     *     @SWG\Parameter(
     *         description="Room number",
     *         in="formData",
     *         name="numero_salle",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the room",
     *         in="formData",
     *         name="nom_salle",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Floor number",
     *         in="formData",
     *         name="etage_salle",
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Seats number",
     *         in="formData",
     *         name="places",
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
     *         description="Missing or incorrect fields",
     *     ),
     *     @SWG\Response(
     *         response=404, 
     *         description="Room not found"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'numero_salle' => 'unique:salles|numeric'
            'nom_salle' => 'unique:salles|max:255',
            'etage_salle' => 'numeric',
            'places' => 'numeric'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $salle = Salle::find($id);

        if(empty($salle)){
            return response()->json(
                ['error' => 'Room not found'],
                404);
        }

        $salle->numero_salle = $request->numero_salle != null ? $request->numero_salle : $salle->numero_salle;
        $salle->nom_salle = $request->nom_salle != null ? $request->nom_salle : $salle->nom_salle;
        $salle->etage_salle = $request->etage_salle != null ? $request->etage_salle : $salle->etage_salle;
        $salle->places = $request->places != null ? $request->places : $salle->places;
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
     *     operationId="destroySalle",
     *     tags={"salle"},
     *     @SWG\Parameter(
     *         description="Room ID",
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
     *         description="Room not found"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $salle = Salle::find($id);

        if(empty($salle)){
            return response()->json(
                ['error' => 'Room not found'],
                404);
        }

        $salle->delete();
    }
}
