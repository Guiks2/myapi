<?php

namespace App\Http\Controllers;

use App\Employe;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;

class EmployeController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/employe",
     *     summary="Get employee list",
     *     description="Use this method to return a listing of employees.",
     *     operationId="listEmployee",
     *     tags={"employe"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Employe")
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
        $employes = Employe::all();
        return $employes;
    }

    /**
     * @SWG\Post(
     *     path="/employe",
     *     summary="Create an employee",
     *     description="Use this method to create a new employee.",
     *     operationId="createEmployee",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"employe"},
     *     @SWG\Parameter(
     *         description="Id of the person",
     *         in="formData",
     *         name="id_personne",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Id of the function",
     *         in="formData",
     *         name="id_fonction",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Employee created",
     *          @SWG\Schema(
     *               ref="#/definitions/Employe",
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You're not allowed to access this service."
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
                ['error' => 'You\'re not allowed to access this service.'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'id_personne' => 'required|numeric',
                'id_fonction' => 'required|numeric',
            ]);

            if($validator->fails()){
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $employe = new Employe;
            $employe->id_personne = $request->id_personne;
            $employe->id_fonction = $request->id_fonction;
            $employe->save();

            return response()->json(
                $employe,
                201);
        }
    }

    /**
     * @SWG\Get(
     *      path="/employe/{id_employe}",
     *      summary="Display an employee",
     *      description="Use this method to return a single employee attributes based on its id.",
     *      operationId="showEmployee",
     *      tags={"employe"},
     *      @SWG\Parameter(
     *          name="id_employe",
     *          in="path",
     *          type="integer",
     *          description="Id of employee to fetch",
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
     *           description="Employee not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $employe = Employe::find($id);

        if(empty($employe)){
            return response()->json(
                ['error' => 'This employee does not exist'],
                404);
        }
        return $employe;
    }

    /**
     * @SWG\Put(
     *     path="/employe/{id_employe}",
     *     summary="Update an employee",
     *     description="Use this method to update the attributes of an employee based on its id.",
     *     operationId="updateEmployee",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"employe"},
     *     @SWG\Parameter(
     *         description="Id of the employee",
     *         in="path",
     *         name="id_employe",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Id of the person",
     *         in="formData",
     *         name="id_personne",
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Id of the function",
     *         in="formData",
     *         name="id_fonction",
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Employee updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Employe",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You're not allowed to access this service."
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
                ['error' => 'You\'re not allowed to access this service .'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'id_personne' => 'numeric|exists:personnes',
                'id_fonction' => 'numeric|exists:fonctions'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $employe = Employe::find($id);
            $employe->id_personne = $request->id_personne != null ? $request->id_personne : $employe->id_personne;
            $employe->id_fonction = $request->id_fonction != null ? $request->id_fonction : $employe->id_fonction;
            $employe->save();

            return response()->json(
                $employe,
                200);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/employe/{id_employe}",
     *     summary="Delete an employee",
     *     description="Use this method to delete an employee based on its id.",
     *     operationId="deleteEmployee",
     *     tags={"employe"},
     *     @SWG\Parameter(
     *         description="Employee id to delete",
     *         in="path",
     *         name="id_employe",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You're not allowed to access this service."
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Employee deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid employee value"
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
            $employe = Employe::find($id);

            if (empty($employe)) {
                return response()->json(
                    ['error' => 'This employee does not exist'],
                    404);
            }

            $employe->delete();
            return response()->json(
                'Successfully deleted',
                200);
        }
    }
}
