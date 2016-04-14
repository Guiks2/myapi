<?php

namespace App\Http\Controllers;

use App\Personne;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class PersonneController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/personne",
     *     summary="Get a person list",
     *     description="Use this method to return a listing of persons.",
     *     operationId="listPersonne",
     *     tags={"personne"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Personne")
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
        $personne = Personne::all();
        return $personne;
    }

    /**
     * @SWG\Post(
     *     path="/personne",
     *     summary="Create a person",
     *     description="Use this method to create a new person.",
     *     operationId="createPerson",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"personne"},
     *     @SWG\Parameter(
     *         description="Name of the person",
     *         in="formData",
     *         name="nom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Firstname of the person",
     *         in="formData",
     *         name="prenom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date of birth of the person",
     *         in="formData",
     *         name="date_naissance",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Email of the person",
     *         in="formData",
     *         name="email",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Address of the person",
     *         in="formData",
     *         name="adresse",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="zipcode of the person",
     *         in="formData",
     *         name="cpostal",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="city of the person",
     *         in="formData",
     *         name="ville",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="country of the person",
     *         in="formData",
     *         name="pays",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Person created",
     *          @SWG\Schema(
     *               ref="#/definitions/Personne",
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
        $validator = Validator::make($request->all(), [
            'nom' => 'required|max:255',
            'prenom' => 'required|max:255',
            'date_naissance' => 'required|date',
            'email' => 'required|max:255',
            'adresse' => 'max:255',
            'cpostal' => 'required|max:255',
            'ville' => 'required|max:255',
            'pays' => 'required|max:255'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $personne = new Personne;
        $personne->nom = $request->nom;
        $personne->prenom = $request->prenom;
        $personne->date_naissance = $request->date_naissance;
        $personne->email = $request->email;
        $personne->adresse = $request->adresse;
        $personne->cpostal = $request->cpostal;
        $personne->ville = $request->ville;
        $personne->pays = $request->pays;
        $personne->save();

        return response()->json(
            $personne,
            201);
    }

    /**
     * @SWG\Get(
     *      path="/personne/{id_personne}",
     *      summary="Display a single person",
     *      description="Use this method to return a single person attributes based on its id.",
     *      operationId="showPerson",
     *      tags={"personne"},
     *      @SWG\Parameter(
     *          name="id_personne",
     *          in="path",
     *          type="integer",
     *          description="id of person to fetch",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Personne",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404,
     *           description="Person not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $person = Personne::find($id);

        if(empty($person)){
            return response()->json(
                ['error' => 'This person does not exist'],
                404);
        }
        return $person;
    }

    /**
     * @SWG\Put(
     *     path="/personne/{id_personne}",
     *     summary="Update a person",
     *     description="Use this method to update the attributes of a person based on its id.",
     *     operationId="updatePerson",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"personne"},
     *     @SWG\Parameter(
     *         description="ID person",
     *         in="path",
     *         name="id_personne",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the person",
     *         in="formData",
     *         name="nom",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Firstname of the person",
     *         in="formData",
     *         name="prenom",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date of birth of the person",
     *         in="formData",
     *         name="date_naissance",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Email of the person",
     *         in="formData",
     *         name="email",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Address of the person",
     *         in="formData",
     *         name="adresse",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="zipcode of the person",
     *         in="formData",
     *         name="cpostal",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="city of the person",
     *         in="formData",
     *         name="ville",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="country of the person",
     *         in="formData",
     *         name="pays",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Person updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Personne",
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
            'prenom' => 'max:255',
            'date_naissance' => 'date',
            'email' => 'max:255',
            'adresse' => 'max:255',
            'cpostal' => 'max:255',
            'ville' => 'max:255',
            'pays' => 'max:255'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $personne = Personne::find($id);
        if(empty($personne)){
            return response()->json(
                ['error' => 'Person not found'],
                404);
        }
        $personne->nom = $request->nom != null ? $request->nom : $personne->nom;
        $personne->prenom = $request->prenom != null ? $request->prenom : $personne->prenom;
        $personne->date_naissance = $request->date_naissance != null ? $request->date_naissance : $personne->date_naissance;
        $personne->email = $request->email != null ? $request->email : $personne->email;
        $personne->adresse = $request->adresse != null ? $request->adresse : $personne->adresse;
        $personne->cpostal = $request->cpostal != null ? $request->cpostal : $personne->cpostal;
        $personne->ville = $request->ville != null ? $request->ville : $personne->ville;
        $personne->pays = $request->pays != null ? $request->pays : $personne->pays;
        $personne->save();

        return response()->json(
            $personne,
            201);
    }

    /**
     * @SWG\Delete(
     *     path="/personne/{id_personne}",
     *     summary="Delete a Person",
     *     description="Use this method to delete a person based on its id.",
     *     operationId="deletePerson",
     *     tags={"personne"},
     *     @SWG\Parameter(
     *         description="Person ID to delete",
     *         in="path",
     *         name="id_personne",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Person deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid person value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $person = Personne::find($id);

        if(empty($person)){
            return response()->json(
                ['error' => 'this person does not exist'],
                404);
        }

        $person->delete();
        if(empty($person)){
            return response()->json(
                ['error' => 'Person not found'],
                404);
        }
    }
}
