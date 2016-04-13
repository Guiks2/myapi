<?php

namespace App\Http\Controllers;

use App\Employe;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class EmployeController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/employe",
     *     summary="Display a listing of employes.",
     *     tags={"employe"},
     *     @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Employe")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $employe = Employe::all();
        return $employe;
    }

    /**
     * @SWG\Post(
     *     path="/employe",
     *     summary="Create a employe",
     *     description="Use this method to create a employe",
     *     operationId="createEmploye",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"employe"},
     *     @SWG\Parameter(
     *         description="ID personne",
     *         in="formData",
     *         name="id_personne",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="ID fonction",
     *         in="formData",
     *         name="id_fonction",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="employe created",
     *         @SWG\Schema(
     *              ref="#/definitions/Employe",
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
            'id_personne' => 'exists:personnes,id_personne|max:11',
            'id_fonction' => 'exists:fonctions,id_fonction|max:11',
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $employe = new employe;
        $employe->id_personne = $request->id_personne;
        $employe->id_fonction = $request->id_fonction;
        $employe->save();

        return response()->json(
            $employe,
            201);
    }
    /**
     * @SWG\Get(
     *      path="/employe/{id_employe}",
     *      summary="Display a single employe",
     *      description="Use this method to return a single employe attributes based on its id.",
     *      operationId="showEmploye",
     *      tags={"employe"},
     *      @SWG\Parameter(
     *          name="id_employe",
     *          in="path",
     *          type="integer",
     *          description="id of employe to fetch",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Employe",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404,
     *           description="Employe not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $employe = Employe::find($id);

        if(empty($employe)){
            return response()->json(
                ['error' => 'This employe does not exist'],
                404);
        }
        return $employe;
    }

    /**
     * @SWG\Put(
     *     path="/employe/{id_employe}",
     *     summary="Update an employee",
     *     description="Use this method to update the attributes of a employee based on its id.",
     *     operationId="updateEmploye",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"employe"},
     *     @SWG\Parameter(
     *         description="ID employé",
     *         in="path",
     *         name="id_employe",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="ID personne",
     *         in="formData",
     *         name="id_personne",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="ID fonction",
     *         in="formData",
     *         name="id_fonction",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Distributor updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Employe",
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
        $employe = Employe::find($id);

        if (empty($employe)) {
            return response()->json(
                ['error' => 'this employe does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [

            'id_personne' => 'exists:personnes,id_personne',
            'id_fonction' => 'exists:fonctions,id_fonction'

        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }


        $employe->id_personne = $request->id_personne != null ? $request->id_personne : $employe->id_personne;
        $employe->id_fonction = $request->id_fonction != null ? $request->id_fonction : $employe->id_fonction;
        $employe->save();

        return response()->json(
            $employe,
            200);
    }

    /**
     * @SWG\Delete(
     *     path="/employe/{id_employe}",
     *     summary="Delete a employe",
     *     description="Use this method to delete a employe based on its id.",
     *     operationId="deleteEmploye",
     *     tags={"employe"},
     *     @SWG\Parameter(
     *         description="Employe ID to delete",
     *         in="path",
     *         name="id_employe",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Employe deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid employe value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $employe = Employe::find($id);

        if(empty($employe)){
            return response()->json(
                ['error' => 'This employe does not exist'],
                404);
        }

        $employe->delete();
    }
}
