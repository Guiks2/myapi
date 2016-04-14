<?php

namespace App\Http\Controllers;

use App\Membre;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class MembreController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/membre",
     *     summary="Get a member list",
     *     description="Use this method to return a listing of member.",
     *     operationId="listMember",
     *     tags={"membre"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Membre")
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
        $membre = Membre::all();
        return $membre;
    }

    /**
     * @SWG\Post(
     *     path="/membre",
     *     summary="Create a member",
     *     description="Use this method to create a new member.",
     *     operationId="createMember",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"membre"},
     *     @SWG\Parameter(
     *         description="Person ID of the member",
     *         in="formData",
     *         name="id_personne",
     *         required=true,
     *         type="number",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Subscribe ID of the member",
     *         in="formData",
     *         name="id_abonnement",
     *         required=true,
     *         type="number",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Inscription date of the member",
     *         in="formData",
     *         name="date_inscription",
     *         required=true,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Initial date of the member subscription",
     *         in="formData",
     *         name="debut_abonnement",
     *         required=true,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Member created",
     *          @SWG\Schema(
     *               ref="#/definitions/Membre",
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Required fields missing or incorrect"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_personne' => 'required|exists:personnes|numeric',
            'id_abonnement' => 'required|exists:abonnements|numeric',
            'date_inscription' => 'required|date',
            'debut_abonnement' => 'required|date'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $membre = new Membre;
        $membre->id_personne = $request->id_personne;
        $membre->id_abonnement = $request->id_abonnement;
        $membre->date_inscription = $request->date_inscription;
        $membre->debut_abonnement = $request->debut_abonnement;
        $membre->save();

        return response()->json(
            $membre,
            201);
    }

    /**
     * @SWG\Get(
     *      path="/membre/{id_membre}",
     *      summary="Display a single member",
     *      description="Use this method to return a single member attributes based on its id.",
     *      operationId="showMember",
     *      tags={"membre"},
     *      @SWG\Parameter(
     *          name="id_membre",
     *          in="path",
     *          type="integer",
     *          description="id of member to fetch",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Membre",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404,
     *           description="Member not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $membre = Membre::find($id);

        if(empty($membre)){
            return response()->json(
                ['error' => 'This member does not exist'],
                404);
        }
        return $membre;
    }


    /**
     * @SWG\Put(
     *     path="/membre/{id_membre}",
     *     summary="Update a member",
     *     description="Use this method to update the attributes of a member based on its id.",
     *     operationId="updateMember",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"membre"},
     *     @SWG\Parameter(
     *         description="ID person",
     *         in="path",
     *         name="id_membre",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Person ID of the member",
     *         in="formData",
     *         name="id_personne",
     *         required=false,
     *         type="number",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Subscribe ID of the member",
     *         in="formData",
     *         name="id_abonnement",
     *         required=false,
     *         type="number",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Inscription date of the member",
     *         in="formData",
     *         name="date_inscription",
     *         required=false,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Initial date of the member subscription",
     *         in="formData",
     *         name="debut_abonnement",
     *         required=false,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Member updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Membre",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Required fields missing or incorrect"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_personne' => 'exists:personnes|numeric',
            'id_abonnement' => 'exists:abonnements|numeric',
            'date_inscription' => 'date',
            'debut_abonnement' => 'date'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $membre = Membre::find($id);
        $membre->id_personne = $request->id_personne;
        $membre->id_abonnement = $request->id_abonnement;
        $membre->date_inscription = $request->date_inscription;
        $membre->debut_abonnement = $request->debut_abonnement;
        $membre->save();

        return response()->json(
            $membre,
            201);
    }

    /**
     * @SWG\Delete(
     *     path="/membre/{id_membre}",
     *     summary="Delete a Member",
     *     description="Use this method to delete a member based on its id.",
     *     operationId="deleteMember",
     *     tags={"membre"},
     *     @SWG\Parameter(
     *         description="Member ID to delete",
     *         in="path",
     *         name="id_membre",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Member deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid member value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $membre = Membre::find($id);

        if(empty($membre)){
            return response()->json(
                ['error' => 'this member does not exist'],
                404);
        }

        $membre->delete();
    }
}
