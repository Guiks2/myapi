<?php

namespace App\Http\Controllers;

use App\Reduction;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class ReductionController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/reduction",
     *     summary="Get a reduction list",
     *     description="Use this method to return a listing of reductions.",
     *     operationId="listReduction",
     *     tags={"reduction"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Reduction")
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
        $reductions = Reduction::all();

        if ($reductions->isEmpty()) {
            return response()->json("No content", 204);
        }

        return $reductions;
    }

    /**
     * @SWG\Post(
     *     path="/reduction",
     *     summary="Create a reduction",
     *     description="Use this method to create a reduction",
     *     operationId="createReduction",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"reduction"},
     *     @SWG\Parameter(
     *         description="Name of the reduction",
     *         in="formData",
     *         name="nom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Initial date of the reduction",
     *         in="formData",
     *         name="date_debut",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Final date of the reduction",
     *         in="formData",
     *         name="date_fin",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Percentage of the reduction",
     *         in="formData",
     *         name="pourcentage_reduction",
     *         required=true,
     *         type="number",
     *         maximum="11"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Reduction created",
     *         @SWG\Schema(
     *              ref="#/definitions/Reduction",
     *         ),
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
            'nom' => 'required|unique:reductions|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'pourcentage_reduction' => 'required|max:11'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $reduction = new Reduction;
        $reduction->nom = $request->nom;
        $reduction->date_debut = $request->date_debut;
        $reduction->date_fin = $request->date_fin;
        $reduction->pourcentage_reduction = $request->pourcentage_reduction;
        $reduction->save();

        return response()->json(
            $reduction,
            201);
    }

    /**
     * @SWG\Get(
     *      path="/reduction/{id_reduction}",
     *      summary="Display a single reduction",
     *      description="Use this method to return a single reduction attributes based on its id.",
     *      operationId="showReduction",
     *      tags={"reduction"},
     *      @SWG\Parameter(
     *          name="id_reduction",
     *          in="path",
     *          type="integer",
     *          description="id of reduction to fetch",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Reduction",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404,
     *           description="Reduction not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $reduction = Reduction::find($id);

        if(empty($reduction)){
            return response()->json(
                ['error' => 'This reduction does not exist'],
                404);
        }
        return $reduction;
    }


    /**
     * @SWG\Put(
     *     path="/reduction/{id_reduction}",
     *     summary="Update a reduction",
     *     description="Use this method to update the attributes of a reductionbased on its id.",
     *     operationId="updateReduction",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"reduction"},
     *     @SWG\Parameter(
     *         description="ID reduction",
     *         in="path",
     *         name="id_reduction",
     *         required=true,
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the reduction",
     *         in="formData",
     *         name="nom",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Initial date of the reduction",
     *         in="formData",
     *         name="date_debut",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Final date of the reduction",
     *         in="formData",
     *         name="date_fin",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Percentage of the reduction",
     *         in="formData",
     *         name="pourcentage_reduction",
     *         required=false,
     *         type="number",
     *         maximum="11"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Reduction updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Reduction",
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
            'nom' => 'unique:reductions|max:255',
            'date_debut' => 'date',
            'date_fin' => 'date',
            'pourcentage_reduction' => 'max:11'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $reduction = Reduction::find($id);
        $reduction->nom = $request->nom;
        $reduction->date_debut = $request->date_debut;
        $reduction->date_fin = $request->date_fin;
        $reduction->pourcentage_reduction = $request->pourcentage_reduction;
        $reduction->save();

        return response()->json(
            $reduction,
            201);
    }

    /**
     * @SWG\Delete(
     *     path="/reduction/{id_reduction}",
     *     summary="Delete a reduction",
     *     description="Use this method to delete a reduction based on its id.",
     *     operationId="deleteReduction",
     *     tags={"reduction"},
     *     @SWG\Parameter(
     *         description="Reduction ID to delete",
     *         in="path",
     *         name="id_reduction",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Reduction deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid reduction value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $reduction = Reduction::find($id);

        if(empty($reduction)){
            return response()->json(
                ['error' => 'This Reduction does not exist'],
                404);
        }

        $reduction->delete();
    }
}
