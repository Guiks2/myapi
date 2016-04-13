<?php

namespace App\Http\Controllers;

use App\Distributeur;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class DistributeurController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/distributeur",
     *     summary="Display a listing of distributors.",
     *     tags={"distributeur"},
     *     @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Distributeur")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $distributeurs = Distributeur::all();
        return $distributeurs;
    }


    /**
     * @SWG\Post(
     *     path="/distributeur",
     *     summary="Create a distributor",
     *     description="Use this method to create a distributor",
     *     operationId="createDistributor",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"distributeur"},
     *     @SWG\Parameter(
     *         description="Nom du distributeur",
     *         in="formData",
     *         name="nom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Adresse du distributeur",
     *         in="formData",
     *         name="adresse",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Code postal du distributeur",
     *         in="formData",
     *         name="cpostal",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Ville du distributeur",
     *         in="formData",
     *         name="ville",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Pays du distributeur",
     *         in="formData",
     *         name="pays",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Téléphone du distributeur",
     *         in="formData",
     *         name="telephone",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Distributor created",
     *         @SWG\Schema(
     *              ref="#/definitions/Distributeur",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:distributeurs|max:255',
            'telephone' => 'required|max:255'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $distributeur = new Distributeur;
        $distributeur->nom = $request->nom;
        $distributeur->adresse = $request->adresse;
        $distributeur->cpostal = $request->cpostal;
        $distributeur->ville = $request->ville;
        $distributeur->pays = $request->pays;
        $distributeur->telephone = $request->telephone;
        $distributeur->save();

        return response()->json(
            $distributeur,
            201);
    }

    /**
     * @SWG\Get(
     *      path="/distributeur/{id_distributeur}",
     *      summary="Display a single distributor",
     *      description="Use this method to return a single distributor attributes based on its id.",
     *      operationId="showDistributor",
     *      tags={"distributeur"},
     *      @SWG\Parameter(
     *          name="id_distributeur",
     *          in="path", 
     *          type="integer",
     *          description="id of distributeur to fetch",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Distributeur",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404, 
     *           description="Distributeur not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $distributeur = Distributeur::find($id);

        if(empty($distributeur)){
            return response()->json(
                ['error' => 'This distributeur does not exist'],
                404);
        }
        return $distributeur;
    }

    /**
     * @SWG\Put(
     *     path="/distributeur/{id_distributeur}",
     *     summary="Update a distributor",
     *     description="Use this method to update the attributes of a distributor based on its id.",
     *     operationId="updateDistributor",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"distributeur"},
     *     @SWG\Parameter(
     *         description="ID distributor",
     *         in="path",
     *         name="id_distributeur",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Nom du distributeur",
     *         in="formData",
     *         name="nom",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Adresse du distributeur",
     *         in="formData",
     *         name="adresse",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Code postal du distributeur",
     *         in="formData",
     *         name="cpostal",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Ville du distributeur",
     *         in="formData",
     *         name="ville",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Pays du distributeur",
     *         in="formData",
     *         name="pays",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Téléphone du distributeur",
     *         in="formData",
     *         name="telephone",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Distributor updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Distributeur",
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
        $validator = Validator::make($request->all(), [
            'nom' => 'max:255',
            'telephone' => 'max:255'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $distributeur = Distributeur::find($id);
        $distributeur->nom = $request->nom;
        $distributeur->adresse = $request->adresse;
        $distributeur->cpostal = $request->cpostal;
        $distributeur->ville = $request->ville;
        $distributeur->pays = $request->pays;
        $distributeur->telephone = $request->telephone;
        $distributeur->save();

        return response()->json(
            $distributeur,
            200);
    }

    /**
     * @SWG\Delete(
     *     path="/distributeur/{id_distributeur}",
     *     summary="Delete a distributor",
     *     description="Use this method to delete a distributor based on its id.",
     *     operationId="deleteDistributor",
     *     tags={"distributeur"},
     *     @SWG\Parameter(
     *         description="Distributor ID to delete",
     *         in="path",
     *         name="id_distributeur",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Distributor deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid distributor value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $distributeur = Distributeur::find($id);

        if(empty($distributeur)){
            return response()->json(
                ['error' => 'This distributor does not exist'],
                404);
        }

        $distributeur->delete();
    }
}
