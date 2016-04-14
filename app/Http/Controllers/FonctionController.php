<?php

namespace App\Http\Controllers;

use App\Fonction;
use Illuminate\Http\Request;

use App\Http\Requests;

class FonctionController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/fonction",
     *     summary="Get function list",
     *     description="Use this method to return a listing of functions.",
     *     operationId="listFunction",
     *     tags={"fonction"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Fonction")
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
        $fonctions = Fonction::all();
        return $fonctions;
    }

    /**
     * @SWG\Post(
     *     path="/fonction",
     *     summary="Create a function",
     *     description="Use this method to create a new function.",
     *     operationId="createFunction",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"fonction"},
     *     @SWG\Parameter(
     *         description="Name of the function",
     *         in="formData",
     *         name="nom",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Salary of the function",
     *         in="formData",
     *         name="salaire",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Salary of the function",
     *         in="formData",
     *         name="cadre",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Function created",
     *          @SWG\Schema(
     *               ref="#/definitions/Fonction",
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You don't have authorization for this content"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing or incorrect fields"
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
                'nom' => 'required|max:255',
                'salaire' => 'required|max:255',
                'cadre' => 'required|digits:1'
            ]);

            if($validator->fails()){
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $fonction = new Fonction;
            $fonction->nom = $request->nom;
            $fonction->salaire = $request->salaire;
            $fonction->cadre = $request->cadre;
            $fonction->save();

            return response()->json(
                $fonction,
                201);
        }
    }

    /**
     * @SWG\Get(
     *      path="/fonction/{id_fonction}",
     *      summary="Display a function",
     *      description="Use this method to return a single function attributes based on its id.",
     *      operationId="showFunction",
     *      tags={"fonction"},
     *      @SWG\Parameter(
     *          name="id_fonction",
     *          in="path",
     *          type="integer",
     *          description="Id of function to fetch",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Fonction",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404,
     *           description="Function not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $fonction = Fonction::find($id);

        if(empty($fonction)){
            return response()->json(
                ['error' => 'This function does not exist'],
                404);
        }
        return $fonction;
    }


    /**
     * @SWG\Put(
     *     path="/fonction/{id_fonction}",
     *     summary="Update a function",
     *     description="Use this method to update the attributes of a function based on its id.",
     *     operationId="updateFunction",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"fonction"},
     *     @SWG\Parameter(
     *         description="Name of the function",
     *         in="formData",
     *         name="id_fonction",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the function",
     *         in="formData",
     *         name="nom",
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Salary of the function",
     *         in="formData",
     *         name="salaire",
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Senior status of the function",
     *         in="formData",
     *         name="cadre",
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Function updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Fonction",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You don't have authorization for this content"
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
                ['error' => 'You don\'t have authorization for this content'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'nom' => 'max:255',
                'salaire' => 'max:255',
                'cadre' => 'digits:1'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $fonction = Fonction::find($id);
            $fonction->nom = $request->nom;
            $fonction->salaire = $request->salaire;
            $fonction->cadre = $request->cadre;
            $fonction->save();

            return response()->json(
                $fonction,
                200);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/fonction/{id_fonction}",
     *     summary="Delete a function",
     *     description="Use this method to delete a function based on its id.",
     *     operationId="deleteFunction",
     *     tags={"fonction"},
     *     @SWG\Parameter(
     *         description="Function id to delete",
     *         in="path",
     *         name="id_fonction",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You don't have authorization for this content"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Function deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid function value"
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
            $fonction = Fonction::find($id);

            if (empty($fonction)) {
                return response()->json(
                    ['error' => 'This function does not exist'],
                    404);
            }

            $fonction->delete();
        }
    }
}
