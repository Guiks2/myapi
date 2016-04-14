<?php

namespace App\Http\Controllers;

use App\Distributeur;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class DistributeurController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/distributeur",
     *     summary="Display a listing of distributors.",
     *     operationId="indexDistributeur",
     *     tags={"distributeur"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Distributeur")
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
        $distributeurs = Distributeur::all();
        if ($distributeurs->isEmpty()) {
            return response()->json("The request didn't return any content.", 204);
        }
        return $distributeurs;
    }


    /**
     * @SWG\Post(
     *     path="/distributeur",
     *     summary="Create a distributor",
     *     description="Use this method to create a distributor",
     *     operationId="storeDistributeur",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"distributeur"},
     *     @SWG\Parameter(
     *         description="Name of the distributor",
     *         in="formData",
     *         name="nom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Address of the distributor",
     *         in="formData",
     *         name="adresse",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Zipcode of the distributor",
     *         in="formData",
     *         name="cpostal",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="City of the distributor",
     *         in="formData",
     *         name="ville",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Country of the distributor",
     *         in="formData",
     *         name="pays",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Phone number of the distributor",
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
     *         description="Missing or incorrect fields"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:distributeurs|max:255',
            'telephone' => 'required|max:15',
            'adresse' => 'max:255',
            'cpostal' => 'numeric',
            'ville' => 'max:255',
            'pays' => 'max:255'
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
     *      operationId="showDistributeur",
     *      tags={"distributeur"},
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
                ['error' => 'Distributor not found'],
                404);
        }
        return $distributeur;
    }

    /**
     * @SWG\Put(
     *     path="/distributeur/{id_distributeur}",
     *     summary="Update a distributor",
     *     description="Use this method to update the attributes of a distributor based on its id.",
     *     operationId="updateDistributeur",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"distributeur"},
     *     @SWG\Parameter(
     *         description="Distributor ID",
     *         in="path",
     *         name="id_distributeur",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the distributor",
     *         in="formData",
     *         name="nom",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Address of the distributor",
     *         in="formData",
     *         name="adresse",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Zipcode of the distributor",
     *         in="formData",
     *         name="cpostal",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="City of the distributor",
     *         in="formData",
     *         name="ville",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Country of the distributor",
     *         in="formData",
     *         name="pays",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Phone number of the distributor",
     *         in="formData",
     *         name="telephone",
     *         type="string",
     *         maximum="15"
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
     *         description="Missing or incorrect fields"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Distributor not found"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'unique:distributeurs|max:255',
            'telephone' => 'max:15',
            'adresse' => 'max:255',
            'cpostal' => 'numeric',
            'ville' => 'max:255',
            'pays' => 'max:255'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $distributeur = Distributeur::find($id);

        if(empty($distributeur)){
            return response()->json(
                ['error' => 'Distributor not found'],
                404);
        }

        $distributeur->nom = $request->nom != null ? $request->nom : $distributeur->nom;
        $distributeur->adresse = $request->adresse != null ? $request->adresse : $distributeur->adresse;
        $distributeur->cpostal = $request->cpostal != null ? $request->cpostal : $distributeur->cpostal;
        $distributeur->ville = $request->ville != null ? $request->ville : $distributeur->ville;
        $distributeur->pays = $request->pays != null ? $request->pays : $distributeur->pays;
        $distributeur->telephone = $request->telephone != null ? $request->telephone : $distributeur->telephone;
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
     *     operationId="destroyDistributeur",
     *     tags={"distributeur"},
     *     @SWG\Parameter(
     *         description="Distributor ID",
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
     *         description="Distributor not found"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $distributeur = Distributeur::find($id);

        if(empty($distributeur)){
            return response()->json(
                ['error' => 'Distributor not found'],
                404);
        }

        $distributeur->delete();

        return response()->json(
            'Successfully deleted',
            200);
    }
}
