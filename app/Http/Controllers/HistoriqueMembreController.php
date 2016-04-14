<?php

namespace App\Http\Controllers;

use App\HistoriqueMembre;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;


class HistoriqueMembreController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/historique_membre",
     *     summary="Get a member list",
     *     description="Use this method to return a listing of historics.",
     *     operationId="listHistoric",
     *     tags={"historique"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/HistoriqueMembre")
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
        $historique = HistoriqueMembre::all();
        return $historique;
    }

    /**
     * @SWG\Post(
     *     path="/historique_membre",
     *     summary="Create an historic",
     *     description="Use this method to create an historic",
     *     operationId="createHistoric",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"historique"},
     *      @SWG\Parameter(
     *         description="Member ID",
     *         in="formData",
     *         name="id_membre",
     *         type="integer",
     *         required=true,
     *         maximum="11"
     *     ),
     *      @SWG\Parameter(
     *         description="Seance ID",
     *         in="formData",
     *         name="id_seance",
     *         type="integer",
     *         required=true,
     *         maximum="11"
     *     ),
     *     @SWG\Parameter(
     *         description="Seance date",
     *         in="formData",
     *         name="date",
     *         type="string",
     *         required=true,
     *         format="datetime"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Historic created"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Fields missing or incorrect."
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_membre' => 'required|exists:membres,id_membre',
            'id_seance' => 'required|exists:seances,id',
            'date' => 'required|date_format:Y-m-d H:i:s'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $historique = new HistoriqueMembre;
        $historique->id_membre = $request->id_membre;
        $historique->id_seance = $request->id_seance;
        $historique->date = $request->date;

        $historique->save();

        return response()->json(
            $historique,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/historique_membre/{id_historique}",
     *     summary="Find historic by ID",
     *     description="Returns a single historic",
     *     operationId="showHistoric",
     *     tags={"historique"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of historic to return",
     *         in="path",
     *         name="id_historique",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Historic not found"
     *     )
     * )
     */
    public function show($id)
    {
        $historiques = HistoriqueMembre::find($id);

        if(empty($historiques)){
            return response()->json(
                ['error' => 'this historique does not exist'],
                404);
        }
        return $historiques;
    }
    
    /**
     * @SWG\Delete(
     *     path="/historique_membre/{id_historique}",
     *     summary="Delete an historic",
     *     description="Use this method to delete an historicbased on its id.",
     *     operationId="deleteHistoric",
     *     tags={"historique"},
     *     @SWG\Parameter(
     *         description="Member ID to delete",
     *         in="path",
     *         name="id_historique",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Historic deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid historic value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $historique = HistoriqueMembre::find($id);

        if (empty($historique)) {
            return response()->json(
                ['error' => 'this historic does not exist'],
                404);
        }

        $historique->delete();
        return response()->json(
            'Successfully deleted',
            200);
    }
}